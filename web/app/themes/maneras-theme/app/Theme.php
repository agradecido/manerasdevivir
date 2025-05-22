<?php

namespace ManerasTheme;

use Timber\Timber;
use ManerasTheme\Controllers\Controller;
use ManerasTheme\ImageProcessor;

class Theme {

	/**
	 * Controller cache.
	 *
	 * @var array
	 */
	protected static $controllers = array();

	/**
	 * Constructor.
	 */
	public function __construct() {
		add_action( 'after_setup_theme', array( $this, 'setup' ) );
		add_filter( 'timber/context', array( $this, 'add_to_context' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_assets' ) );

		// Inicializar el procesador de imágenes.
		ImageProcessor::init();
	}

	/**
	 * Theme configuration.
	 */
	public function setup() {
		add_theme_support( 'title-tag' );
		add_theme_support( 'post-thumbnails' );
		add_theme_support( 'menus' );

		// Añadir soporte para formatos de imagen adicionales.
		add_theme_support( 'responsive-embeds' );
		add_theme_support( 'html5', array( 'gallery', 'caption', 'style', 'script' ) );

		// Añadir soporte para tamaños de imagen personalizados.
		add_image_size( 'featured-large', 1200, 800, true );
		add_image_size( 'featured-medium', 800, 600, true );
		add_image_size( 'featured-small', 400, 300, true );

		register_nav_menus(
			array(
				'primary_navigation' => __( 'Primary Navigation', 'maneras-theme' ),
				'footer_navigation'  => __( 'Footer Navigation', 'maneras-theme' ),
			)
		);
	}

	/**
	 * Enqueue scripts and styles.
	 */
	public function enqueue_assets() {
		// Enqueue Tailwind-generated CSS if it exists.
		$css_path = get_template_directory() . '/assets/dist/main.css';
		if ( file_exists( $css_path ) ) {
			wp_enqueue_style(
				'maneras-main-style',
				get_template_directory_uri() . '/assets/dist/main.css',
				array(),
				filemtime( $css_path )
			);
		}

		// Enqueue JS bundle if present.
		$js_min_path  = get_template_directory() . '/assets/dist/app.min.js';
		$js_dev_path  = get_template_directory() . '/assets/dist/app.js';
		$js_main_path = get_template_directory() . '/assets/dist/main.js';

		// Determinar qué archivo JS usar (minificado en producción, no minificado en desarrollo).
		if ( file_exists( $js_min_path ) && ! WP_DEBUG ) {
			wp_enqueue_script(
				'maneras-scripts',
				get_template_directory_uri() . '/assets/dist/app.min.js',
				array(),
				filemtime( $js_min_path ),
				true
			);
		} elseif ( file_exists( $js_dev_path ) ) {
			wp_enqueue_script(
				'maneras-scripts',
				get_template_directory_uri() . '/assets/dist/app.js',
				array(),
				filemtime( $js_dev_path ),
				true
			);
		}

		if ( file_exists( $js_main_path ) ) {
			wp_enqueue_script(
				'maneras-main-script',
				get_template_directory_uri() . '/assets/dist/main.js',
				array(),
				filemtime( $js_main_path ),
				true
			);
		}
	}

	/**
	 * Add global data to Timber context.
	 *
	 * @param array $context The Timber context.
	 * @return array
	 */
	public function add_to_context( $context ) {
		// Load the appropriate controller.
		$controller = $this->load_controller();

		// Merge controller data with context.
		$controller_data = $controller->toArray();

		if ( ! empty( $controller_data ) ) {
			$context = array_merge( $context, $controller_data );
		}

		// Always load App controller for global data.
		if ( ! ( $controller instanceof \ManerasTheme\Controllers\App ) ) {
			$app      = new \ManerasTheme\Controllers\App();
			$app_data = $app->toArray();

			if ( ! empty( $app_data ) ) {
				$context = array_merge( $context, $app_data );
			}
		}

		return $context;
	}

	/**
	 * Load the appropriate controller based on WordPress template hierarchy.
	 *
	 * @return Controller
	 */
	protected function load_controller() {
		if ( isset( static::$controllers[ $this->get_template_class() ] ) ) {
			return static::$controllers[ $this->get_template_class() ];
		}

		$class = $this->get_controller_class();
		if ( class_exists( $class ) ) {
			static::$controllers[ $this->get_template_class() ] = new $class();
			return static::$controllers[ $this->get_template_class() ];
		}

		$class = $this->get_controller_class( 'App' );
		static::$controllers[ $this->get_template_class() ] = new $class();
		return static::$controllers[ $this->get_template_class() ];
	}

	/**
	 * Get the controller class based on the current template.
	 *
	 * @param string $fallback The fallback controller to use.
	 * @return string
	 */
	protected function get_controller_class( $fallback = 'App' ) {
		$class = $this->get_template_class();

		if ( $class === $fallback ) {
			return "ManerasTheme\\Controllers\\{$fallback}";
		}

		$class = "ManerasTheme\\Controllers\\{$class}";

		if ( class_exists( $class ) ) {
			return $class;
		}

		return "ManerasTheme\\Controllers\\{$fallback}";
	}

	/**
	 * Get the template class name based on WordPress template hierarchy.
	 *
	 * @return string
	 */
	protected function get_template_class() {
		if ( is_front_page() ) {
			return 'FrontPage';
		}

		if ( is_singular() ) {
			return 'Single';
		}

		if ( is_archive() || is_home() ) {
			return 'Archive';
		}

		if ( is_search() ) {
			return 'Search';
		}

		if ( is_404() ) {
			return 'NotFound';
		}

		return 'App';
	}
}
