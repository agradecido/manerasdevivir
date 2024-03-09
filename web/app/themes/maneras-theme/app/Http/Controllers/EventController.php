<?php

namespace App\Http\Controllers;

use Roots\Acorn\View\Composer;
use WP_Query;
use App\Constants;
use App\Traits\Formattable;

class EventController extends Composer
{
    use Formattable;

    /**
     * @return array
     */
    public function index() : array
    {
        global $post;

        $args = [
            'post_type' => 'event',
            'posts_per_page' => Constants::HOME_EVENTS_LIMIT,
            'meta_key' => 'start_date',
            'orderby' => 'meta_value',
            'order' => 'ASC',
        ];

        $query = new WP_Query($args);

        $events = [];

        if ($query->have_posts()) {
            while ($query->have_posts()) {
                $query->the_post();
                $events[] = $this->formatEvent($post);
            }
            wp_reset_postdata();
        }

        return $events;
    }

    /**
     * @param string $province
     * @return array
     */
    public function getEventsByProvince($province) : array
    {
        global $post;

        $args = [
            'post_type' => 'event',
            'posts_per_page' => -1,
            'meta_key' => 'start_date',
            'orderby' => 'meta_value',
            'order' => 'ASC',
            'meta_query' => [
                [
                    'key' => 'administrative_division',
                    'value' => $province,
                    'compare' => '=',
                ],
                [
                    'key' => 'start_date',
                    'value' => date('Y-m-d'),
                    'compare' => '>=',
                    'type' => 'DATE',
                ]
            ],
        ];

        $query = new WP_Query($args);

        $events = [];

        while ($query->have_posts()) {
            $query->the_post();
            $events[] = $this->formatEvent($post);
        }
        wp_reset_postdata();


        return $events;
    }
}
