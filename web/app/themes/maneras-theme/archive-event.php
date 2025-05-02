<?php
/**
 *
 * Template for displaying the “event” post type archive.
 * This file bridges the CPT “event” archive to the theme’s rendering system.
 *
 * @package ManerasTheme
 * @category Theme
 * @version 1.0
 * @author Javier Sierra <agradecido@manerasdevivir.com>
 */

namespace ManerasTheme;

if ( file_exists( __DIR__ . '/vendor/autoload.php' ) ) {
	require_once __DIR__ . '/vendor/autoload.php';
}

if ( function_exists( 'render' ) ) {
	render( 'pages/events/archive-event' );
}
