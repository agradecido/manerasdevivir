<?php

namespace ManerasTheme\View\Composers;

use Illuminate\View\Factory;
use WP_Post;

/**
 * Registers view‑composer callbacks for Jenssegers Blade.
 */
class SingleArticle {

	/**
	 * Register the view composer for the article single page.
	 *
	 * @param Factory $blade The Blade factory instance.
	 */
	public static function register( Factory $blade ): void {
		$blade->composer(
			'pages.articles.single-article',
			function ( $view ) {
				/** @var WP_Post $post */
				$post = $view->article ?? get_post();

				if ( ! $post ) {
					return;
				}

				$view->with(
					array(
						'title'   => get_the_title( $post ),
						'author'  => get_post_meta( $post->ID, 'firma_sender', true ),
						'isoDate' => get_post_time( 'c', true, $post ),
						'pubDate' => get_the_date( 'd.m.Y', $post ),
						'thumb'   => has_post_thumbnail( $post )
									? get_the_post_thumbnail( $post, 'large', array( 'class' => 'w-full h-auto max-h-96 object-cover object-center' ) )
									: '',
						'content' => apply_filters( 'the_content', $post->post_content ),
						'tags'    => get_the_tags( $post ),
					)
				);
			}
		);
	}
}
