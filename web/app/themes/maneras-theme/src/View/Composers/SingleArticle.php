<?php

namespace ManerasTheme\View\Composers;

use Illuminate\View\Factory;
use WP_Post;

/**
 * Registers view‑composer callbacks for Jenssegers Blade.
 */
class SingleArticle {

	public static function register( Factory $blade ): void {
		// ← Factory
		$blade->composer(
			'single-article',
			function ( $view ) {
				/** @var WP_Post $post */
				$post = get_post();

				$view->with(
					array(
						'title'   => get_the_title( $post ),
						'author'  => get_the_author_meta( 'display_name', $post->post_author ),
						'isoDate' => get_post_time( 'c', true, $post ),
						'pubDate' => get_the_date( 'd.m.Y', $post ),
						'thumb'   => has_post_thumbnail( $post )
									? get_the_post_thumbnail( $post, 'large', array( 'class' => 'w-full h-auto' ) )
									: '',
						'content' => apply_filters( 'the_content', $post->post_content ),
						'tags'    => get_the_tags( $post ),
					)
				);
			}
		);
	}
}
