<?php

namespace ManerasTheme\View\Composers;

use Illuminate\View\Factory;
use WP_Query;

/**
 * Registers view‑composer callbacks for the Article archive page.
 */
class ArchiveArticle {
	/**
	 * Register the view composer for the article archive page.
	 *
	 * @param Factory $blade The Blade factory instance.
	 */
	public static function register( Factory $blade ): void {
		$blade->composer(
			'pages.articles.archive-article',
			function ( $view ) {
				$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;

				// Articles query with pagination.
				$articles = new WP_Query(
					array(
						'post_type'      => 'article',
						'posts_per_page' => 12,
						'paged'          => $paged,
						'orderby'        => 'date',
						'order'          => 'DESC',
					)
				);

				// Get categories for filtering (if needed).
				$categories = get_terms(
					array(
						'taxonomy'   => 'category',
						'hide_empty' => true,
					)
				);

				// Create an IntlDateFormatter for properly formatted dates.
				$formatter = new \IntlDateFormatter(
					'es_ES',
					\IntlDateFormatter::LONG,
					\IntlDateFormatter::NONE,
					null,
					null,
					'd \'de\' MMMM \'de\' yyyy'
				);

				$view->with( compact( 'articles', 'categories', 'formatter' ) );
			}
		);
	}
}
