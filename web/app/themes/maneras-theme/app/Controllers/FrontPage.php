<?php

namespace ManerasTheme\Controllers;

use Timber\Timber;

class FrontPage extends Controller {

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
				'meta_key'       => '_is_featured',
				'meta_value'     => '1',
			)
		);

		return Timber::get_posts( $featured_query );
	}

	/**
	 * Recent posts.
	 *
	 * @param int $count Number of recent posts to fetch.
	 * @return array
	 */
	public function recent( $count = 5 ) {
		$recent_query = new \WP_Query(
			array(
				'posts_per_page' => $count,
			)
		);

		return Timber::get_posts( $recent_query );
	}
}
