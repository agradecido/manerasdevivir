<?php

/**
 * Provinces Taxonomy
 *
 * PHP Version 8.3
 *
 * Plugin Name:       Maneras Core Functionality
 * Description:       Event post type for Maneras Core.
 * Version:           1.0.0
 * Author:            Javier Sierra <agradecido@manerasdevivir.com>
 * License:           MIT
 * Text Domain:       maneras-core
 */

namespace ManerasCore\Taxonomies;

class Provinces {

	/**
	 * The fields to be sanitized.
	 *
	 * @var array
	 */
	private array $fields = array(
		'provincia_slug' => 'sanitize_title',
	);

	/**
	 * Provinces constructor.
	 */
	public function __construct() {
		// Register taxonomy at the proper time.
		add_action( 'init', array( $this, 'registerTaxonomy' ) );

		// Add query filter.
		add_action( 'pre_get_posts', array( $this, 'filterArchiveQuery' ) );
	}

	/**
	 * Register the taxonomy.
	 */
	public function registerTaxonomy(): void {
		register_taxonomy(
			'province',
			'event',
			array(
				'label'             => 'Provincias',
				'public'            => true,
				'hierarchical'      => false,
				'rewrite'           => array( 'slug' => 'provincia' ),
				'show_admin_column' => true,
				'show_in_rest'      => true,
				'labels'            => array(
					'name'          => 'Provincias',
					'singular_name' => 'Provincia',
					'search_items'  => 'Buscar provincias',
					'all_items'     => 'Todas las provincias',
					'edit_item'     => 'Editar provincia',
					'update_item'   => 'Actualizar provincia',
					'add_new_item'  => 'Añadir nueva provincia',
					'new_item_name' => 'Nombre de nueva provincia',
					'menu_name'     => 'Provincias',
				),
			)
		);
	}

	/**
	 * Filter the event archive query by province.
	 *
	 * @param \WP_Query $query The WP_Query instance (passed by reference).
	 * @return void
	 */
	public function filterArchiveQuery( \WP_Query $query ): void {
		if ( is_admin() || ! $query->is_main_query() || ! is_post_type_archive( 'event' ) ) {
			return;
		}

		if ( ! empty( $_GET['province'] ) ) {
			$query->set(
				'tax_query',
				array(
					array(
						'taxonomy' => 'province',
						'field'    => 'slug',
						'terms'    => sanitize_text_field( $_GET['province'] ),
					),
				)
			);
		}
	}
}
