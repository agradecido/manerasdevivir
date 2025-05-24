<?php

namespace ManerasTheme;

use Timber\Timber;
use Timber\ImageHelper;

/**
 * Clase para manejar el procesamiento y optimización de imágenes
 */
class ImageProcessor {

	/**
	 * Base URL para las imágenes de assets
	 *
	 * @var string
	 */
	private static string $assetsUrl;

	/**
	 * Inicializar el procesador de imágenes
	 */
	public static function init() {
		// Obtener la URL base para assets.
		self::$assetsUrl = get_template_directory_uri() . '/assets/dist/images/';

		// Agregar filtros para Timber.
		add_filter( 'timber/twig', array( self::class, 'addTwigFilters' ) );

		// Agregar filtros para procesar imágenes en Timber.
		add_filter( 'timber/image/src', array( self::class, 'processImageUrl' ), 10, 3 );

		// Soporte para WebP.
		add_filter( 'upload_mimes', array( self::class, 'addWebpSupport' ) );
	}

	/**
	 * Agregar soporte para WebP.
	 *
	 * @param array $mimes Array de tipos MIME permitidos.
	 * @return array
	 */
	public static function addWebpSupport( $mimes ) {
		$mimes['webp'] = 'image/webp';
		return $mimes;
	}

	/**
	 * Agregar filtros Twig personalizados
	 *
	 * @param \Twig\Environment $twig Instancia de Twig.
	 * @return \Twig\Environment
	 */
	public static function addTwigFilters( $twig ) {
		// Filtro para devolver versión responsiva de imagen.
		$twig->addFilter( new \Twig\TwigFilter( 'responsive_image', array( self::class, 'getResponsiveImage' ) ) );

		// Filtro para devolver versión WebP de una imagen.
		$twig->addFilter( new \Twig\TwigFilter( 'webp', array( self::class, 'getWebpVersion' ) ) );

		// Filtro para imágenes de assets estáticos.
		$twig->addFilter( new \Twig\TwigFilter( 'asset_image', array( self::class, 'getAssetImage' ) ) );

		return $twig;
	}

	/**
	 * Procesamiento de URL de imagen en Timber
	 *
	 * @param string   $src URL de la imagen.
	 * @param int|null $id ID de la imagen (opcional).
	 * @param string   $size Tamaño de la imagen (opcional).
	 * @return string URL procesada de la imagen.
	 */
	public static function processImageUrl( $src, $id = null, $size = 'full' ) {
		// Si estamos en desarrollo o si el usuario es administrador,
		// usa la versión sin optimizar para facilitar el desarrollo
		if ( WP_DEBUG && current_user_can( 'manage_options' ) ) {
			return $src;
		}

		// En producción, intenta usar WebP para navegadores compatibles
		if ( self::browserSupportsWebp() && ! self::isGif( $src ) ) {
			$webpSrc = self::getWebpVersion( $src );
			if ( $webpSrc ) {
				return $webpSrc;
			}
		}

		return $src;
	}

	/**
	 * Obtener imagen responsiva con srcset y tamaños
	 *
	 * @param string $src URL de la imagen.
	 * @param string $sizes Tamaños de la imagen (default: '100vw').
	 * @param bool   $lazyload Si se debe usar lazyload (default: true).
	 * @return array Array con atributos de la imagen responsiva.
	 */
	public static function getResponsiveImage( $src, $sizes = '100vw', $lazyload = true ) {
		if ( ! $src ) {
			return '';
		}

		$timber_image = new \Timber\Image( $src );
		$srcset       = array();
		$breakpoints  = array( 320, 640, 1024, 1440, 1920 );

		foreach ( $breakpoints as $width ) {
			$resized  = $timber_image->src( $width );
			$srcset[] = "{$resized} {$width}w";
		}

		$srcset_attr = implode( ', ', $srcset );
		$lazy_attr   = $lazyload ? 'loading="lazy"' : '';

		return array(
			'src'       => $timber_image->src(),
			'srcset'    => $srcset_attr,
			'sizes'     => $sizes,
			'lazy_attr' => $lazy_attr,
			'alt'       => $timber_image->alt(),
			'width'     => $timber_image->width(),
			'height'    => $timber_image->height(),
		);
	}

	/**
	 * Obtener versión WebP de una imagen
	 *
	 * @param string $src URL de la imagen.
	 * @return string URL de la imagen WebP o la original si no se encuentra.
	 */
	public static function getWebpVersion( $src ) {
		if ( ! $src || self::isGif( $src ) ) {
			return $src;
		}

		// Para imágenes de la librería de medios
		if ( strpos( $src, wp_upload_dir()['baseurl'] ) !== false ) {
			$webp_src = preg_replace( '/\.(jpe?g|png)$/i', '.webp', $src );

			// Verificar si existe el archivo WebP
			$webp_path = ImageHelper::url_to_file_location( $webp_src );

			if ( file_exists( $webp_path ) ) {
				return $webp_src;
			}
		}

		// Para imágenes de assets
		if ( strpos( $src, get_template_directory_uri() . '/assets/' ) !== false ) {
			$src_parts = pathinfo( $src );
			$webp_src  = $src_parts['dirname'] . '/' . $src_parts['filename'] . '.webp';

			// No podemos verificar la existencia física ya que es una URL, así que lo retornamos directamente
			return $webp_src;
		}

		return $src;
	}

	/**
	 * Obtener imagen de los assets estáticos
	 *
	 * @param string      $filename Nombre del archivo de imagen.
	 * @param string|null $size Tamaño de la imagen (opcional).
	 * @return string URL de la imagen.
	 */
	public static function getAssetImage( $filename, $size = null ) {
		$base_url = self::$assetsUrl;

		if ( ! $size ) {
			return $base_url . $filename;
		}

		$file_parts     = pathinfo( $filename );
		$sized_filename = $file_parts['filename'] . '-' . $size . '.' . $file_parts['extension'];

		return $base_url . $sized_filename;
	}

	/**
	 * Detectar si el navegador soporta WebP
	 *
	 * @return bool
	 */
	protected static function browserSupportsWebp() {
		// Verificar encabezados Accept
		if ( isset( $_SERVER['HTTP_ACCEPT'] ) && strpos( $_SERVER['HTTP_ACCEPT'], 'image/webp' ) !== false ) {
			return true;
		}

		// Verificar User-Agent
		if ( isset( $_SERVER['HTTP_USER_AGENT'] ) ) {
			$ua = $_SERVER['HTTP_USER_AGENT'];

			// Chrome 32+, Edge, Firefox 65+, Opera 19+
			if ( preg_match( '/Chrome\/([0-9]+)/', $ua, $matches ) && (int) $matches[1] >= 32 ) {
				return true;
			}

			if ( strpos( $ua, 'Edge' ) !== false ) {
				return true;
			}

			if ( preg_match( '/Firefox\/([0-9]+)/', $ua, $matches ) && (int) $matches[1] >= 65 ) {
				return true;
			}

			if ( preg_match( '/OPR\/([0-9]+)/', $ua, $matches ) && (int) $matches[1] >= 19 ) {
				return true;
			}
		}

		return false;
	}

	/**
	 * Verificar si la URL es de una imagen GIF
	 *
	 * @param string $src URL de la imagen.
	 * @return bool
	 */
	protected static function isGif( $src ) {
		return preg_match( '/\.gif$/i', $src );
	}
}
