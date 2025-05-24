<?php

namespace ManerasTheme\Controllers;

use Timber\Timber;
use ManerasTheme\Traits\WithPagination;

class Archive extends Controller {
	use WithPagination;

	/**
	 * Constructor - inicializa la consulta principal para la paginación
	 */
	public function __construct() {
		global $wp_query;
		$this->last_query = $wp_query;
	}

	/**
	 * Archive title.
	 *
	 * @return string
	 */
	public function title() {
		return get_the_archive_title();
	}

	/**
	 * Archive description.
	 *
	 * @return string
	 */
	public function description() {
		return get_the_archive_description();
	}

	/**
	 * Posts in the archive.
	 *
	 * @return array
	 */
	public function posts() {
		global $wp_query;
		return Timber::get_posts( $wp_query );
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
