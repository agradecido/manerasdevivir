<?php

namespace ManerasTheme\Traits;

use Timber\Timber;
use WP_Query;

/**
 * Trait para manejar la paginación de posts.
 * Provee métodos reutilizables para obtener posts paginados y datos de paginación.
 *
 * Cómo usar este trait:
 * 1. Agregue "use WithPagination;" a su clase.
 * 2. Use el método get_paginated_posts() para obtener posts paginados.
 * 3. Use el método get_pagination_data() para obtener datos de paginación.
 *
 * Este trait guarda automáticamente la última consulta para ser reutilizada
 * entre métodos, lo que permite separar la obtención de los posts y los datos
 * de paginación mientras se usa la misma consulta.
 */
trait WithPagination {

	/**
	 * Almacena la última consulta WP_Query ejecutada.
	 *
	 * @var WP_Query|null
	 */
	protected $last_query = null;

	/**
	 * Obtiene posts paginados según los parámetros proporcionados.
	 *
	 * @param array $args        Argumentos para WP_Query.
	 * @param int   $per_page    Número de posts por página.
	 * @param int   $paged       Número de página actual.
	 * @return array             Array de posts.
	 */
	protected function get_paginated_posts( array $args = array(), int $per_page = 5, ?int $paged = null ) {
		// Si no se proporciona un número de página, intentamos obtenerlo de la URL o query vars.
		if ( null === $paged ) {
			// Primero, intenta obtener la página de la URL directamente.
			$request_uri = isset( $_SERVER['REQUEST_URI'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REQUEST_URI'] ) ) : '';
			if ( preg_match( '/\/page\/(\d+)\/?/', $request_uri, $matches ) ) {
				$paged = intval( $matches[1] );
			} else {
				// Último recurso: obtén la página de las query vars.
				if ( is_front_page() ) {
					$paged = get_query_var( 'page' ) ? get_query_var( 'page' ) : 1;
				} else {
					$paged = get_query_var( 'paged' ) ? get_query_var( 'paged' ) : 1;
				}
			}
		}

		// Valores por defecto para la consulta.
		$defaults = array(
			'posts_per_page' => $per_page,
			'paged'          => $paged,
		);

		// Si estamos en la página principal (is_front_page) y tenemos una página
		// estática configurada como página de inicio, necesitamos usar 'page' en
		// lugar de 'paged' para la paginación.
		if ( is_front_page() && ! is_home() ) {
			$defaults['page'] = $paged;
		}

		// Combinamos los argumentos proporcionados con los valores por defecto.
		$query_args = array_merge( $defaults, $args );

		// Ejecutamos la consulta.
		$query = new WP_Query( $query_args );

		// Guardamos la consulta para usarla en otras funciones.
		$this->last_query = $query;

		// Devolvemos los posts.
		return Timber::get_posts( $query );
	}

	/**
	 * Obtiene datos de paginación basados en la última consulta ejecutada.
	 *
	 * @param WP_Query|null $query Objeto WP_Query opcional. Si no se proporciona, se usa la última consulta.
	 * @return array              Datos de paginación.
	 */
	protected function get_pagination_data( ?WP_Query $query = null ) {
		// Si no se proporciona una consulta, usamos la última ejecutada.
		if ( null === $query && isset( $this->last_query ) ) {
			$query = $this->last_query;
		}

		// Si no hay consulta, devolvemos un array vacío.
		if ( null === $query ) {
			return array();
		}

		// Obtenemos el número de página actual directamente de la URL si estamos en la portada.
		$paged = 1;

		if ( is_front_page() ) {
			// Intentar obtener la página directamente de la URL primero (más fiable).
			if ( isset( $_SERVER['REQUEST_URI'] ) ) {
				$request_uri = sanitize_text_field( wp_unslash( $_SERVER['REQUEST_URI'] ) );
				if ( preg_match( '/\/page\/(\d+)\/?/', $request_uri, $matches ) ) {
					$paged = (int) $matches[1];
				} else {
					// Si no hay coincidencia en la URL, usar el valor de la consulta.
					$paged = max( 1, $query->get( 'page' ) );
				}
			} else {
				$paged = max( 1, $query->get( 'page' ) );
			}
		} else {
			$paged = max( 1, $query->get( 'paged' ) );
		}

		// Devolvemos los datos de paginación.
		return array(
			'total'     => $query->max_num_pages,
			'current'   => $paged,
			'prev_link' => $paged > 1 ? get_previous_posts_page_link() : '',
			'next_link' => $paged < $query->max_num_pages ? get_next_posts_page_link() : '',
		);
	}
}
