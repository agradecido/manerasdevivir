<?php

namespace App\View\Components;

use Roots\Acorn\View\Component;
use WP_Query;

class EventsModuleComponent extends Component
{
    public  array  $posts;
    private string $post_type = 'event';

    public function __construct()
    {
        $this->posts = $this->getLastEvents();
    }

    protected function getLastEvents() : array
    {
        $args = [
            'post_type' => $this->post_type,
            'posts_per_page' => 10,
            'orderby' => 'date',
            'order' => 'DESC'
        ];

        $query = new WP_Query($args);

        if (!$query->have_posts()) {
            return [];
        }

        return $query->posts;
    }

    public function render() : string
    {
        return $this->view('components.events-module', ['posts' => $this->posts]);
    }
}
