<?php

namespace ManerasTheme\Controllers;

use ManerasTheme\Traits\WithPagination;

/**
 * Archive controller.
 *
 * This class handles the logic for displaying archive pages in the theme.
 * It extends the base Controller class and uses the WithPagination trait
 * to manage pagination data.
 */
class Archive extends Controller {
	use WithPagination;

	/**
	 * Posts in the archive.
	 *
	 * @var array
	 */
	public $posts;

	/**
	 * Pagination data for the archive.
	 *
	 * @var array
	 */
	public $pagination;

	/**
	 * Constructor - inicializa la consulta principal para la paginación
	 */
	public function __construct() {
		global $wp_query;
		$this->last_query = $wp_query;
		$this->posts      = $wp_query->posts; // Replaced Timber::get_posts
		$this->pagination = $this->get_pagination_data( $wp_query );
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
		return $wp_query->posts; // Replaced Timber::get_posts
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
