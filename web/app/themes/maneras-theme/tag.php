<?php
namespace ManerasTheme;

use ManerasTheme\Controllers\TagArchiveController;

$queried = get_queried_object();
if ( ! $queried || 'post_tag' !== $queried->taxonomy ) {
	// Not a tag archive
	return;
}

$slug    = $queried->slug;
$context = TagArchiveController::getArchive( $slug );

if ( function_exists( 'ManerasTheme\\render' ) ) {
	// Use Sage/Blade rendering
	echo render( 'tag', $context );
} else {
	get_header();
	include locate_template( 'resources/views/taxonomy/tag.blade.php' );
	get_footer();
}
