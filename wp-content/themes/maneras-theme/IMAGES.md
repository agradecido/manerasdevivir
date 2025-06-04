# Sistema de procesamiento de imágenes para Maneras Theme

Este sistema permite optimizar y gestionar imágenes en el tema WordPress, con soporte para formato WebP, lazy loading y tamaños responsivos.

## Características

- Conversión automática a formato WebP
- Generación de imágenes responsivas en múltiples tamaños
- Optimización de imágenes JPEG y PNG
- Carga perezosa (lazy loading) de imágenes
- Detección de soporte para WebP en navegadores
- Componentes Twig para imágenes responsivas

## Cómo usar

### Configuración

1. Instala las dependencias necesarias:

```bash
npm install
```

2. Procesa imágenes existentes:

```bash
npm run images
```

3. Construye los assets (CSS y JS):

```bash
npm run build:all
```

### En desarrollo

Durante el desarrollo, puedes ejecutar:

```bash
npm run dev
```

Esto observará los cambios en CSS y JS.

### En plantillas Twig

Para usar imágenes responsivas en las plantillas, puedes utilizar los componentes:

```twig
{# Imagen responsiva básica #}
{% include 'components/responsive-image.twig' with {
    'image': post.thumbnail,
    'class': 'my-custom-class',
    'sizes': '(min-width: 768px) 50vw, 100vw'
} %}

{# Imagen con soporte para WebP y fallback #}
{% include 'components/webp-image.twig' with {
    'image': post.thumbnail,
    'class': 'featured-image',
    'lazy': true
} %}
```

### En código PHP

También puedes usar la clase `ImageProcessor` para procesar imágenes:

```php
use ManerasTheme\ImageProcessor;

// Obtener datos para una imagen responsiva
$image_data = ImageProcessor::getResponsiveImage($post->thumbnail);

// Obtener URL de imagen WebP
$webp_url = ImageProcessor::getWebpVersion($image_url);
```

## Opciones de personalización

El sistema se puede personalizar editando los siguientes archivos:

- `scripts/process-images.js`: Configura los tamaños y opciones de procesamiento
- `app/ImageProcessor.php`: Modifica las funciones de procesamiento de imágenes
- `assets/src/css/components/_images.css`: Personaliza los estilos de las imágenes

## Depuración

En modo desarrollo (con WP_DEBUG activado), las imágenes se cargan sin optimización para facilitar el desarrollo.
