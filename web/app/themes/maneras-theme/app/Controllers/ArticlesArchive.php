<?php
/**
 * ArticlesArchive Controller
 * Archivo de noticias (CPT article) para Manerasdevivir.com
 *
 * @package ManerasTheme\Controllers
 */

namespace ManerasTheme\Controllers;

use Timber\Timber;
use ManerasTheme\Traits\WithPagination;

/**
 * Archive controller for articles (CPT article).
 */
class ArticlesArchive extends Controller {
	use WithPagination;

	/**
	 * Constructor - initializes the main query for pagination only for CPT article.
	 */
	public function __construct() {
		global $wp_query;

		// If we are already on an article archive, use the main query.
		if ( is_post_type_archive( 'article' ) ) {
			$this->last_query = $wp_query;
		} else {
			// If not, create a specific query for articles.
			$args = array(
				'post_type'      => 'article',
				'posts_per_page' => 10,
				'post_status'    => 'publish',
				'paged'          => get_query_var( 'paged' ) ? get_query_var( 'paged' ) : 1,
			);
			$this->last_query = new \WP_Query( $args );
		}

		// Debug: guardar recuento de posts en propiedad
		$this->data['debug'] = array(
			'post_count'    => $this->last_query->post_count,
			'found_posts'   => $this->last_query->found_posts,
			'max_num_pages' => $this->last_query->max_num_pages,
			'is_archive'    => is_archive(),
			'is_post_type_archive' => is_post_type_archive( 'article' ),
		);
	}

	/**
	 * Archive title.
	 *
	 * @return string
	 */
	public function title() {
		return 'Archivo de noticias';
	}

	/**
	 * Archive description.
	 *
	 * @return string
	 */
	public function description() {
		return 'Todas las noticias publicadas en Manerasdevivir.com';
	}

	/**
	 * Posts in the archive.
	 *
	 * @return array
	 */
	public function posts() {
		// Debug: imprimir información en el log
		error_log('ArticlesArchive::posts() - post_count: ' . $this->last_query->post_count);
		
		// Usar get_posts directamente para asegurarnos de obtener los posts
		$posts = $this->last_query->get_posts();
		
		// Convertir a objetos Timber\Post
		return array_map(function($post) {
			return new \Timber\Post($post);
		}, $posts);
	}

	/**
	 * Pagination data for the archive.
	 *
	 * @return array
	 */
	public function pagination() {
		return $this->get_pagination_data( $this->last_query );
	}
	
	/**
	 * Debug data.
	 *
	 * @return array
	 */
	public function debug() {
		return $this->data['debug'];
	}
}
