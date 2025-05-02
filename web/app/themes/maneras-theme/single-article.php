<?php
/**
 *
 * single-article.php
 *
 * Template for displaying single article posts
 * This file is a bridge between the custom post type "article" and the theme's rendering system.
 */

namespace ManerasTheme;

if ( file_exists( __DIR__ . '/vendor/autoload.php' ) ) {
	require_once __DIR__ . '/vendor/autoload.php';
}

if ( function_exists( 'render' ) ) {
	render( 'single-article' );
}
