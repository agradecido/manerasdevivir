#!/usr/bin/env php
<?php
/**
 * import_articles.php
 *
 * Importador de la tabla antigua `noticias` hacia el CPT `article` de WordPress.
 * - Junta `noticia` + `extendido` con <!--more-->
 * - Importa todas las imágenes (relativas o externas) a la biblioteca de medios
 * - Usa `fecha_wp` para la fecha de publicación
 * - Añade excerpt (primer párrafo de la intro)
 * - Asigna `slug` a post_name
 * - Rellena los meta fields: firma_sender, email_sender, ip_sender, timestamp, fuente, provincia, canonical, home, robots, id_noticia
 * - Asigna `relimage` como imagen destacada
 *
 * CONFIGURA estas variables antes de ejecutar:
 */

// AUMENTAR LÍMITE DE MEMORIA Y TIEMPO
ini_set( 'memory_limit', '1024M' );
set_time_limit( 0 );

date_default_timezone_set( 'Europe/Madrid' );

// CREDENTIALS BD ANTIGUA
$oldDbDsn    = 'mysql:host=mysql;dbname=maneras_noticias;charset=utf8mb4';
$oldDbUser   = 'root';
$oldDbPass   = 'root';
$oldSiteBase = 'https://www.manerasdevivir.com';

// 1) Bootstrap de WordPress
require __DIR__ . '/wp/wp-load.php';
define( 'WP_IMPORTING', true );

// 1.1) Media functions
if ( ! function_exists( 'media_sideload_image' ) ) {
	require_once ABSPATH . 'wp-admin/includes/file.php';
	require_once ABSPATH . 'wp-admin/includes/media.php';
	require_once ABSPATH . 'wp-admin/includes/image.php';
}

// 1.2) Desactivar KSES para HTML completo
remove_filter( 'pre_post_content', 'wp_filter_post_kses' );
remove_filter( 'pre_post_content', 'wp_kses_data' );
remove_filter( 'content_save_pre', 'wp_filter_post_kses' );
remove_filter( 'content_filtered_save_pre', 'wp_filter_post_kses' );

if ( ! function_exists( 'wp_insert_post' ) ) {
	fwrite( STDERR, "✗ WordPress no inicializado\n" );
	exit( 1 );
}

// 1.3) Posponer conteos e invalidación de caché
wp_defer_term_counting( true );
wp_defer_comment_counting( true );
wp_suspend_cache_invalidation( true );

gc_enable(); // Garbage Collector

// 2) Conectar a BD antigua
try {
	$pdo = new PDO(
		$oldDbDsn,
		$oldDbUser,
		$oldDbPass,
		array( PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION )
	);
} catch ( Exception $e ) {
	fwrite( STDERR, "✗ No se pudo conectar a BD antigua: {$e->getMessage()}\n" );
	exit( 1 );
}

// 3) Leer noticias válidas
$sql  = <<<'SQL'
SELECT *
  FROM noticias
 WHERE revisado = 1 AND borrado!=1
 ORDER BY id_noticia DESC
SQL;
$stmt = $pdo->query( $sql );

// 4) Procesar cada registro
while ( $row = $stmt->fetch( PDO::FETCH_ASSOC ) ) {
	$legacyId = $row['id_noticia'];

	// a) Preparar título, excerpt y contenido
	$title    = $row['titular'] ?: "Sin título (#{$legacyId})";
	$intro    = trim( $row['noticia'] ?? '' );
	$extended = trim( $row['extendido'] ?? '' );
	$paras    = preg_split( '/\r?\n\r?\n/', $intro, -1, PREG_SPLIT_NO_EMPTY );
	$excerpt  = isset( $paras[0] ) ? wp_strip_all_tags( $paras[0] ) : '';
	$content  = $intro . "\n\n<!--more-->\n\n" . $extended;
	$content  = apply_import_filters( $content );

	// b) Reescribir busqueda.php
	$content = preg_replace_callback(
		'#(?:\./)*busqueda\.php\?criterio=([^"\']+)#i',
		function ( $m ) {
			return home_url( '?s=' . urlencode( trim( $m[1] ) ) );
		},
		$content
	);

	// c) Fecha de publicación
	$postDate = current_time( 'mysql' );
	$rawFecha = trim( $row['fecha'] ?? '' );
	if ( preg_match( '/^\d{1,2}\.\d{1,2}\.\d{2,4}$/', $rawFecha ) ) {
		list($d, $m, $y) = explode( '.', $rawFecha );
		if ( strlen( $y ) === 2 ) {
			$yi = (int) $y;
			$y  = $yi < 50 ? 2000 + $yi : 1900 + $yi;
		}
		$dt = DateTime::createFromFormat( '!Y-m-d H:i:s', sprintf( '%04d-%02d-%02d 00:00:00', $y, $m, $d ) );
		if ( $dt ) {
			$postDate = $dt->format( 'Y-m-d H:i:s' );
		}
	}

	// d) Canonical
	$rawCanon  = trim( $row['canonical'] ?? '' );
	$canonical = $rawCanon ?: "{$oldSiteBase}/noticias/{$legacyId}";
	if ( ! $rawCanon ) {
		fwrite( STDERR, "ℹ️ Canonical por defecto: {$canonical}\n" );
	}

	// e) Preparar array de post
	$postArr = array(
		'post_type'     => 'article',
		'post_title'    => wp_strip_all_tags( $title ),
		'post_excerpt'  => $excerpt,
		'post_content'  => $content,
		'post_status'   => 'publish',
		'post_date'     => $postDate,
		'post_date_gmt' => get_gmt_from_date( $postDate ),
		'post_name'     => sanitize_title( $row['slug'] ?: $title ),
	);
	// Duplicado controlado por meta canonical
	$exists = get_posts(
		array(
			'post_type'   => 'article',
			'meta_key'    => 'canonical',
			'meta_value'  => $canonical,
			'numberposts' => 1,
			'fields'      => 'ids',
		)
	);
	if ( $exists ) {
		$postArr['ID'] = $exists[0];
		$postId        = wp_update_post( $postArr );
	} else {
		$postId = wp_insert_post( $postArr, true );
	}
	if ( is_wp_error( $postId ) ) {
		fwrite( STDERR, "✗ Error al guardar post: {$postId->get_error_message()}\n" );
		continue;
	}
	echo "✅ Importada noticia {$legacyId} → WP post {$postId}\n";

	// f) Función para importar imágenes sin duplicar
	$import_image = function ( $url ) use ( $postId, $oldSiteBase ) {
		if ( strpos( $url, '//' ) === 0 ) {
			$url = 'https:' . $url;
		} elseif ( ! preg_match( '#^https?://#i', $url ) ) {
			$url = rtrim( $oldSiteBase, '/' ) . '/' . ltrim( $url, '/' );
		}
		// Reusar si ya importada
		$found = get_posts(
			array(
				'post_type'   => 'attachment',
				'meta_key'    => 'imported_from_url',
				'meta_value'  => $url,
				'numberposts' => 1,
				'fields'      => 'ids',
			)
		);
		if ( $found ) {
			return $found[0];
		}
		$id = media_sideload_image( $url, $postId, null, 'id' );
		if ( is_wp_error( $id ) ) {
			foreach ( $id->get_error_messages() as $msg ) {
				fwrite( STDERR, "⚠️ No importada: {$url}: {$msg}\n" );
			}
			return null;
		}
		add_post_meta( $id, 'imported_from_url', $url, true );
		return $id;
	};

	// g) Importar imágenes en contenido
	if ( preg_match_all( '/<img[^>]+src=["\']([^"\']+)["\']/', $content, $matches ) ) {
		foreach ( array_unique( $matches[1] ) as $src ) {
			if ( $attId = $import_image( $src ) ) {
				$newUrl  = wp_get_attachment_url( $attId );
				$content = str_replace( $src, $newUrl, $content );
			}
		}
		wp_update_post(
			array(
				'ID'           => $postId,
				'post_content' => $content,
			)
		);
	}

	// h) Imagen destacada
	$featUrl = trim( $row['relimage'] ?? '' );
	if ( ! $featUrl && isset( $matches[1][0] ) ) {
		$featUrl = $matches[1][0];
	}
	if ( $featUrl && ( $featId = $import_image( $featUrl ) ) ) {
		set_post_thumbnail( $postId, $featId );
	} else {
		echo "ℹ️ Sin imagen destacada para {$legacyId}\n";
	}

	// i) Metadatos adicionales
	$map = array(
		'firma'     => 'firma_sender',
		'email'     => 'email_sender',
		'ip'        => 'ip_sender',
		'timestamp' => 'timestamp',
		'fuente'    => 'fuente',
		'provincia' => 'provincia',
		'canonical' => 'canonical',
		'home'      => 'home',
		'robots'    => 'robots',
	);
	foreach ( $map as $col => $metaKey ) {
		if ( isset( $row[ $col ] ) ) {
			update_post_meta( $postId, $metaKey, $row[ $col ] );
		}
	}
	update_post_meta( $postId, 'id_noticia', $legacyId );

	// Liberar memoria
	wp_reset_postdata();
	gc_collect_cycles();
	unset( $row, $paras, $content, $matches );
}

// Restaurar operaciones diferidas
wp_defer_term_counting( false );
wp_defer_comment_counting( false );
wp_suspend_cache_invalidation( false );

echo "🎉 Importación completada.\n";

function apply_import_filters( $content ) {
	// 1. Centrar imágenes e iframes dentro de <p style="text-align: center;">
	$content = preg_replace_callback(
		'/<p\s+style=["\']text-align:\s*center;?["\']>(.*?)<\/p>/is',
		function ( $m ) {
			$inside = $m[1];

			// Añadir style al <img> o <iframe> que haya dentro
			$inside = preg_replace_callback(
				'/<(img|iframe)([^>]*)>/i',
				function ( $m2 ) {
					$tag   = $m2[1];
					$attrs = $m2[2];

					// Si ya tiene style, añadimos
					if ( preg_match( '/style=["\']([^"\']*)["\']/i', $attrs, $styleMatch ) ) {
						$existingStyle = rtrim( $styleMatch[1], ';' ) . '; ';
						$attrs         = preg_replace(
							'/\sstyle=["\'][^"\']*["\']/i',
							' style="' . $existingStyle . 'display: block; margin-left: auto; margin-right: auto;"',
							$attrs
						);
					} else {
						// Sin style previo
						$attrs .= ' style="display: block; margin-left: auto; margin-right: auto;"';
					}

					return "<{$tag}{$attrs}>";
				},
				$inside
			);

			return '<p style="text-align: center;">' . $inside . '</p>';
		},
		$content
	);

	// 2. Eliminar <p>&nbsp;</p>
	$content = preg_replace( '/<p>(&nbsp;|\s)*<\/p>/i', '', $content );

	return $content;
}
