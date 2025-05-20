<?php

namespace ManerasTheme;

use Timber\Timber;

class Theme {

	public function __construct() {
		add_action( 'after_setup_theme', array( $this, 'setup' ) );
		add_filter( 'timber/context', array( $this, 'addToContext' ) );
	}

	public function setup() {
		add_theme_support( 'title-tag' );
		add_theme_support( 'post-thumbnails' );
		add_theme_support( 'menus' );
	}

	public function addToContext( $context ) {
		$context['site'] = array(
			'name'        => get_bloginfo( 'name' ),
			'description' => get_bloginfo( 'description' ),
		);
		return $context;
	}
}
