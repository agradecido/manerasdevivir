<?php

namespace ManerasTheme\Controllers;

use Timber\Timber;
use Timber\Post;
use ManerasTheme\Traits\WithBreadcrumbs;
use ManerasTheme\Breadcrumbs;

/**
 * Single post controller.
 * Handles the logic for displaying a single post.
 *
 * @package ManerasTheme\Controllers
 */
class Single extends Controller {
	
	use WithBreadcrumbs;

	/**
	 * The post object.
	 *
	 * @var \Timber\Post
	 */
	public $post;

	/**
	 * Constructor.
	 */
	public function __construct() {
		$this->post = Timber::get_post();
		$this->setup_post_date();
		$this->setup_post_author();
		$this->setup_breadcrumbs();
	}

	/**
	 * Setup breadcrumbs for the current post.
	 */
	private function setup_breadcrumbs() {
		$breadcrumbs = new Breadcrumbs();
		$this->data['breadcrumbs'] = $breadcrumbs->get_items();
		$this->data['breadcrumbs_json_ld'] = $breadcrumbs->get_json_ld();
	}

	/**
	 * Setup formatted post date in Spanish.
	 */
	private function setup_post_date() {
		if ( ! $this->post ) {
			return;
		}

		// Obtener el post de WordPress para acceder a post_date.
		$wp_post = get_post( $this->post->ID );
		if ( ! $wp_post ) {
			return;
		}

		// Obtener componentes de la fecha.
		$timestamp = strtotime( $wp_post->post_date );
		$dia       = gmdate( 'j', $timestamp );
		$anio      = gmdate( 'Y', $timestamp );
		$mes_num   = gmdate( 'n', $timestamp );

		// Array de nombres de meses en español.
		$meses_es = array(
			1  => 'enero',
			2  => 'febrero',
			3  => 'marzo',
			4  => 'abril',
			5  => 'mayo',
			6  => 'junio',
			7  => 'julio',
			8  => 'agosto',
			9  => 'septiembre',
			10 => 'octubre',
			11 => 'noviembre',
			12 => 'diciembre',
		);

		// Construir la fecha formateada en español.
		$fecha_es = $dia . ' de ' . $meses_es[ $mes_num ] . ' de ' . $anio;

		// Añadir la fecha formateada al post.
		$this->post->wp_date = $fecha_es;
	}

	/**
	 * Setup post author information.
	 * Obtiene el autor del campo personalizado 'firma_sender' o del autor de WordPress como fallback.
	 */
	private function setup_post_author() {
		if ( ! $this->post ) {
			return;
		}

		// Primero intentar obtener el autor desde el campo personalizado 'firma_sender'.
		$firma_sender = get_post_meta( $this->post->ID, 'firma_sender', true );

		// Si tenemos una firma_sender, usarla como autor.
		if ( ! empty( $firma_sender ) ) {
			$this->post->author_name = $firma_sender;
			return;
		}

		// Como fallback, intentar obtener el autor de WordPress.
		$wp_post = get_post( $this->post->ID );
		if ( ! $wp_post || ! $wp_post->post_author ) {
			return;
		}

		// Obtener datos del autor.
		$author_data = get_userdata( $wp_post->post_author );
		if ( ! $author_data ) {
			return;
		}

		// Guardar el nombre del autor directamente en el post.
		$this->post->author_name = $author_data->display_name;
	}

	/**
	 * Get the post comments.
	 *
	 * @return array
	 */
	public function comments() {
		return array(
			'count' => get_comments_number( $this->post->ID ),
			'list'  => get_comments(
				array(
					'post_id' => $this->post->ID,
					'status'  => 'approve',
				)
			),
		);
	}

	/**
	 * Get related posts.
	 *
	 * @param int $count Number of related posts to fetch.
	 * @return array
	 */
	public function related( $count = 3 ) {
		// Get posts in the same category.
		$categories = wp_get_post_categories( $this->post->ID );

		if ( empty( $categories ) ) {
			return array();
		}

		$related_query = new \WP_Query(
			array(
				'category__in'   => $categories,
				'post__not_in'   => array( $this->post->ID ),
				'posts_per_page' => $count,
			)
		);

		return Timber::get_posts( $related_query );
	}
}
