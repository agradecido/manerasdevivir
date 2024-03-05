<?php

namespace App\View\Components;

use Roots\Acorn\View\Component;
use WP_Query;

class FeaturedContentComponent extends Component
{
    public $posts;

    public function __construct()
    {
        $this->posts = $this->getFeaturedPosts();
    }

    protected function getFeaturedPosts() : array
    {
        $args = [
            'post_type' => 'post',
            'posts_per_page' => 10,
            'category_name' => 'destacados',
            'orderby' => 'date',
            'order' => 'DESC'
        ];

        $query = new WP_Query($args);

        if (!$query->have_posts()) {
            return [];
        }

        return $query->posts;
    }

    public function render()
    {
        return $this->view('components.featured-content', ['posts' => $this->posts]);
    }
}
