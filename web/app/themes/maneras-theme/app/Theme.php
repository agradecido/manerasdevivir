<?php

namespace ManerasTheme;

use Timber\Timber;

class Theme {

	/**
	 * Constructor.
	 */
	public function __construct() {
		add_action( 'after_setup_theme', array( $this, 'setup' ) );
		add_filter( 'timber/context', array( $this, 'addToContext' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueueAssets' ) );
	}

	/**
	 * Theme configuration.
	 */
	public function setup() {
		add_theme_support( 'title-tag' );
		add_theme_support( 'post-thumbnails' );
		add_theme_support( 'menus' );

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
	public function enqueueAssets() {
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
		$js_path = get_template_directory() . '/assets/dist/main.js';
		if ( file_exists( $js_path ) ) {
			wp_enqueue_script(
				'maneras-main-script',
				get_template_directory_uri() . '/assets/dist/main.js',
				array(),
				filemtime( $js_path ),
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
	public function addToContext( $context ) {
		$context['site'] = array(
			'name'        => get_bloginfo( 'name' ),
			'description' => get_bloginfo( 'description' ),
		);

		$context['menu'] = array(
			'primary' => Timber::get_menu( 'primary_navigation' ),
			'footer'  => Timber::get_menu( 'footer_navigation' ),
		);

		if ( is_active_sidebar( 'sidebar-primary' ) ) {
			$context['sidebar'] = Timber::get_widgets( 'sidebar-primary' );
		}

		return $context;
	}
}
