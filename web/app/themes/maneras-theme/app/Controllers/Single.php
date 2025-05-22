<?php

namespace ManerasTheme\Controllers;

use Timber\Timber;
use Timber\Post;

class Single extends Controller {

	/**
	 * The post object.
	 *
	 * @var \Timber\Post
	 */
	public $post;

	/**
	 * Constructor.
	 */
	public function __construct() {
		$this->post = Timber::get_post();
	}

	/**
	 * Get the post comments.
	 *
	 * @return array
	 */
	public function comments() {
		return array(
			'count' => get_comments_number( $this->post->ID ),
			'list'  => get_comments(
				array(
					'post_id' => $this->post->ID,
					'status'  => 'approve',
				)
			),
		);
	}

	/**
	 * Get related posts.
	 *
	 * @param int $count Number of related posts to fetch.
	 * @return array
	 */
	public function related( $count = 3 ) {
		// Get posts in the same category
		$categories = wp_get_post_categories( $this->post->ID );

		if ( empty( $categories ) ) {
			return array();
		}

		$related_query = new \WP_Query(
			array(
				'category__in'   => $categories,
				'post__not_in'   => array( $this->post->ID ),
				'posts_per_page' => $count,
			)
		);

		return Timber::get_posts( $related_query );
	}
}
