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
	$post_type = get_post_type();
	// Prefer specific single template by post type
	$type_template = 'singles/single-' . $post_type . '.twig';
	if ( $post_type === 'post' && file_exists( get_template_directory() . '/templates/singles/single-article.twig' ) ) {
		View::render( 'singles/single-article.twig' );
	} elseif ( file_exists( get_template_directory() . '/templates/' . $type_template ) ) {
		View::render( $type_template );
	} else {
		View::render( 'singles/single.twig' );
	}
} elseif ( is_search() ) {
	View::render( 'search.twig' );
} elseif ( is_404() ) {
	View::render( '404.twig' );
} else {
	View::render( 'base/base.twig' );
}
