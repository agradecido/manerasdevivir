<?php

namespace ManerasTheme\Controllers;

use Timber\Timber;

class NotFound extends Controller {

	/**
	 * Get recent posts to display on 404 page.
	 *
	 * @param int $count Number of posts to show.
	 * @return array
	 */
	public function recent_posts( $count = 5 ) {
		$recent_query = new \WP_Query(
			array(
				'posts_per_page' => $count,
				'post_status'    => 'publish',
			)
		);

		return Timber::get_posts( $recent_query );
	}
}
