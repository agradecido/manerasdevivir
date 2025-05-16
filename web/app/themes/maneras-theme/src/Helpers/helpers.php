<?php
// phpcs:ignore WordPress.NamingConventions.ValidFunctionName.FunctionNameInvalid
// phpcs:ignore WordPress.NamingConventions.ValidFunctionName.FunctionName
/**
 * Helpers for the theme.
 *
 * Some of them are based on the Laravel framework.
 *
 * helpers/helpers.php
 */

if ( file_exists( __DIR__ . '/vendor/autoload.php' ) ) {
	require_once __DIR__ . '/vendor/autoload.php';
}

use Illuminate\Container\Container;
use Illuminate\Events\Dispatcher;
use Illuminate\Filesystem\Filesystem;
use Illuminate\View\Compilers\BladeCompiler;
use Illuminate\View\Engines\CompilerEngine;
use Illuminate\View\Engines\PhpEngine;
use Illuminate\View\Engines\EngineResolver;
use Illuminate\View\Factory;
use Illuminate\View\FileViewFinder;


	/**
	 * Returns the versioned URL of an asset processed by Vite.
	 *
	 * @param string $path Path you pass (may or may not include "resources/")
	 * @return string Public URL to the file in public/.vite/
	 */
function vite_asset( string $path ): string {
	static $manifest = null;
	if ( is_null( $manifest ) ) {
		$manPath = get_stylesheet_directory() . '/public/.vite/manifest.json';
		if ( ! file_exists( $manPath ) ) {
			return '';
		}
		$manifest = json_decode( file_get_contents( $manPath ), true );
	}

	// Normalize by removing the "resources/" prefix if it exists
	$normalized = preg_replace( '#^resources/#', '', ltrim( $path, '/' ) );

	// 1) Try the normalized key
	if ( isset( $manifest[ $normalized ]['file'] ) ) {
		return get_stylesheet_directory_uri()
			. '/public/'
			. $manifest[ $normalized ]['file'];
	}

	// 2) Try the key as is (in case no prefix is used)
	if ( isset( $manifest[ $path ]['file'] ) ) {
		return get_stylesheet_directory_uri()
			. '/public/'
			. $manifest[ $path ]['file'];
	}

	// 3) As a fallback, search by entry['src']
	foreach ( $manifest as $entry ) {
		if ( isset( $entry['src'] ) ) {
			// compare with both versions
			if ( $entry['src'] === $normalized || $entry['src'] === $path ) {
				return get_stylesheet_directory_uri()
					. '/public/'
					. $entry['file'];
			}
		}
	}

	// 4) Not found
	return '';
}


/**
 * Get the breadcrumbs for the current page.
 */
function mdv_breadcrumbs(): array {
	if ( is_front_page() || is_home() ) {
		return array();
	}

	$crumbs = array(
		array(
			'label' => 'Inicio',
			// 'icon'  => 'home',
			'url'   => home_url( '/' ),
		),
	);

	// CPT «event» → archivo.
	if ( is_post_type_archive( 'event' ) ) {
		$crumbs[] = array(
			'label' => 'Conciertos',
			'url'   => null,
		);
		return $crumbs;
	}

	// CPT «article» → archivo.
	if ( is_post_type_archive( 'article' ) ) {
		$crumbs[] = array(
			'label' => 'Noticias',
			'url'   => null,
		);
		return $crumbs;
	}

	// Single de evento.
	if ( is_singular( 'event' ) ) {
		$crumbs[] = array(
			'label' => 'Conciertos',
			'url'   => get_post_type_archive_link( 'event' ),
		);
	}

	// Single de artículo.
	if ( is_singular( 'article' ) ) {
		$crumbs[] = array(
			'label' => 'Noticias',
			'url'   => get_post_type_archive_link( 'article' ),
		);
	}

	// CPT «interview» → archivo.
	if ( is_post_type_archive( 'interview' ) ) {
		$crumbs[] = array(
			'label' => 'Entrevistas',
			'url'   => null,
		);
		return $crumbs;
	}

	// Single de entrevista.
	if ( is_singular( 'interview' ) ) {
		$crumbs[] = array(
			'label' => 'Entrevistas',
			'url'   => get_post_type_archive_link( 'interview' ),
		);
	}
	// CPT «report» → archivo.
	if ( is_post_type_archive( 'report' ) ) {
		$crumbs[] = array(
			'label' => 'Reportajes',
			'url'   => null,
		);
		return $crumbs;
	}
	// Single de reportaje.
	if ( is_singular( 'report' ) ) {
		$crumbs[] = array(
			'label' => 'Reportajes',
			'url'   => get_post_type_archive_link( 'report' ),
		);
	}

	// Otros casos (post, page…).
	$crumbs[] = array(
		'label' => get_the_title() ?: get_the_archive_title(),
		'url'   => null,
	);

	return $crumbs;
}


/**
 * Get the daily quote.
 *
 * @return string
 */
function mdv_cita() {
	$cita   = 'No pienses que estoy muy triste si no me ves sonreir';
	$quote  = '<blockquote class="mt-2 md:mt-0">';
	$quote .= '<p class="mb-0 text-sm italic text-gray-500 dark:text-gray-400">' . $cita . '</p>';
	$quote .= '</blockquote>';
	return $quote;
}


/**
 * Limpia y formatea el título de un evento para darle un aspecto homogéneo.
 *
 * @param string $title El título a limpiar y formatear.
 * @return string El título limpio con formato homogéneo.
 */
function mdv_clean_event_title( string $title ): string {
	// Decodificar entidades HTML.
	$title = html_entity_decode( $title );

	// Reemplazar códigos HTML específicos.
	$title = str_replace( '&#8211;', '-', $title );
	$title = str_replace( '&amp;#8211;', '-', $title );

	// Convertir guiones y símbolos "+" a separadores.
	$title = str_replace( array( ' - ', ' – ', ' — ', ' — ', '–', '—' ), ', ', $title );
	$title = preg_replace( '/\s*-\s*/', ', ', $title );
	$title = preg_replace( '/\s*\+\s*/', ', ', $title );

	// Eliminar espacios múltiples y limpiar.
	$title = preg_replace( '/\s+/', ' ', $title );
	$title = trim( $title );
	$title = trim( $title, ',' );
	$title = trim( $title, '+' );
	$title = trim( $title, '-' );

	// Eliminar comas múltiples y espacios alrededor de comas.
	$title = preg_replace( '/\s*,\s*/', ', ', $title );
	$title = preg_replace( '/,+/', ',', $title );

	// Convertir todo a minúsculas primero usando mb_strtolower para preservar acentos.
	$title = function_exists( 'mb_strtolower' ) ? mb_strtolower( $title, 'UTF-8' ) : strtolower( $title );

	// Dividir en partes para procesamiento.
	$parts = explode( ',', $title );
	$parts = array_map( 'trim', $parts );
	$parts = array_filter( $parts ); // Eliminar elementos vacíos.

	// Aplicar Title Case a cada parte (primera letra de cada palabra en mayúscula).
	$formatted_parts = array();
	foreach ( $parts as $part ) {
		// Convertir a Title Case, pero mantener palabras como "y", "de", "el" en minúscula.
		$words  = explode( ' ', $part );
		$result = array();

		foreach ( $words as $i => $word ) {
			// Lista de palabras que deben mantenerse en minúsculas (excepto al inicio).
			$lowercase_words = array( 'y', 'e', 'de', 'del', 'la', 'el', 'los', 'las', 'un', 'una', 'a', 'al', 'en', 'con', 'por', 'para' );

			// Preservar acrónimos con puntos (como P.I.L.T.).
			if ( preg_match( '/^([a-z]\.)+[a-z]?\.?$/', $word ) ) {
				$result[] = function_exists( 'mb_strtoupper' ) ? mb_strtoupper( $word, 'UTF-8' ) : strtoupper( $word );
			}
			// Primera palabra o no es de las que van en minúscula.
			elseif ( $i === 0 || ! in_array( $word, $lowercase_words ) ) {
				// Usar mb_strtoupper para la primera letra y mantener el resto.
				if ( function_exists( 'mb_substr' ) && function_exists( 'mb_strtoupper' ) ) {
					$first    = mb_strtoupper( mb_substr( $word, 0, 1, 'UTF-8' ), 'UTF-8' );
					$rest     = mb_substr( $word, 1, null, 'UTF-8' );
					$result[] = $first . $rest;
				} else {
					$result[] = ucfirst( $word );
				}
			}
			// Palabras que siempre van en minúscula (a menos que estén al inicio).
			else {
				$result[] = $word;
			}
		}

		$formatted_parts[] = implode( ' ', $result );
	}

	// Manejar el caso especial para "Y" entre bandas.
	if ( count( $formatted_parts ) > 1 ) {
		$last = array_pop( $formatted_parts );
		return implode( ', ', $formatted_parts ) . ' y ' . $last;
	}

	return implode( ', ', $formatted_parts );
}


/**
 * Mark a post as featured
 *
 * @param int $post_id The ID of the post to feature.
 * @return bool True on success, false on failure.
 */
function maneras_mark_as_featured( $post_id ) {
	return wp_set_object_terms( $post_id, 'destacado', 'featured', false );
}

/**
 * Remove featured mark from a post
 *
 * @param int $post_id The ID of the post to unfeature.
 * @return bool True on success, false on failure.
 */
function maneras_unmark_featured( $post_id ) {
	return wp_set_object_terms( $post_id, array(), 'featured', false );
}

/**
 * Check if a post is featured
 *
 * @param int|null $post_id The ID of the post to check, defaults to current post.
 * @return bool True if post is featured, false otherwise.
 */
function maneras_is_featured( $post_id = null ) {
	if ( null === $post_id ) {
		$post_id = get_the_ID();
	}

	$terms = get_the_terms( $post_id, 'featured' );
	return ! empty( $terms );
}

/**
 * Get featured posts
 *
 * @param string $post_type The post type to get featured posts for.
 * @param int    $count Number of posts to return.
 * @return WP_Query Query result with featured posts.
 */
function maneras_get_featured_posts( $post_type = 'post', $count = 5 ) {
	return new WP_Query(
		array(
			'post_type'      => $post_type,
			'posts_per_page' => $count,
			'tax_query'      => array(
				array(
					'taxonomy' => 'featured',
					'field'    => 'slug',
					'terms'    => 'destacado',
				),
			),
		)
	);
}
