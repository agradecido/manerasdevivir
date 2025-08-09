<?php
/**
 * Reparador de rutas Twig: reescribe include/extends/embed/import/from a su nueva ubicación.
 * Uso:
 *   php scripts/repair-twig-includes.php --dry-run               # ver cambios
 *   php scripts/repair-twig-includes.php --write                 # aplicar cambios
 *   php scripts/repair-twig-includes.php --write templates/sub   # otro directorio base
 */

$argvOpts = implode(' ', $argv);
$write = strpos($argvOpts, '--write') !== false;
$dry   = !$write;

// Detectar argumento posicional de base (ignorar el propio script)
$positional = array_values(array_filter($argv, function($a){ return isset($a[0]) && $a[0] !== '-'; }));
$base = null;
if (count($positional) >= 2) {
    // $positional[0] es la ruta del script, [1] sería la base opcional
    $candidate = $positional[1];
    if (!empty($candidate)) {
        $base = $candidate;
    }
}
if (!$base) {
    $base = __DIR__ . '/../templates';
}
$base = realpath($base);
if (!$base || !is_dir($base)) {
    fwrite(STDERR, "Base de templates no válida: " . ($base ?: '[null]') . "\n");
    exit(1);
}

function relpath(string $from, string $to): string {
    $from = str_replace('\\', '/', $from);
    $to   = str_replace('\\', '/', $to);
    $fromParts = array_values(array_filter(explode('/', rtrim($from, '/'))));
    $toParts   = array_values(array_filter(explode('/', rtrim($to, '/'))));
    while (count($fromParts) && count($toParts) && ($fromParts[0] === $toParts[0])) {
        array_shift($fromParts);
        array_shift($toParts);
    }
    return str_repeat('../', count($fromParts)) . implode('/', $toParts);
}

// Indexar todos los .twig por nombre de archivo
$rii = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($base, FilesystemIterator::SKIP_DOTS));
$index = []; // basename.twig (lower) => [relative paths]
$twigFiles = [];
foreach ($rii as $file) {
    if ($file->isFile() && strtolower($file->getExtension()) === 'twig') {
        $twigFiles[] = $file->getPathname();
        $rel = ltrim(str_replace($base, '', $file->getPathname()), DIRECTORY_SEPARATOR);
        $index[strtolower(basename($file->getFilename()))][] = $rel;
    }
}

$totalChangedFiles = 0;
$totalReplacements = 0;

$pattern = '/\b(extends|include|embed|import|from)\s+(?P<q>[\'"])(?P<path>[^\'"]+)(?P=q)/';

foreach ($twigFiles as $twigPath) {
    $src = file_get_contents($twigPath);
    $orig = $src;
    $dirOfFile = dirname($twigPath);
    $src = preg_replace_callback($pattern, function($m) use ($base, $dirOfFile, $index, &$totalReplacements) {
        $path = $m['path'];
        // Ignorar rutas namespaced (@namespace/...)
        if (strlen($path) && $path[0] === '@') {
            return $m[0];
        }
        // Si ya existe tal cual, no tocar
        $abs = $base . DIRECTORY_SEPARATOR . $path;
        if (is_file($abs)) {
            return $m[0];
        }
        // Probar relativo al archivo actual
        $absRel = realpath($dirOfFile . DIRECTORY_SEPARATOR . $path);
        if ($absRel !== false && strpos($absRel, $base . DIRECTORY_SEPARATOR) === 0) {
            return $m[0];
        }
        // Buscar por basename
        $basename = strtolower(basename($path));
        if (!isset($index[$basename])) {
            return $m[0];
        }
        $candidates = $index[$basename];
        // Solo reescribir si hay coincidencia única
        if (count($candidates) !== 1) {
            return $m[0];
        }
        $newRel = $candidates[0];
        $totalReplacements++;
        return $m[1] . ' ' . $m['q'] . $newRel . $m['q'];
    }, $src);

    if ($src !== $orig) {
        $totalChangedFiles++;
        if ($dry) {
            echo "[DRY] Cambios en: " . str_replace($base . DIRECTORY_SEPARATOR, '', $twigPath) . PHP_EOL;
        } else {
            file_put_contents($twigPath, $src);
            echo "Reescrito: " . str_replace($base . DIRECTORY_SEPARATOR, '', $twigPath) . PHP_EOL;
        }
    }
}

echo ($dry ? "[DRY] " : "") . "Archivos modificados: $totalChangedFiles, reemplazos: $totalReplacements\n";