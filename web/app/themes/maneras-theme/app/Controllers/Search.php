<?php

namespace ManerasTheme\Controllers;

use Timber\Timber;

class Search extends Controller {

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

		return array(
			'total'     => $wp_query->max_num_pages,
			'current'   => max( 1, get_query_var( 'paged' ) ),
			'prev_link' => get_previous_posts_link(),
			'next_link' => get_next_posts_link(),
		);
	}
}
