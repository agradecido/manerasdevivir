<?php
namespace ManerasTheme\Controllers;

use WP_Query;
use WP_Term;
use ManerasTheme\Controllers\TagController;

class TagArchiveController {

	/**
	 * Retrieve the tag object and its posts by slug.
	 *
	 * @param string $slug Tag slug.
	 * @return array{tag:WP_Term, posts:WP_Query}
	 */
	public static function getArchive( string $slug ): array {
		$tag   = get_term_by( 'slug', $slug, 'post_tag' );
		$posts = TagController::getPostsByTag( $slug );
		return array(
			'tag'   => $tag,
			'posts' => $posts,
		);
	}
}
