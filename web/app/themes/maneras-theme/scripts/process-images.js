/**
 * Script para procesar imágenes en el tema WordPress
 * - Convierte imágenes a formato WebP
 * - Optimiza JPG y PNG
 * - Genera diferentes tamaños responsivos
 */

const { glob } = require('glob');
const path = require('path');
const fs = require('fs');
const sharp = require('sharp');
const imagemin = require('imagemin');
const imageminMozjpeg = require('imagemin-mozjpeg');
const imageminPngquant = require('imagemin-pngquant');
const imageminWebp = require('imagemin-webp');

// Directorios
const SRC_DIR = path.join(__dirname, '../assets/src/images');
const DIST_DIR = path.join(__dirname, '../assets/dist/images');

// Asegurarse de que los directorios existan
if (!fs.existsSync(SRC_DIR)) {
  fs.mkdirSync(SRC_DIR, { recursive: true });
  console.log(`Creado directorio: ${SRC_DIR}`);
}

if (!fs.existsSync(DIST_DIR)) {
  fs.mkdirSync(DIST_DIR, { recursive: true });
  console.log(`Creado directorio: ${DIST_DIR}`);
}

// Tamaños predefinidos para imágenes responsivas
const SIZES = [
  { width: 320, suffix: 'xs' },
  { width: 640, suffix: 'sm' },
  { width: 1024, suffix: 'md' },
  { width: 1440, suffix: 'lg' },
  { width: 1920, suffix: 'xl' }
];

// Procesamiento principal
async function processImages() {
  try {
    // Buscar todas las imágenes en el directorio fuente
    const files = await glob(`${SRC_DIR}/**/*.{jpg,jpeg,png,gif,svg}`, { dot: true });
    
    if (files.length === 0) {
      console.log('No se encontraron imágenes para procesar.');
      return;
    }

    console.log(`Procesando ${files.length} imágenes...`);
    
    // Copiar archivos SVG directamente
    const svgFiles = files.filter(file => file.toLowerCase().endsWith('.svg'));
    for (const file of svgFiles) {
      const filename = path.basename(file);
      const outputPath = path.join(DIST_DIR, filename);
      
      try {
        // Crear subdirectorios necesarios
        const relativePath = path.relative(SRC_DIR, path.dirname(file));
        const targetDir = path.join(DIST_DIR, relativePath);
        
        if (!fs.existsSync(targetDir)) {
          fs.mkdirSync(targetDir, { recursive: true });
        }
        
        fs.copyFileSync(file, path.join(targetDir, filename));
        console.log(`SVG copiado: ${outputPath}`);
      } catch (error) {
        console.error(`Error al copiar SVG: ${file}`, error);
      }
    }
    
    // Filtrar SVGs para procesar solo JPG/PNG/GIF
    const rasterFiles = files.filter(file => !file.toLowerCase().endsWith('.svg'));
    
    // Optimizar JPG/PNG con imagemin
    await optimizeImages(files);
    
    // Convertir a WebP y generar tamaños responsive
    for (const file of files) {
      // Saltar archivos SVG para WebP y responsive
      if (!file.toLowerCase().endsWith('.svg')) {
        await generateWebP(file);
        await generateResponsiveSizes(file);
      }
    }
    
    console.log('¡Procesamiento de imágenes completado con éxito!');
  } catch (error) {
    console.error('Error al procesar imágenes:', error);
  }
}

// Optimiza JPG/PNG usando imagemin
async function optimizeImages(files) {
  try {
    // Filtrar SVGs
    const rasterFiles = files.filter(file => !file.toLowerCase().endsWith('.svg'));
    
    if (rasterFiles.length === 0) {
      console.log('No hay imágenes raster para optimizar');
      return;
    }
    
    await imagemin(rasterFiles, {
      destination: DIST_DIR,
      plugins: [
        imageminMozjpeg({ quality: 80 }),
        imageminPngquant({ quality: [0.65, 0.8] })
      ]
    });
    console.log('Imágenes JPG/PNG optimizadas');
  } catch (error) {
    console.error('Error al optimizar imágenes:', error);
  }
}

// Genera versión WebP de una imagen
async function generateWebP(filePath) {
  const filename = path.basename(filePath);
  const outputPath = path.join(DIST_DIR, `${path.parse(filename).name}.webp`);
  
  try {
    await imagemin([filePath], {
      destination: path.dirname(outputPath),
      plugins: [imageminWebp({ quality: 80 })]
    });
    console.log(`WebP generado: ${outputPath}`);
  } catch (error) {
    console.error(`Error al convertir a WebP: ${filePath}`, error);
  }
}

// Genera tamaños responsivos de una imagen
async function generateResponsiveSizes(filePath) {
  const filename = path.basename(filePath);
  const { name, ext } = path.parse(filename);
  
  for (const size of SIZES) {
    const outputFilename = `${name}-${size.suffix}${ext}`;
    const outputPath = path.join(DIST_DIR, outputFilename);
    const webpOutputPath = path.join(DIST_DIR, `${name}-${size.suffix}.webp`);
    
    try {
      // Crear versión redimensionada
      await sharp(filePath)
        .resize(size.width)
        .toFile(outputPath);
      
      // Crear versión WebP del tamaño
      await sharp(filePath)
        .resize(size.width)
        .webp({ quality: 80 })
        .toFile(webpOutputPath);
      
      console.log(`Creado: ${outputFilename} y versión WebP`);
    } catch (error) {
      console.error(`Error al redimensionar: ${filePath} a ${size.width}px`, error);
    }
  }
}

// Ejecutar el procesamiento
processImages();
