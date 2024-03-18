<?php

/**
 * Theme filters.
 */

namespace App;

/**
 * Add "… Continued" to the excerpt.
 *
 * @return string
 */

use App\Constants;

add_filter('excerpt_more', function () {
    return sprintf(' &hellip; <a href="%s">%s</a>', get_permalink(), __('Continued', Constants::TEXTDOMAIN));
});

add_filter('post_link', function ($post_link, $post) {
    if ('post' == get_post_type($post)) {
        $id_noticia = get_post_meta($post->ID, 'id_noticia', true);
        if ($id_noticia) {
            return home_url(user_trailingslashit("noticias/$id_noticia/" . $post->post_name));
        }
    }
    return $post_link;
}, 10, 2);

add_filter('use_block_editor_for_post_type', function ($enabled, $post_type) {
    if ($post_type === 'post') {
        return false;
    }
    return $enabled;
}, 10, 2);

add_action('init', function () {
    global $wp_rewrite;
    $wp_rewrite->date_structure = 'archivo/%year%/%monthnum%/%day%';
});

