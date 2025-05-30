<?php
/**
 * Tags Taxonomy
 *
 * PHP Version 8.3
 *
 * @package ManerasCore
 *
 * Plugin Name:       Maneras Core Functionality
 * Description:       Tags taxonomy for Maneras Core.
 * Version:           1.0.0
 * Author:            Javier Sierra <agradecido@manerasdevivir.com>
 * License:           MIT
 * Text Domain:       maneras-core
 */

namespace ManerasCore\Taxonomies;

/**
 * Class Tags
 *
 * Registers and manages the Tags taxonomy.
 *
 * @package ManerasCore\Taxonomies
 */
class Tags {

	/**
	 * Tags constructor.
	 */
	public function __construct() {
		// Register taxonomy at the proper time.
		add_action( 'init', array( $this, 'register_taxonomy' ) );
	}

	/**
	 * Register the taxonomy.
	 */
	public function register_taxonomy(): void {
		register_taxonomy(
			'post_tag',
			array( 'article', 'report', 'event', 'post', 'interview' ),
			array(
				'label'             => __( 'Etiquetas', 'maneras-core' ),
				'public'            => true,
				'show_ui'           => true,
				'show_admin_column' => true,
				'hierarchical'      => false,
				'show_in_rest'      => true,
				'rewrite'           => array(
					'slug'         => 'etiqueta',
					'with_front'   => false,
					'hierarchical' => false,
				),
				'labels'            => array(
					'name'                       => __( 'Etiquetas', 'maneras-core' ),
					'singular_name'              => __( 'Etiqueta', 'maneras-core' ),
					'search_items'               => __( 'Buscar Etiquetas', 'maneras-core' ),
					'popular_items'              => __( 'Etiquetas Populares', 'maneras-core' ),
					'all_items'                  => __( 'Todas las Etiquetas', 'maneras-core' ),
					'parent_item'                => null,
					'parent_item_colon'          => null,
					'edit_item'                  => __( 'Editar Etiqueta', 'maneras-core' ),
					'update_item'                => __( 'Actualizar Etiqueta', 'maneras-core' ),
					'add_new_item'               => __( 'Añadir Nueva Etiqueta', 'maneras-core' ),
					'new_item_name'              => __( 'Nombre de Nueva Etiqueta', 'maneras-core' ),
					'separate_items_with_commas' => __( 'Separa las etiquetas con comas', 'maneras-core' ),
					'add_or_remove_items'        => __( 'Añadir o eliminar etiquetas', 'maneras-core' ),
					'choose_from_most_used'      => __( 'Elegir de las etiquetas más usadas', 'maneras-core' ),
					'not_found'                  => __( 'No se encontraron etiquetas', 'maneras-core' ),
					'menu_name'                  => __( 'Etiquetas', 'maneras-core' ),
					'view_item'                  => __( 'Ver Etiqueta', 'maneras-core' ),
					'back_to_items'              => __( 'Volver a etiquetas', 'maneras-core' ),
				),
			)
		);
	}
}
