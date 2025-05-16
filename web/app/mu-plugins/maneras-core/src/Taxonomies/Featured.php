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
	}

	/**
	 * Register the taxonomy.
	 */
	public function register_taxonomy(): void {
		register_taxonomy(
			'featured',
			array( 'article', 'report', 'event', 'post', 'interview' ),
			array(
				'label'        => 'Destacado',
				'public'       => false,
				'show_ui'      => true,
				'hierarchical' => false,
			)
		);
	}
}
