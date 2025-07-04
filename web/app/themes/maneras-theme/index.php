<?php
/**
 * The main template file
 *
 * @package ManerasTheme
 */

use ManerasTheme\View;

// Render the appropriate template based on WordPress template hierarchy.
if ( is_front_page() ) {
	View::render( 'pages/front-page.twig' );
} elseif ( is_home() || is_archive() ) {
	View::render( 'archives/archive.twig' );
} elseif ( is_page() ) {
	$slug          = get_post_field( 'post_name', get_the_ID() );
	$template_path = 'pages/' . $slug . '.twig';
	if ( file_exists( get_template_directory() . '/templates/' . $template_path ) ) {
		View::render( $template_path );
	} else {
		View::render( 'pages/page.twig' );
	}
} elseif ( is_singular() ) {
	View::render( 'pages/single-article.twig' );
} elseif ( is_search() ) {
	View::render( 'pages/search.twig' );
} elseif ( is_404() ) {
	View::render( 'pages/404.twig' );
} else {
	View::render( 'base/layout.twig' );
}
