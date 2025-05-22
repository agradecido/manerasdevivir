<?php

use Timber\Timber;
use ManerasTheme\Theme;

require_once __DIR__ . '/vendor/autoload.php';

Timber::init();

Timber::$dirname = array( 'templates' );

// Load theme logic.
new Theme();

/**
 * Helper function to get the footer with proper context
 */
function maneras_get_footer() {
	$app_controller         = new ManerasTheme\Controllers\App();
	$context                = Timber::context();
	$context['site']        = $app_controller->site();
	$context['menu']         = isset( $context['menu'] ) ? $context['menu'] : $app_controller->menu();
	$context['footer_menu'] = isset( $context['menu'] ) && isset( $context['menu']['footer'] ) ? $context['menu']['footer'] : null;

	Timber::render( 'partials/footer.twig', $context );
}

/**
 * Helper function to get the header with proper context
 */
function maneras_get_header() {
	$app_controller          = new ManerasTheme\Controllers\App();
	$context                 = Timber::context();
	$context['site']         = $app_controller->site();
	$context['menu']         = isset( $context['menu'] ) ? $context['menu'] : $app_controller->menu();
	$context['primary_menu'] = isset( $context['menu'] ) && isset( $context['menu']['primary'] ) ? $context['menu']['primary'] : null;

	Timber::render( 'partials/header.twig', $context );
}
