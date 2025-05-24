<?php

namespace ManerasTheme\Traits;

use Timber\Timber;
use WP_Query;

/**
 * Trait para manejar la paginación de posts.
 * Provee métodos reutilizables para obtener posts paginados y datos de paginación.
 *
 * Cómo usar este trait:
 * 1. Agregue "use WithPagination;" a su clase
 * 2. Use el método get_paginated_posts() para obtener posts paginados
 * 3. Use el método get_pagination_data() para obtener datos de paginación
 * 
 * Este trait guarda automáticamente la última consulta para ser reutilizada
 * entre métodos, lo que permite separar la obtención de los posts y los datos
 * de paginación mientras se usa la misma consulta.
 */
trait WithPagination {

	/**
	 * Obtiene posts paginados según los parámetros proporcionados.
	 *
	 * @param array $args        Argumentos para WP_Query.
	 * @param int   $per_page    Número de posts por página.
	 * @param int   $paged       Número de página actual.
	 * @return array             Array de posts.
	 */
	protected function get_paginated_posts( array $args = array(), int $per_page = 5, ?int $paged = null ) {
		// Si no se proporciona un número de página, intentamos obtenerlo de la consulta
		if ( null === $paged ) {
			$paged = get_query_var( 'paged' ) ? get_query_var( 'paged' ) : 1;
		}

		// Valores por defecto para la consulta
		$defaults = [
			'posts_per_page' => $per_page,
			'paged'          => $paged,
		];

		// Combinamos los argumentos proporcionados con los valores por defecto
		$query_args = array_merge( $defaults, $args );

		// Ejecutamos la consulta
		$query = new WP_Query( $query_args );

		// Guardamos la consulta para usarla en otras funciones (por ejemplo, para obtener la paginación)
		$this->last_query = $query;

		// Devolvemos los posts
		return Timber::get_posts( $query );
	}

	/**
	 * Obtiene datos de paginación basados en la última consulta ejecutada.
	 *
	 * @param WP_Query|null $query Objeto WP_Query opcional. Si no se proporciona, se usa la última consulta.
	 * @return array              Datos de paginación.
	 */
	protected function get_pagination_data( ?WP_Query $query = null ) {
		// Si no se proporciona una consulta, usamos la última ejecutada
		if ( null === $query && isset( $this->last_query ) ) {
			$query = $this->last_query;
		}

		// Si no hay consulta, devolvemos un array vacío
		if ( null === $query ) {
			return array();
		}

		// Obtenemos el número de página actual
		$paged = max( 1, $query->get( 'paged' ) );

		// Devolvemos los datos de paginación
		return array(
			'total'     => $query->max_num_pages,
			'current'   => $paged,
			'prev_link' => $paged > 1 ? get_previous_posts_page_link() : '',
			'next_link' => $paged < $query->max_num_pages ? get_next_posts_page_link() : '',
		);
	}
}
