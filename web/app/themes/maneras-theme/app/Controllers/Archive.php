<?php

namespace ManerasTheme\Controllers;

use Timber\Timber;
use ManerasTheme\Traits\WithPagination;
use ManerasTheme\Traits\WithBreadcrumbs;
use ManerasTheme\Breadcrumbs;

/**
 * Archive controller.
 *
 * This class handles the logic for displaying archive pages in the theme.
 * It extends the base Controller class and uses the WithPagination trait
 * to manage pagination data.
 */
class Archive extends Controller {
	use WithPagination;
	use WithBreadcrumbs;

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
	 * Archive title.
	 *
	 * @var string
	 */
	public $title;

	/**
	 * Archive description.
	 *
	 * @var string
	 */
	public $description;


	/**
	 * Constructor - inicializa la consulta principal para la paginación
	 */
	public function __construct() {
		global $wp_query;
		$this->title       = $this->title();
		$this->description = $this->description();
		$this->last_query  = $wp_query;
		$this->posts       = Timber::get_posts( $wp_query );
		$this->pagination  = $this->get_pagination_data( $wp_query );
		$this->setup_breadcrumbs();
	}

	/**
	 * Archive title.
	 *
	 * @return string
	 */
	public function title() {
		if ( 'article' === get_post_type() && is_archive() && ! is_tax() ) {
			return __( 'Archivo de Noticias', 'maneras-theme' );
		}
		if ( 'event' === get_post_type() && is_archive() && ! is_tax() ) {
			return __( 'Agenda de conciertos', 'maneras-theme' );
		}		
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
