<?php

namespace ManerasTheme\Controllers;

use Timber\Timber;
use ManerasTheme\Traits\WithPagination;

class FrontPage extends Controller {
	use WithPagination;

	/**
	 * Posts por página para artículos recientes.
	 *
	 * @var int
	 */
	protected $posts_per_page = 5;

	/**
	 * Constructor - initialize data that should be available
	 */
	public function __construct() {
		$this->data['featured'] = $this->featured();
		$this->data['recent']   = $this->recent();
		$this->data['pagination'] = $this->pagination();
	}

	/**
	 * Featured posts.
	 *
	 * @param int $count Number of featured posts to fetch.
	 * @return array
	 */
	public function featured( $count = 3 ) {
		$featured_query = new \WP_Query(
			array(
				'posts_per_page' => $count,
				'post_type'      => 'article',
				'tax_query'      => array(
					array(
						'taxonomy' => 'featured',
						'field'    => 'slug',
						'terms'    => 'home',
					),
				),
			)
		);

		return Timber::get_posts( $featured_query );
	}

	/**
	 * Recent posts.
	 *
	 * @param int $posts_per_page Number of recent posts to fetch per page (optional).
	 * @return array
	 */
	public function recent( $posts_per_page = null ) {
		if ( null === $posts_per_page ) {
			$posts_per_page = $this->posts_per_page;
		}
		
		return $this->get_paginated_posts(
			array(
				'post_type' => 'article',
			),
			$posts_per_page
		);
	}
	
	/**
	 * Pagination data for recent posts.
	 *
	 * @return array
	 */
	public function pagination() {
		return $this->get_pagination_data();
	}
}
