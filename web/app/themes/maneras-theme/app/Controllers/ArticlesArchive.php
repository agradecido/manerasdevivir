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
			$args             = array(
				'post_type'      => 'article',
				'posts_per_page' => 10,
				'post_status'    => 'publish',
				'paged'          => get_query_var( 'paged' ) ? get_query_var( 'paged' ) : 1,
			);
			$this->last_query = new \WP_Query( $args );
		}

		// Debug: guardar información de diagnóstico
		global $wpdb;
		$db_posts_count = $wpdb->get_var("SELECT COUNT(*) FROM {$wpdb->posts} WHERE post_type = 'article' AND post_status = 'publish'");
		
		$this->data['debug'] = array(
			'post_count'           => $this->last_query->post_count,
			'found_posts'          => $this->last_query->found_posts,
			'max_num_pages'        => $this->last_query->max_num_pages,
			'is_archive'           => is_archive(),
			'is_post_type_archive' => is_post_type_archive( 'article' ),
			'db_posts_count'       => $db_posts_count,
			'registered_pt'        => post_type_exists('article') ? 'Sí' : 'No',
			'url_base'             => get_post_type_archive_link('article'),
		);
		
		// Pre-inicializar artículos de ejemplo para depuración
		$this->data['sample_articles'] = $this->get_sample_articles();
	}

	/**
	 * Get sample articles for testing
	 * 
	 * @return array
	 */
	private function get_sample_articles() {
		// Artículos de ejemplo para tener algo que mostrar en desarrollo
		return array(
			array(
				'ID'           => 1,
				'title'        => 'Artículo de ejemplo 1',
				'excerpt'      => 'Este es un artículo de ejemplo para depurar la funcionalidad del archivo.',
				'thumbnail'    => false,
				'link'         => '#',
				'post_date'    => date('Y-m-d'),
				'author_name'  => 'Admin',
			),
			array(
				'ID'           => 2,
				'title'        => 'Artículo de ejemplo 2',
				'excerpt'      => 'Este es otro artículo de ejemplo para depurar la funcionalidad del archivo.',
				'thumbnail'    => false,
				'link'         => '#',
				'post_date'    => date('Y-m-d', strtotime('-1 day')),
				'author_name'  => 'Editor',
			),
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
		// Try to get real posts
		global $wpdb;
		$posts = array();
		
		// Direct database query for articles
		$query = $wpdb->prepare(
			"SELECT ID, post_title, post_excerpt, post_date 
			FROM {$wpdb->posts} 
			WHERE post_type = %s 
			AND post_status = %s 
			ORDER BY post_date DESC 
			LIMIT %d",
			'article',
			'publish',
			10
		);
		
		$results = $wpdb->get_results($query);
		
		if (!empty($results)) {
			foreach ($results as $result) {
				$post = array(
					'ID'           => $result->ID,
					'title'        => $result->post_title,
					'excerpt'      => !empty($result->post_excerpt) ? $result->post_excerpt : 'Sin extracto',
					'post_date'    => $result->post_date,
					'link'         => get_permalink($result->ID),
					'thumbnail'    => has_post_thumbnail($result->ID) ? get_the_post_thumbnail_url($result->ID, 'medium') : false,
					'author_name'  => get_post_meta($result->ID, 'firma_sender', true),
				);
				$posts[] = $post;
			}
			
			$this->data['debug']['db_query_success'] = true;
			
			return $posts;
		} else {
			// Fall back to sample articles if no real posts
			$this->data['debug']['db_query_success'] = false;
			$this->data['debug']['fallback_to_samples'] = true;
			
			return $this->data['sample_articles'];
		}
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
	
	/**
	 * Sample articles.
	 *
	 * @return array
	 */
	public function sample_articles() {
		return $this->data['sample_articles'];
	}
}
