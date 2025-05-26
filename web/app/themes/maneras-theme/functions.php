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
	$context['menu']        = isset( $context['menu'] ) ? $context['menu'] : $app_controller->menu();
	$context['footer_menu'] = isset( $context['menu'] ) && isset( $context['menu']['footer'] ) ? $context['menu']['footer'] : null;

	Timber::render( 'partials/footer.twig', $context );
}

/**
 * Helper function to get the header with proper context
 */
function maneras_get_header() {
	$app_controller         = new ManerasTheme\Controllers\App();
	$context                = Timber::context();
	$context['site']        = $app_controller->site();
	$context['menu']        = isset( $context['menu'] ) ? $context['menu'] : $app_controller->menu();
	$context['header_menu'] = isset( $context['menu'] ) && isset( $context['menu']['primary'] ) ? $context['menu']['primary'] : null;

	Timber::render( 'partials/header.twig', $context );
}

/**
 * Hace los iframes de YouTube responsivos.
 * Envuelve los iframes de YouTube en un contenedor con clase site-container para hacerlos responsivos.
 *
 * @param string $content El contenido a filtrar.
 * @return string Contenido con los iframes de YouTube en contenedores responsivos.
 */
function maneras_make_youtube_videos_responsive( $content ) {
	// Busca iframes que contengan youtube.com o youtu.be.
	$pattern = '/<iframe.*?src=["\'](https?:\/\/(www\.)?(youtube\.com|youtu\.be)\/.+?)["\'].*?><\/iframe>/i';

	// Reemplaza iframes por versión responsiva.
	$replacement = '<div class="site-container site-container--16x9">$0</div>';

	// Aplica el reemplazo.
	$content = preg_replace( $pattern, $replacement, $content );

	return $content;
}

// Aplica el filtro al contenido.
add_filter( 'the_content', 'maneras_make_youtube_videos_responsive' );
// También aplica el filtro al contenido de widgets.
add_filter( 'widget_text_content', 'maneras_make_youtube_videos_responsive' );
