<?php
/**
 * The main template file
 *
 * @package ManerasTheme
 */

use ManerasTheme\View;

// Render the appropriate template based on WordPress template hierarchy.
if ( is_front_page() ) {
	View::render( 'front-page.twig' );
} elseif ( is_home() || is_archive() ) {
	View::render( 'archive.twig' );
} elseif ( is_singular() ) {
	View::render( 'single.twig' );
} elseif ( is_search() ) {
	View::render( 'search.twig' );
} elseif ( is_404() ) {
	View::render( '404.twig' );
} else {
	View::render( 'index.twig' );
}
