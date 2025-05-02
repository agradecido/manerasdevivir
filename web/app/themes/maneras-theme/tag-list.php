<?php
/**
 * Template Name: Tag Listing
 */

namespace ManerasTheme;

use ManerasTheme\Controllers\TagController;

if ( function_exists( 'ManerasTheme\\render' ) ) {
	// Render Blade view and pass tags
	echo render( 'taxonomy/tag-list', array( 'tags' => TagController::getTags() ) );
} else {
	get_header();

	// Fallback: load controller and plain PHP view.
	$tags = TagController::getTags();
	include locate_template( 'resources/views/tag-list.blade.php' );

	get_footer();
}
