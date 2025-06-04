<?php
/**
 * Maneras Theme
 * Breadcrumbs Class
 * Generates breadcrumb navigation and JSON-LD structured data for the current page context
 *
 * @package ManerasTheme
 * @since 1.0.0
 * @version 1.0.0
 */

namespace ManerasTheme;

use Timber\Timber;
use Timber\Post as TimberPost;
use Timber\Term as TimberTerm;


/**
 * Class Breadcrumbs
 *
 * Generates breadcrumb navigation and JSON-LD structured data for the current page context.
 */
class Breadcrumbs {
	/**
	 * Holds the breadcrumb items.
	 *
	 * @var array
	 */
	private $items = array();

	/**
	 * Breadcrumbs constructor.
	 *
	 * Initializes the breadcrumbs by generating the items based on the current context.
	 */
	public function __construct() {
		$this->generate_breadcrumbs();
	}

	/**
	 * Generates the breadcrumb items.
	 */
	private function generate_breadcrumbs() {
		// Add Home.
		$this->items[] = array(
			'name' => __( 'Home', 'maneras-theme' ),
			'url'  => get_home_url(),
		);

		if ( is_singular() ) {
			$post = Timber::get_post();
			if ( $post ) {
				$this->add_singular_ancestors( $post );
				$this->items[] = array(
					'name' => $post->title(),
					'url'  => $post->link(), // Current item, so no URL needed for JSON-LD, but good for visual.
				);
			}
		} elseif ( is_category() || is_tag() || is_tax() ) {
			$term = Timber::get_term();
			if ( $term ) {
				$this->add_term_ancestors( $term );
				$this->items[] = array(
					'name' => $term->name(),
					'url'  => $term->link(), // Current item.
				);
			}
		} elseif ( is_archive() ) {
			// For general archives like date-based or post type archives.
			$this->items[] = array(
				'name' => get_the_archive_title(),
				// No URL for the current archive page in this simple case.
			);
		} elseif ( is_search() ) {
			$this->items[] = array(
				// Using a static name for search results.
				'name' => __( 'Search Results', 'maneras-theme' ),
			);
		} elseif ( is_404() ) {
			$this->items[] = array(
				'name' => __( 'Page Not Found', 'maneras-theme' ),
			);
		}
	}

	/**
	 * Adds ancestors for singular post types (posts, pages).
	 *
	 * @param TimberPost $post The current post.
	 */
	private function add_singular_ancestors( TimberPost $post ) {
		$ancestors = $post->ancestors();
		if ( $ancestors ) {
			foreach ( array_reverse( $ancestors ) as $ancestor_post ) {
				$this->items[] = array(
					'name' => $ancestor_post->title(),
					'url'  => $ancestor_post->link(),
				);
			}
		}
		// Potentially add category for posts.
		if ( 'post' === $post->post_type ) {
			$categories = $post->terms( 'category' );
			if ( ! empty( $categories ) ) {
				// Add the first category.
				$category = new TimberTerm( $categories[0] );
				$this->add_term_ancestors( $category ); // Add category ancestors if any.
				$this->items[] = array(
					'name' => $category->name(),
					'url'  => $category->link(),
				);
			}
		}
	}

	/**
	 * Adds ancestors for term archives (categories, tags).
	 *
	 * @param TimberTerm $term The current term.
	 */
	private function add_term_ancestors( TimberTerm $term ) {
		$ancestors = $term->ancestors();
		if ( $ancestors ) {
			foreach ( array_reverse( $ancestors ) as $ancestor_term ) {
				$this->items[] = array(
					'name' => $ancestor_term->name(),
					'url'  => $ancestor_term->link(),
				);
			}
		}
	}

	/**
	 * Returns the breadcrumb items.
	 *
	 * @return array
	 */
	public function get_items() {
		return $this->items;
	}

	/**
	 * Generates the JSON-LD script for breadcrumbs.
	 *
	 * @return string
	 */
	public function get_json_ld() {
		if ( empty( $this->items ) ) {
			return '';
		}

		$schema_items = array();
		foreach ( $this->items as $key => $item ) {
			$schema_items[] = array(
				'@type'    => 'ListItem',
				'position' => $key + 1,
				'name'     => $item['name'],
				'item'     => $item['url'] ?? '', // URL is optional for the last item.
			);
		}

		// The last item should not have an 'item' property if it's the current page
		// but for simplicity and because many examples include it, we'll leave it.
		// Or, remove 'item' if $item['url'] is empty or points to the current page.
		if ( ! empty( $schema_items ) ) {
			$last_item_key = count( $schema_items ) - 1;
			// If the last item's URL is the same as the current page URL or empty,
			// it's common practice to omit the 'item' property for it.
			// However, for this initial implementation, we'll keep it simple.
			// A more robust solution would compare with the current page URL.
			// For now, if the URL was explicitly set to empty or not set for the last item.
			$post = Timber::get_post();
			if ( empty( $this->items[ $last_item_key ]['url'] ) || ( $post && $this->items[ $last_item_key ]['url'] === $post->link() ) ) {
				unset( $schema_items[ $last_item_key ]['item'] );
			}
		}

		$schema = array(
			'@context'        => 'https://schema.org',
			'@type'           => 'BreadcrumbList',
			'itemListElement' => $schema_items,
		);

		return '<script type="application/ld+json">' . wp_json_encode( $schema, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT ) . '</script>';
	}
}
