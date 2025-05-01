<?php
/**
 * Functions for the theme.
 * This file contains theme-specific functionality.
 *
 * PHP version 8.3
 *
 * @category Theme
 * @package  ManerasTheme
 * @author   Javier Sierra <agradecido@manerasdevivir.com>
 * @license  https://opensource.org/licenses/MIT MIT License
 * @link     https://manerasdevivir.com
 */

namespace ManerasTheme;

use Jenssegers\Blade\Blade;

/**
 * Load theme and project dependencies via Composer.
 */
if ( file_exists( __DIR__ . '/vendor/autoload.php' ) ) {
	require_once __DIR__ . '/vendor/autoload.php';
}

/**
 * Render and display a Blade view.
 *
 * @param string $view Name of the view (without .blade.php).
 * @param array  $data Data to pass to the template.
 *
 * @return void
 */
function render( string $view, array $data = array() ): void {
	echo get_blade()->make( $view, $data )->render();
}

add_action(
	'wp_enqueue_scripts',
	function () {
		$theme_uri    = get_template_directory_uri() . '/public';
		$manifestPath = get_template_directory() . '/public/.vite/manifest.json';

		if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
			$dev = 'http://localhost:5173';

			add_action(
				'wp_footer',
				function () {
					$dev = 'http://localhost:5173';
					// Include Vite development client and app.js
					echo '<script type="module" src="' . esc_url( $dev . '/@vite/client' ) . '"></script>';
					echo '<script type="module" src="' . esc_url( $dev . '/resources/js/app.js' ) . '"></script>';
				},
				10
			);
			return;
		}

		if ( ! file_exists( $manifestPath ) ) {
			return;
		}

		$manifest = json_decode( file_get_contents( $manifestPath ), true );
		if ( ! is_array( $manifest ) ) {
			return;
		}

		// Find the first entry point.
		$entry = null;
		foreach ( $manifest as $key => $item ) {
			if ( ! empty( $item['isEntry'] ) ) {
				$entry = $item;
				break;
			}
		}

		if ( ! $entry ) {
			return;
		}

		if ( ! empty( $entry['css'] ) ) {
			foreach ( $entry['css'] as $cssFile ) {
				wp_enqueue_style(
					'maneras-vite-css',
					"{$theme_uri}/{$cssFile}",
					array(),
					null
				);
			}
		}

		wp_enqueue_script(
			'maneras-vite-js',
			"{$theme_uri}/{$entry['file']}",
			array(),
			null,
			true
		);
		wp_script_add_data( 'maneras-vite-js', 'type', 'module' );
	}
);
