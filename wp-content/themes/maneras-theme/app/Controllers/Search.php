<?php

namespace ManerasTheme\Controllers;

use Timber\Timber;
use ManerasTheme\Traits\WithPagination;

class Search extends Controller {
	use WithPagination;

	/**
	 * Constructor - inicializa la consulta principal para la paginación
	 */
	public function __construct() {
		global $wp_query;
		$this->last_query = $wp_query;
	}

	/**
	 * Search query.
	 *
	 * @return string
	 */
	public function query() {
		return get_search_query();
	}

	/**
	 * Search results.
	 *
	 * @return array
	 */
	public function results() {
		global $wp_query;
		return Timber::get_posts( $wp_query );
	}

	/**
	 * Search result count.
	 *
	 * @return int
	 */
	public function count() {
		global $wp_query;
		return $wp_query->found_posts;
	}

	/**
	 * Pagination data.
	 *
	 * @return array
	 */
	public function pagination() {
		global $wp_query;
		return $this->get_pagination_data( $wp_query );
	}
}
