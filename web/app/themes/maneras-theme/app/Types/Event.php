<?php

namespace App\Types;

use App\Constants;
use PostTypes\PostType;

class Event
{
    private array $fields = [
        'start_date' => 'sanitize_text_field',
        'end_date' => 'sanitize_text_field',
        'artists' => 'sanitize_textarea_field',
        'city' => 'sanitize_text_field',
        'country' => 'sanitize_text_field',
        'administrative_division' => 'sanitize_text_field',
        'venue' => 'sanitize_text_field',
        'venue_lat' => 'floatval',
        'venue_lon' => 'floatval',
        'venue_address' => 'sanitize_textarea_field',
        'price' => 'sanitize_text_field',
        'precio_anticipada' => 'sanitize_text_field',
        'precio_taquilla' => 'sanitize_text_field',
        'purchase_url' => 'esc_url_raw',
        'provider' => 'sanitize_text_field',
        'provider_id' => 'intval',
        'poster' => 'esc_url_raw',
        'provincia_slug' => 'sanitize_title',
        'slug' => 'sanitize_title',
        'sender_email' => 'sanitize_email',
        'comments' => 'sanitize_textarea_field',
        'festival_name' => 'sanitize_text_field',
    ];

    public function __construct()
    {
        $this->createPostType();
        $this->addHooks();
    }

    protected function createPostType() : void
    {
        $names = [
            'name' => 'event',
            'singular' => __('Evento', Constants::TEXTDOMAIN),
            'plural' => __('Eventos', Constants::TEXTDOMAIN),
            'slug' => 'conciertos',
        ];

        $options = [
            'supports' => ['title', 'editor', 'thumbnail'],
            'has_archive' => true,
            'public' => true,
            'rewrite' => ['slug' => 'conciertos'],
            'menu_icon' => 'dashicons-calendar',
        ];

        $event = new PostType($names, $options);
        $event->register();
    }

    protected function addHooks() : void
    {
        add_action('init', [$this, 'registerMetaBoxes']);
    }

    public function registerMetaBoxes()
    {
        add_action('add_meta_boxes', function () {
            add_meta_box('event_details', __('Detalles del Evento', 'textdomain'), [$this, 'eventMetaBoxCallback'], 'event', 'normal', 'high');
        });

        add_action('save_post_event', function ($post_id) {
            if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE || wp_is_post_autosave($post_id) || wp_is_post_revision($post_id)) {
                return;
            }

            // check_admin_referer('event_nonce_action', 'event_nonce');

            foreach ($this->fields as $field => $sanitizer) {
                if (array_key_exists($field, $_POST)) {
                    update_post_meta($post_id, $field, call_user_func($sanitizer, $_POST[$field]));
                }
            }
        });
    }

    public function eventMetaBoxCallback($post)
    {
        // Seguridad: Nonce field para verificación
        wp_nonce_field('event_save_meta_box_data', 'event_meta_box_nonce');

        // Campos del formulario
        foreach ($this->fields as $field => $type) {
            $value = get_post_meta($post->ID, $field, true);
            echo '<label for="' . esc_attr($field) . '">' . esc_html(ucfirst(str_replace('_', ' ', $field))) . '</label>';
            if ($type === 'textarea') {
                echo '<textarea id="' . esc_attr($field) . '" name="' . esc_attr($field) . '" class="widefat">' . esc_textarea($value) . '</textarea>';
            } else {
                echo '<input type="' . esc_attr($type) . '" id="' . esc_attr($field) . '" name="' . esc_attr($field) . '" value="' . esc_attr($value) . '" class="widefat">';
            }
            echo '<br><br>';
        }
    }

    public function getFeaturedEvents() : array
    {
        $args = [
            'post_type' => 'event',
            'posts_per_page' => 5,
            'meta_key' => 'start_date',
            'orderby' => 'meta_value',
            'order' => 'ASC',
            'meta_query' => [
                [
                    'key' => 'start_date',
                    'value' => date('Y-m-d'),
                    'compare' => '>=',
                    'type' => 'DATE',
                ],
            ],
        ];

        $query = new \WP_Query($args);
        $events = [];

        if ($query->have_posts()) {
            while ($query->have_posts()) {
                $query->the_post();
                $event = [
                    'title' => get_the_title(),
                    'permalink' => get_the_permalink(),
                    'start_date' => get_post_meta(get_the_ID(), 'start_date', true),
                    'artists' => get_post_meta(get_the_ID(), 'artists', true),
                    'city' => get_post_meta(get_the_ID(), 'city', true),
                    'administrative_division' => get_post_meta(get_the_ID(), 'administrative_division', true),
                    'venue' => get_post_meta(get_the_ID(), 'venue', true),
                    'price' => get_post_meta(get_the_ID(), 'price', true),
                    'purchase_url' => get_post_meta(get_the_ID(), 'purchase_url', true),
                    'poster' => get_the_post_thumbnail_url(get_the_ID(), 'full'),
                ];
            }
        }

        return $events;
    }
}

new Event();
