<?php

use Timber\Timber;
use ManerasTheme\Theme;
use ManerasTheme\Controllers\Pages\SubmitNews;
use ManerasTheme\Features\NewsSubmission;

require_once __DIR__ . '/vendor/autoload.php';

Timber::init();

Timber::$dirname = array( 'templates' );

// Load theme logic.
new Theme();

// Registrar el controlador de la página de envío de noticias.
SubmitNews::register();

// Inicializar la funcionalidad de envío de noticias.
NewsSubmission::init();

/**
 * Helper function to get the footer with proper context.
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
	$header_controller = new ManerasTheme\Controllers\Header();
	$context           = $header_controller->get_context();

	Timber::render( 'partials/header.twig', $context );
}

/**
 * Helper function to transform YouTube iframes into responsive containers.
 * This function searches for YouTube iframes in the content and wraps them in a responsive container.
 *
 * @param string $content El contenido a filtrar.
 * @return string Contenido con los iframes de YouTube en contenedores responsivos.
 */
function maneras_make_youtube_videos_responsive( $content ): string {

	$pattern = '/<iframe.*?src=["\'](https?:\/\/(www\.)?(youtube\.com|youtu\.be)\/.+?)["\'].*?><\/iframe>/i';

	$replacement = '<div class="site-container site-container--16x9">$0</div>';

	$content = preg_replace( $pattern, $replacement, $content );

	return $content;
}
add_filter( 'the_content', 'maneras_make_youtube_videos_responsive' );
add_filter( 'widget_text_content', 'maneras_make_youtube_videos_responsive' );
