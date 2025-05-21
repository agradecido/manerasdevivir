<?php

namespace ManerasTheme\Controllers;

use Timber\Timber;

class Archive extends Controller {

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

		return array(
			'total'     => $wp_query->max_num_pages,
			'current'   => max( 1, get_query_var( 'paged' ) ),
			'prev_link' => get_previous_posts_link(),
			'next_link' => get_next_posts_link(),
		);
	}
}
