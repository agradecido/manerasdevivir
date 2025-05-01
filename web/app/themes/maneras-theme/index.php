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

require_once __DIR__ . '/vendor/autoload.php'; // Include Composer autoloader

// get_header();

$posts = get_posts(
    [
        'post_type'      => 'article',
        'posts_per_page' => 10,
        'orderby'        => 'date',
        'order'          => 'DESC',
    ]
);

render('index', compact('posts'));

// get_footer();
