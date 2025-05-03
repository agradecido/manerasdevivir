<?php
namespace ManerasTheme;

if ( file_exists( __DIR__ . '/vendor/autoload.php' ) ) {
	require_once __DIR__ . '/vendor/autoload.php';
}

use ManerasTheme\Controllers\TagController;

$queried = get_queried_object();
if ( ! $queried || 'post_tag' !== $queried->taxonomy ) {
	return;
}

$slug    = $queried->slug;
$context = TagController::getArchive( $slug );

render( 'pages/taxonomy/tag', $context );
