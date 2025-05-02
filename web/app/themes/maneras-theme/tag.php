<?php
namespace ManerasTheme;

use ManerasTheme\Controllers\TagController;

$queried = get_queried_object();
if ( ! $queried || 'post_tag' !== $queried->taxonomy ) {
	// Not a tag archive.
	return;
}

$slug    = $queried->slug;
$context = TagController::getArchive( $slug );

echo render( 'taxonomy/tag', $context );
