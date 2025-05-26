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
} elseif ( is_singular() ) {
	View::render( 'pages/single.twig' );
} elseif ( is_search() ) {
	View::render( 'pages/search.twig' );
} elseif ( is_404() ) {
	View::render( 'pages/404.twig' );
} else {
	View::render( 'base/layout.twig' );
}
