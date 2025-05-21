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
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueueScripts' ) );
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
	public function enqueueScripts() {
		wp_enqueue_style(
			'maneras-styles',
			get_stylesheet_uri(),
			array(),
			filemtime( get_template_directory() . '/style.css' )
		);

		if ( file_exists( get_template_directory() . '/dist/main.css' ) ) {
			wp_enqueue_style(
				'maneras-main-styles',
				get_template_directory_uri() . '/dist/main.css',
				array(),
				filemtime( get_template_directory() . '/dist/main.css' )
			);
		}

		if ( file_exists( get_template_directory() . '/dist/main.js' ) ) {
			wp_enqueue_script(
				'maneras-main-scripts',
				get_template_directory_uri() . '/dist/main.js',
				array(),
				filemtime( get_template_directory() . '/dist/main.js' ),
				true
			);
		}
	}

	/**
	 * Add custom data to Timber context.
	 *
	 * @param array $context The Timber context.
	 *
	 * @return array The modified context.
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

		$context['posts'] = Timber::get_posts();

		return $context;
	}
}
