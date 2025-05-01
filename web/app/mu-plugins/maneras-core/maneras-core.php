<?php
/**
 * Maneras Core Functionality
 * PHP Version 8.3
 *
 * Plugin Name:       Maneras Core
 * Description:       Custom post types and functionality independent from the theme.
 * Version:           1.0.0
 * Author:            Javier Sierra <agradecido@manerasdevivir.com>
 * License:           MIT
 * Text Domain:       maneras-core
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$autoload = __DIR__ . '/vendor/autoload.php';

if ( ! file_exists( $autoload ) ) {
	wp_die(
		esc_html__(
			'El plugin Maneras Core necesita que ejecutes "composer install".',
			'maneras-core'
		),
		esc_html__( 'Autoload no encontrado', 'maneras-core' )
	);
}

require_once $autoload;

use ManerasCore\Plugin;

add_action(
	'plugins_loaded',
	static function (): void {
		new Plugin();
	}
);
