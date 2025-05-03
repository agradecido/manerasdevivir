<?php

namespace ManerasTheme\View\Composers;

use Illuminate\View\Factory;
use WP_Query;

/**
 * Registers view‑composer callbacks for Jenssegers Blade.
 */
class ArchiveEvent {
	/**
	 * Register the view composer for the event archive page.
	 *
	 * @param Factory $blade The Blade factory instance.
	 */
	public static function register( Factory $blade ): void {
		$blade->composer(
			'pages.events.archive-event',
			function ( $view ) {
				$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
				$today = current_time( 'Y-m-d' );

				// Lista de campos de meta a precargar (para rendimiento)
				$meta_fields = array(
					'start_date',
					'end_date',
					'artists',
					'event_city',
					'administrative_division',
					'event_country',
					'venue',
					'venue_address',
					'venue_lat',
					'venue_lon',
					'price',
					'precio_anticipada',
					'precio_taquilla',
					'purchase_url',
					'festival_name',
					'poster',
					'comments',
				);

				// Obtener eventos próximos con todos los metadatos
				$upcoming = new WP_Query(
					array(
						'post_type'              => 'event',
						'posts_per_page'         => 12,
						'paged'                  => $paged,
						'meta_key'               => 'start_date',
						'orderby'                => 'meta_value',
						'order'                  => 'ASC',
						'meta_query'             => array(
							array(
								'key'     => 'start_date',
								'value'   => $today,
								'compare' => '>=',
								'type'    => 'DATE',
							),
						),
						// Mejorar rendimiento con estos parámetros.
						'update_post_meta_cache' => true,
						'update_post_term_cache' => true,
					)
				);

				// Precargar todos los metadatos para los eventos próximos.
				if ( $upcoming->have_posts() ) {
					$post_ids = wp_list_pluck( $upcoming->posts, 'ID' );
					self::preload_meta_fields( $post_ids, $meta_fields );
				}

				// Obtener eventos pasados con todos los metadatos.
				$past = new WP_Query(
					array(
						'post_type'              => 'event',
						'posts_per_page'         => 10,
						'meta_key'               => 'start_date',
						'orderby'                => 'meta_value',
						'order'                  => 'DESC',
						'meta_query'             => array(
							array(
								'key'     => 'start_date',
								'value'   => $today,
								'compare' => '<',
								'type'    => 'DATE',
							),
						),
						// Mejorar rendimiento con estos parámetros.
						'update_post_meta_cache' => true,
						'update_post_term_cache' => true,
					)
				);

				// Precargar todos los metadatos para los eventos pasados.
				if ( $past->have_posts() ) {
					$past_post_ids = wp_list_pluck( $past->posts, 'ID' );
					self::preload_meta_fields( $past_post_ids, $meta_fields );
				}

				// Añadir variables a la vista.
				$view->with( 'upcoming', $upcoming );
				$view->with( 'past', $past );
				$view->with( 'meta_fields', $meta_fields );
			}
		);
	}

	/**
	 * Precarga metadatos para un conjunto de posts para mejorar el rendimiento.
	 *
	 * @param array $post_ids IDs de los posts para precargar metadatos.
	 * @param array $meta_keys Lista de claves de metadatos a precargar.
	 * @return void
	 */
	private static function preload_meta_fields( array $post_ids, array $meta_keys ): void {
		if ( empty( $post_ids ) || empty( $meta_keys ) ) {
			return;
		}

		// Obtener todos los metadatos de los posts en una sola consulta SQL.
		global $wpdb;

		$query = $wpdb->prepare(
			"SELECT post_id, meta_key, meta_value 
			FROM $wpdb->postmeta 
			WHERE post_id IN (" . implode( ',', array_fill( 0, count( $post_ids ), '%d' ) ) . ") 
			AND meta_key IN (" . implode( ',', array_fill( 0, count( $meta_keys ), '%s' ) ) . ")",
			array_merge( $post_ids, $meta_keys )
		);

		$results = $wpdb->get_results( $query );

		// Organizar los resultados en el caché.
		$cache = array();
		foreach ( $results as $row ) {
			$cache[ $row->post_id ][ $row->meta_key ][] = $row->meta_value;
		}

		// Actualizar el caché de metadatos de WordPress.
		foreach ( $cache as $post_id => $meta_data ) {
			foreach ( $meta_data as $meta_key => $meta_values ) {
				wp_cache_set( $post_id . '_' . $meta_key, $meta_values, 'post_meta' );
			}
		}
	}
}
