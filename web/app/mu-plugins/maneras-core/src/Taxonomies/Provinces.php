<?php

/**
 * Provinces Taxonomy
 * 
 * PHP Version 8.3
 *
 * Plugin Name:       Maneras Core Functionality
 * Description:       Event post type for Maneras Core.
 * Version:           1.0.0
 * Author:            Javier Sierra <agradecido@manerasdevivir.com>
 * License:           MIT
 * Text Domain:       maneras-core
 */

namespace ManerasCore\Taxonomies;

class Provinces
{
    /**
     * @var array
     */
    private array $fields = [
        'provincia_slug' => 'sanitize_title',
    ];

    /**
     * Provinces constructor.
     */
    public function __construct()
    {
        // Register taxonomy at the proper time.
        add_action('init', [$this, 'registerTaxonomy']);

        // Add query filter.
        add_action('pre_get_posts', [$this, 'filterArchiveQuery']);
    }

    /**
     * Register the taxonomy.
     */
    public function registerTaxonomy(): void
    {
        register_taxonomy('province', 'event', [
            'label' => 'Provincias',
            'public' => true,
            'hierarchical' => false,
            'rewrite' => ['slug' => 'provincia'],
            'show_admin_column' => true,
            'labels' => [
                'name' => 'Provincias',
                'singular_name' => 'Provincia',
                'search_items' => 'Buscar provincias',
                'all_items' => 'Todas las provincias',
                'edit_item' => 'Editar provincia',
                'update_item' => 'Actualizar provincia',
                'add_new_item' => 'Añadir nueva provincia',
                'new_item_name' => 'Nombre de nueva provincia',
                'menu_name' => 'Provincias',
            ],
        ]);
    }

    /**
     * Filter the event archive query by province.
     */
    public function filterArchiveQuery(\WP_Query $query): void
    {
        if (is_admin() || !$query->is_main_query() || !is_post_type_archive('event')) {
            return;
        }

        if (!empty($_GET['province'])) {
            $query->set('tax_query', [[
                'taxonomy' => 'province',
                'field'    => 'slug',
                'terms'    => sanitize_text_field($_GET['province']),
            ]]);
        }
    }
}
