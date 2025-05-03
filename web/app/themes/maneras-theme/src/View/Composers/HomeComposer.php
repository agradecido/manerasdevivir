<?php

namespace ManerasTheme\View\Composers;

use Illuminate\View\Factory;
use WP_Query;

/**
 * Registers view‑composer callbacks for the Home page.
 */
class HomeComposer {

	/**
	 * Register the view composer for the home page.
	 *
	 * @param Factory $blade The Blade factory instance.
	 */
	public static function register( Factory $blade ): void {
		$blade->composer(
			'pages.home',
			function ( $view ) {
				$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;

				$articles = new WP_Query(
					array(
						'post_type'      => 'article',
						'posts_per_page' => 10,
						'paged'          => $paged,
						'orderby'        => 'date',
						'order'          => 'DESC',
					)
				);

				$view->with( 'articles', $articles );
			}
		);
	}
}