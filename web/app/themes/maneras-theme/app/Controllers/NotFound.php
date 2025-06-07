<?php

namespace ManerasTheme\Controllers;

use Timber\Timber;
use ManerasTheme\Traits\WithBreadcrumbs;

class NotFound extends Controller {
	use WithBreadcrumbs;

	/**
	 * Constructor - setup breadcrumbs for 404 page
	 */
	public function __construct() {
		$this->setup_breadcrumbs();
	}

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
