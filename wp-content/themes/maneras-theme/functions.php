<?php

use ManerasTheme\Theme;
use ManerasTheme\Controllers\Pages\SubmitNews;
use ManerasTheme\Features\NewsSubmission;

require_once __DIR__ . '/vendor/autoload.php';

// Initialize Twig
$twig_loader = null;
$twig_env = null;
if (class_exists('\Twig\Loader\FilesystemLoader')) {
    $twig_loader = new \Twig\Loader\FilesystemLoader(get_template_directory() . '/templates');
}
if ($twig_loader && class_exists('\Twig\Environment')) {
    $twig_env = new \Twig\Environment($twig_loader, [
        'cache' => false, // Or get_template_directory() . '/cache/twig',
        'debug' => defined('WP_DEBUG') && WP_DEBUG,
    ]);
    // Initialize Twig in the Theme class
    if ($twig_env && class_exists('ManerasTheme\Theme')) {
        // ManerasTheme\Theme::init_twig($twig_env); // Will be called after $theme_instance is created
    }
}


// Load theme logic.
$theme_instance = new Theme();

// Initialize Twig in the Theme class, now passing the instance
if ($twig_env && isset($theme_instance) && method_exists('ManerasTheme\Theme', 'init_twig')) {
    ManerasTheme\Theme::init_twig($twig_env, $theme_instance);
}

// Registrar el controlador de la página de envío de noticias.
SubmitNews::register();

// Inicializar la funcionalidad de envío de noticias.
NewsSubmission::init();

/**
 * Helper function to get the footer with proper context.
 */
function maneras_get_footer() {
	$app_controller         = new ManerasTheme\Controllers\App();
	// Simplified context for now
	$context = []; // Start with empty context
	$context['site'] = $app_controller->site(); // Assuming this method doesn't use Timber
	// Menu fetching likely needs direct WP calls now or a refactored App controller method
	$context['menu'] = $app_controller->menu();
	$context['footer_menu'] = isset( $context['menu']['footer'] ) ? $context['menu']['footer'] : null;
	// ... other essential global context items

	if (ManerasTheme\Theme::get_twig()) {
		ManerasTheme\Theme::get_twig()->display( 'partials/footer.twig', $context );
	}
}

/**
 * Helper function to get the header with proper context
 */
function maneras_get_header() {
	$header_controller = new ManerasTheme\Controllers\Header();
	$context           = $header_controller->get_context(); // Assuming this doesn't use Timber

	if (ManerasTheme\Theme::get_twig()) {
		ManerasTheme\Theme::get_twig()->display( 'partials/header.twig', $context );
	}
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
