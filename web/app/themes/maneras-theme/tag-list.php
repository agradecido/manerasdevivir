<?php
/**
 * Template Name: Tag Listing
 */

namespace ManerasTheme;

if ( file_exists( __DIR__ . '/vendor/autoload.php' ) ) {
	require_once __DIR__ . '/vendor/autoload.php';
}

use ManerasTheme\Controllers\TagController;

render( 'pages/taxonomy/tag-list', array( 'tags' => TagController::getTags() ) );
