<?php
/**
 * TagController.php
 *
 * This file is part of the Maneras theme.
 *
 * @package ManerasTheme\Controllers
 * @category Controllers
 * @version 1.0
 * @author Javier Sierra <agradecido@manerasdevivir.com>
 */

namespace ManerasTheme\Controllers;

use WP_Query;
use WP_Term;

class TagController {

	/**
	 * Retrieve the tag object and its posts by slug.
	 *
	 * @param string $slug Tag slug.
	 * @return array{tag:WP_Term, posts:WP_Query}
	 */
	public static function getArchive( string $slug ): array {
		$tag   = get_term_by( 'slug', $slug, 'post_tag' );
		$posts = self::getPostsByTag( $slug );
		return array(
			'tag'   => $tag,
			'posts' => $posts,
		);
	}

	/**
	 * Retrieve all post tags, including those with no posts.
	 *
	 * @return WP_Term[] Array of tag term objects.
	 */
	public static function getTags(): array {
		$tags = get_terms(
			array(
				'taxonomy'   => 'post_tag',
				'hide_empty' => false,
			)
		);

		// Filter out tags with no posts.
		$tags = array_filter(
			$tags,
			function ( $tag ) {
				return ! empty( $tag->count );
			}
		);

		// Sort tags by posts count in descending order.
		usort(
			$tags,
			function ( $a, $b ) {
				return $b->count - $a->count;
			}
		);
		return $tags;
	}

	/**
	 * Retrieve posts associated with a specific tag slug.
	 * Supports both standard posts and custom 'article' post type.
	 * Adds pagination support.
	 *
	 * @param string $slug Tag slug.
	 * @return WP_Query Query object containing the posts.
	 */
	public static function getPostsByTag( string $slug ): WP_Query {
		$paged = get_query_var( 'paged' ) ? absint( get_query_var( 'paged' ) ) : 1;
		$args  = array(
			'post_type'      => array( 'post', 'article' ),
			'posts_per_page' => 10,
			'paged'          => $paged,
			'tax_query'      => array(
				array(
					'taxonomy' => 'post_tag',
					'field'    => 'slug',
					'terms'    => $slug,
				),
			),
		);

		return new WP_Query( $args );
	}
}
