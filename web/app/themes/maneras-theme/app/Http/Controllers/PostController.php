<?php

namespace App\Http\Controllers;

use Roots\Acorn\View\Composer;
use WP_Query;
use App\Constants;

class PostController extends Composer
{
    public function _construct()
    {
    }

    public function getFeaturedPosts()
    {
        $args = [
            'category_name' => 'destacados',
            'posts_per_page' => Constants::FEATURED_POSTS_LIMIT,
            'orderby' => 'date',
            'order' => 'DESC',
        ];

        $query = new WP_Query($args);

        $featuredPosts = [];

        if ($query->have_posts()) {
            while ($query->have_posts()) {
                $query->the_post();
                $featuredPosts[] = [
                    'title' => get_the_title(),
                    'permalink' => get_permalink(),
                    'thumbnail' => get_the_post_thumbnail_url(),
                    'excerpt' => get_the_excerpt(),
                ];
            }
            wp_reset_postdata();
        }

        return $featuredPosts;
    }
}
