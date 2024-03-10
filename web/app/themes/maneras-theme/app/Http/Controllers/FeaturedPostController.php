<?php

namespace App\Http\Controllers;

use Roots\Acorn\View\Composer;
use WP_Query;
use App\Constants;
use App\Traits\Formattable;

class FeaturedPostController extends Composer
{
    use Formattable;

    public function index(): array
    {
        global $post;

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
                $featuredPosts[] = $this->formatFeaturedPostCard($post);
            }
            wp_reset_postdata();
        }
        return $featuredPosts;
    }
}
