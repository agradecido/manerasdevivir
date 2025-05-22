<?php
/**
 * Featured Taxonomy
 *
 * PHP Version 8.3
 *
 * @package ManerasCore
 *
 * Plugin Name:       Maneras Core Functionality
 * Description:       Featured taxonomy for Maneras Core.
 * Version:           1.0.0
 * Author:            Javier Sierra <agradecido@manerasdevivir.com>
 * License:           MIT
 * Text Domain:       maneras-core
 */

namespace ManerasCore\Taxonomies;

/**
 * Class Featured
 *
 * Registers and manages the Featured taxonomy.
 *
 * @package ManerasCore\Taxonomies
 */
class Featured {

	/**
	 * Featured constructor.
	 */
	public function __construct() {
		// Register taxonomy at the proper time.
		add_action( 'init', array( $this, 'register_taxonomy' ) );
        
        // Add default terms after theme setup
        add_action( 'init', array( $this, 'add_default_terms' ), 20 );
	}

	/**
	 * Register the taxonomy.
	 */
	public function register_taxonomy(): void {
		register_taxonomy(
			'featured',
			array( 'article', 'report', 'event', 'post', 'interview' ),
			array(
				'label'             => 'Destacado',
				'public'            => true,
				'show_ui'           => true,
				'show_admin_column' => true,
				'hierarchical'      => false,
				'show_in_rest'      => true,
				'labels'            => array(
					'name'              => 'Destacados',
					'singular_name'     => 'Destacado',
					'search_items'      => 'Buscar Destacados',
					'all_items'         => 'Todos los Destacados',
					'parent_item'       => 'Destacado Padre',
					'parent_item_colon' => 'Destacado Padre:',
					'edit_item'         => 'Editar Destacado',
					'update_item'       => 'Actualizar Destacado',
					'add_new_item'      => 'Añadir Nuevo Destacado',
					'new_item_name'     => 'Nuevo Nombre de Destacado',
					'menu_name'         => 'Destacados',
				),
			)
		);
	}

	/**
	 * Add default terms to the taxonomy.
	 */
	public function add_default_terms(): void {
		$default_terms = array(
			'home' => 'Destacado en Inicio',
			'sidebar' => 'Destacado en Barra Lateral',
			'newsletter' => 'Destacado en Newsletter',
		);

		foreach ( $default_terms as $slug => $name ) {
			if ( ! term_exists( $slug, 'featured' ) ) {
				wp_insert_term(
					$name,
					'featured',
					array(
						'slug' => $slug,
					)
				);
			}
		}
	}
}
