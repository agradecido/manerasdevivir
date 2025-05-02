<?php
/**
 *
 * Template for displaying single article posts.
 * This file is a bridge between the custom post type "article" and the theme's rendering system.
 * 
 * @package ManerasTheme
 * @category Theme
 * @version 1.0
 * @author Javier Sierra <agradecido@manerasdevivir.com>
 */

namespace ManerasTheme;

if ( file_exists( __DIR__ . '/vendor/autoload.php' ) ) {
	require_once __DIR__ . '/vendor/autoload.php';
}

if ( function_exists( 'render' ) ) {
	render( 'pages/articles/single-article' );
}
