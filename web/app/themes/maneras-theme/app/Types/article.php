<?php
/**
 * Register the Article CPT
 */

use PostTypes\PostType;
use App\Constants;

$labels = array(
    'name' => _x('Noticias', 'Post type general name', Constants::TEXTDOMAIN),
    'singular_name' => _x('Noticia', 'Post type singular name', Constants::TEXTDOMAIN),
    'menu_name' => _x('Noticias', 'Admin Menu text', Constants::TEXTDOMAIN),
    'name_admin_bar' => _x('Noticia', 'Add New on Toolbar', Constants::TEXTDOMAIN),
    'add_new' => __('Add New', Constants::TEXTDOMAIN),
    'add_new_item' => __('Add New Noticia', Constants::TEXTDOMAIN),
    'new_item' => __('New Noticia', Constants::TEXTDOMAIN),
    'edit_item' => __('Edit Noticia', Constants::TEXTDOMAIN),
    'view_item' => __('View Noticia', Constants::TEXTDOMAIN),
    'all_items' => __('All Noticias', Constants::TEXTDOMAIN),
    'search_items' => __('Search Noticias', Constants::TEXTDOMAIN),
    'parent_item_colon' => __('Parent Articles:', Constants::TEXTDOMAIN),
    'not_found' => __('No noticias found.', Constants::TEXTDOMAIN),
    'not_found_in_trash' => __('No noticias found in Trash.', Constants::TEXTDOMAIN),
    'featured_image' => _x('Article Main Image', 'Overrides the “Featured Image” phrase for this post type. Added in 4.3', Constants::TEXTDOMAIN),
    'set_featured_image' => _x('Set main image', 'Overrides the “Set featured image” phrase for this post type. Added in 4.3', Constants::TEXTDOMAIN),
    'remove_featured_image' => _x('Remove main image', 'Overrides the “Remove featured image” phrase for this post type. Added in 4.3', Constants::TEXTDOMAIN),
    'use_featured_image' => _x('Use as main image', 'Overrides the “Use as featured image” phrase for this post type. Added in 4.3', Constants::TEXTDOMAIN),
    'archives' => _x('Article archives', 'The post type archive label used in nav menus. Default “Post Archives”. Added in 4.4', Constants::TEXTDOMAIN),
    'insert_into_item' => _x('Insert into noticias', 'Overrides the “Insert into post”/”Insert into page” phrase (used when inserting media into a post). Added in 4.4', Constants::TEXTDOMAIN),
    'uploaded_to_this_item' => _x('Uploaded to this noticia', 'Overrides the “Uploaded to this post”/”Uploaded to this page” phrase (used when viewing media attached to a post). Added in 4.4', Constants::TEXTDOMAIN),
    'filter_items_list' => _x('Filter noticias list', 'Screen reader text for the filter links heading on the post type listing screen. Default “Filter posts list”/”Filter pages list”. Added in 4.4', Constants::TEXTDOMAIN),
    'items_list_navigation' => _x('Articles list navigation', 'Screen reader text for the pagination heading on the post type listing screen. Default “Posts list navigation”/”Pages list navigation”. Added in 4.4', Constants::TEXTDOMAIN),
    'items_list' => _x('Articles list', 'Screen reader text for the items list heading on the post type listing screen. Default “Posts list”/”Pages list”. Added in 4.4', Constants::TEXTDOMAIN),
);

$options = array(
    'labels' => $labels,
    'public' => true,
    'publicly_queryable' => true,
    'show_ui' => true,
    'show_in_menu' => true,
    'query_var' => true,
    'capability_type' => 'post',
    'has_archive' => true,
    'hierarchical' => false,
    'supports' => ['title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments'],
    'taxonomies' => ['post_tag'],
    'rewrite' => array('slug' => 'noticias'),
);

$article = new PostType('article', $options);
$article->labels($labels);
$article->register();

function custom_noticias_permalink_structure($post_link, $post, $leavename, $sample)
{
    if ('article' == get_post_type($post)) {
        $id_noticia = get_post_meta($post->ID, 'id_noticia', true);
        if ($id_noticia) {
            return home_url(user_trailingslashit("noticias/$id_noticia/" . $post->post_name));
        }
    }
    return $post_link;
}
add_filter('post_type_link', 'custom_noticias_permalink_structure', 10, 4);

function custom_article_rewrite_rule() {
    add_rewrite_rule(
        '^noticias/([0-9]+)/([^/]+)?',
        'index.php?post_type=article&name=$matches[2]',
        'top'
    );
}
add_action('init', 'custom_article_rewrite_rule');

function redirect_article_to_404() {
    if (is_singular('article')) {
        global $post;
        $id_noticia = get_post_meta($post->ID, 'id_noticia', true);

        // Obtener la URL actual
        $current_url = home_url(add_query_arg(null, null));
        $expected_url = home_url('/noticias/' . $id_noticia . '/' . $post->post_name);

        // Si la URL actual no coincide con la URL esperada, redirigir a la página 404
        if (strpos($current_url, $expected_url) === false) {
            global $wp_query;
            $wp_query->set_404();
            status_header(404);
            get_template_part(404);
            exit();
        }
    }
}
add_action('template_redirect', 'redirect_article_to_404');
