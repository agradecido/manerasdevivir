<?php

use Timber\Timber;
use ManerasTheme\Theme;
use ManerasTheme\Controllers\Pages\SubmitNews;
use ManerasTheme\Features\NewsSubmission;
use ManerasTheme\Breadcrumbs;


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

	Timber::render( 'base/footer.twig', $context );
}

/**
 * Helper function to get the header with proper context
 * 
 * @return void
 */
function maneras_get_header(): void {
	$header_controller = new ManerasTheme\Controllers\Header();
	$context           = $header_controller->get_context();

	Timber::render( 'base/header.twig', $context );
}

/**
 * Helper function to transform YouTube iframes into responsive containers.
 * This function searches for YouTube iframes in the content and wraps them in a responsive container.
 *
 * @param string $content El contenido a filtrar.
 * @return string Contenido con los iframes de YouTube en contenedores responsivos.
 */
function maneras_make_youtube_videos_responsive( $content ): string {

	$pattern = '/<iframe[^>]*?src=["\'](https?:\/\/(?:www\.)?(?:youtube\.com|youtu\.be)\/[^"\']+)["\'][^>]*>\s*<\/iframe>/is';

    $replacement = '<div class="site-container site-container--16x9">$0</div>';

    $content = preg_replace( $pattern, $replacement, $content );

    return $content;
    }

    add_filter( 'the_content', 'maneras_make_youtube_videos_responsive' );
    add_filter( 'widget_text_content', 'maneras_make_youtube_videos_responsive' );

    add_filter(
    'timber/context',
    function ( $context ) {
    $breadcrumbs = new Breadcrumbs();
    $items = $breadcrumbs->get_items();
    $json_ld = $breadcrumbs->get_json_ld();

    $context['breadcrumbs'] = $items;
    $context['breadcrumbs_json_ld'] = $json_ld;
    return $context;
    });

    // Add custom locations for Timber (Timber 2.x).
    add_filter('timber/locations', function(array $locations){
        $base = get_stylesheet_directory() . '/templates';
        // Ensure widgets namespace points to templates/widgets
        $locations['widgets'] = isset($locations['widgets']) && is_array($locations['widgets'])
            ? array_values(array_unique(array_merge($locations['widgets'], [ $base . '/widgets' ])))
            : [ $base . '/widgets' ];
        return $locations;
    });