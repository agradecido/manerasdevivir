<?php
// phpcs:ignoreFile
namespace ManerasCore;

use ManerasCore\PostTypes\Event;
use ManerasCore\PostTypes\Article;

class Plugin {

	/**
	 * Plugin constructor.
	 */
	public function __construct() {
		// 1) Registrar CPTs
		$this->registerPostTypes();

		// 2) Desactivar el editor de bloques en 'event'
		add_filter(
			'use_block_editor_for_post_type',
			array( $this, 'disableBlockEditor' ),
			10,
			2
		);

		// 3) Permalink: noticias/{ID}/{slug}/
		// add_filter(
		// 	'post_type_link',
		// 	array( $this, 'articlePermalink' ),
		// 	10,
		// 	2
		// );

		// 4) Añadir regla de rewrite para ese permalink
		// add_action(
		// 	'init',
		// 	array( $this, 'addArticleRewrite' )
		// );
	}

	/**
	 * Register custom post types.
	 */
	public function registerPostTypes(): void {
		new Event();

		new Article();
		
		/*
		new Article(
			array(
				'rewrite'     => array(
					'slug'       => 'noticias/%id_noticia%',
					'with_front' => false,
				),
				'has_archive' => 'noticias',
			)
		);
		*/

		add_action(
			'after_setup_theme',
			function () {
				add_theme_support( 'post-thumbnails' );
			}
		);

		add_action(
			'init',
			function () {
				// 1. Definimos una nueva columna "thumbnail" en el listado de article.
				add_filter(
					'manage_article_posts_columns',
					function ( $columns ) {
						// Añadimos la columna "thumbnail" después de la columna "cb" (checkbox).
						$new = array();
						foreach ( $columns as $key => $label ) {
							$new[ $key ] = $label;
							if ( 'cb' === $key ) {
								$new['thumbnail'] = __( 'Featured', 'maneras-core' );
							}
						}
						return $new;
					}
				);

				// 2. Poblamos la columna con la miniatura (50×50)
				add_action(
					'manage_article_posts_custom_column',
					function ( $column, $post_id ) {
						if ( 'thumbnail' === $column ) {
							if ( has_post_thumbnail( $post_id ) ) {
								echo get_the_post_thumbnail( $post_id, array( 50, 50 ) );
							} else {
								echo '—';
							}
						}
					},
					10,
					2
				);

				add_action(
					'admin_head',
					function () {
						echo '<style>
                        .wp-list-table .column-thumbnail { 
                            width: 100px; 
                        }
                        .wp-list-table .column-thumbnail img {
                            width: 100px;
                            height: auto;
                        }
                    </style>';
					}
				);
				// Asegurarnos de que nuestro CPT soporta thumbnail en el editor clásico y bloques.
				add_post_type_support( 'article', 'thumbnail' );
			},
			20
		);
	}

	/**
	 * Disable Gutenberg for events.
	 *
	 * @param bool   $use_block_editor Whether to use the block editor.
	 * @param string $post_type The post type.
	 *
	 * @return bool
	 */
	public function disableBlockEditor( bool $use_block_editor, string $post_type ): bool {
		if ( $post_type === 'event' ) {
			return false;
		}
		return $use_block_editor;
	}

	/**
	 * Build /noticias/{ID}/{slug}/ permalinks for articles.
	 *
	 * @param string   $url
	 * @param \WP_Post $post
	 * @return string
	 */
	// public function articlePermalink( string $url, \WP_Post $post ): string {
	// 	if ( $post->post_type !== 'article' ) {
	// 		return $url;
	// 	}

	// 	return home_url(
	// 		sprintf( 'noticias/%d/%s/', $post->ID, $post->post_name )
	// 	);
	// }

	/**
	 * Add rewrite rule to capture /noticias/ID/slug/ URLs.
	 */
	// public function addArticleRewrite(): void {
	// 	add_rewrite_rule(
	// 		'^noticias/([0-9]+)/([^/]+)/?$',
	// 		'index.php?post_type=article&p=$matches[1]',
	// 		'top'
	// 	);
	// }
}

