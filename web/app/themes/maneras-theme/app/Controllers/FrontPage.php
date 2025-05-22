<?php

namespace ManerasTheme\Controllers;

use Timber\Timber;

class FrontPage extends Controller {

	/**
	 * Constructor - initialize data that should be available
	 */
	public function __construct() {
		$this->data['featured'] = $this->featured();
		$this->data['recent']   = $this->recent();
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
				'post_type'      => 'article',
			)
		);

		return Timber::get_posts( $recent_query );
	}
}
