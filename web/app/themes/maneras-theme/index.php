<?php
/**
 * Front-page template
 *
 * PHP version 8.3
 *
 * @category Theme
 * @package  ManerasTheme
 * @author   Javier Sierra <agradecido@manerasdevivir.com>
 * @license  https://opensource.org/licenses/MIT MIT License
 * @link     https://manerasdevivir.com
 */

namespace ManerasTheme;

if ( file_exists( __DIR__ . '/vendor/autoload.php' ) ) {
	require_once __DIR__ . '/vendor/autoload.php';
}

$posts = get_posts(
	array(
		'post_type'      => 'article',
		'posts_per_page' => 10,
		'orderby'        => 'date',
		'order'          => 'DESC',
	)
);

render( 'index', compact( 'posts' ) );
