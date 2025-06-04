<?php
/**
 * Plugin Name:   Disable Emojis
 * Description:   Disable emojis in WordPress.
 * Author:        Javier Sierra <agradecido@manerasdevivir.com>
 * Author URI:    https://manerasdevivir.com
 * Version:       1.0
 * License:       MIT
 * License URI:   https://opensource.org/licenses/MIT
 */

/**
 * Disable emojis in WordPress
 */
function desactivar_wp_emojicons() {
	remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
	remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
	remove_action( 'wp_print_styles', 'print_emoji_styles' );
	remove_action( 'admin_print_styles', 'print_emoji_styles' );

	remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
	remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
	remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );

	add_filter( 'tiny_mce_plugins', 'desactivar_emojicons_tinymce' );
	add_filter( 'emoji_svg_url', '__return_false' );
}
add_action( 'init', 'desactivar_wp_emojicons' );

/**
 * Disable the emoji script in TinyMCE
 *
 * @param array $plugins Lista de plugins de TinyMCE.
 * @return array Lista de plugins de TinyMCE sin el plugin de emojis.
 */
function desactivar_emojicons_tinymce( $plugins ) {
	if ( is_array( $plugins ) ) {
		return array_diff( $plugins, array( 'wpemoji' ) );
	}
	return array();
}
