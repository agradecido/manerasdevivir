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

error_log( '✅ Estamos' );
add_action(
	'template_redirect',
	function () {
		if ( is_home() && is_paged() ) {
			error_log( '✅ Estamos en la home paginada: page ' . get_query_var( 'paged' ) );
		}
	}
);

/**
 * Load theme and project dependencies via Composer.
 */
if ( file_exists( __DIR__ . '/vendor/autoload.php' ) ) {
	require_once __DIR__ . '/vendor/autoload.php';
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

/**
 * Register template routing for Blade templates.
 */
add_filter(
	'template_include',
	function () {
		if ( is_front_page() ) {
			render( 'pages.home' );
			exit;
		}

		if ( is_single() && get_post_type() === 'article' ) {
			global $post;
			setup_postdata( $post );

			render( 'pages.articles.single-article', array( 'article' => $post ) );
			wp_reset_postdata();
			exit;
		}

		if ( is_archive() && is_post_type_archive( 'article' ) ) {
			render( 'pages.articles.archive-article' );
			exit;
		}

		if ( is_single() && get_post_type() === 'event' ) {
			render( 'pages.events.single-event', array( 'event' => get_post() ) );
			exit;
		}

		if ( is_archive() && is_post_type_archive( 'event' ) ) {
			render(
				'pages.events.archive-event',
				array()
			);
			exit;
		}

		if ( is_single() && get_post_type() === 'interview' ) {
			global $post;
			setup_postdata( $post );

			render( 'pages.interviews.single-interview', array( 'interview' => $post ) );
			wp_reset_postdata();
			exit;
		}

		if ( is_archive() && is_post_type_archive( 'interview' ) ) {
			render( 'pages.interviews.archive-interview' );
			exit;
		}

		if ( is_single() && get_post_type() === 'report' ) {
			global $post;
			setup_postdata( $post );

			render( 'pages.reports.single-report', array( 'report' => $post ) );
			wp_reset_postdata();
			exit;
		}

		if ( is_archive() && is_post_type_archive( 'report' ) ) {
			render( 'pages.reports.archive-report' );
			exit;
		}

		if ( is_tax( 'tag' ) ) {
			render( 'pages.taxonomy.tag-list' );
			exit;
		}
		if ( is_tax( 'tag', get_query_var( 'tag' ) ) ) {
			render( 'pages.taxonomy.tag' );
			exit;
		}

		if ( is_404() ) {
			render( 'pages.404' );
			exit;
		}

		// Fallback.
		return get_theme_file_path( 'index.php' );
	}
);
