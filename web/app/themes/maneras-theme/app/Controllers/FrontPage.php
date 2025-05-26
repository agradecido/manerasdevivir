<?php
namespace ManerasTheme\Controllers;

use Timber\Timber;
use ManerasTheme\Traits\WithPagination;

class FrontPage extends Controller {
	use WithPagination;

	/**
	 * Posts por página para artículos recientes.
	 *
	 * @var int
	 */
	protected $posts_per_page = 5;

	/**
	 * Constructor - initialize data that should be available.
	 */
	public function __construct() {
		$this->data['featured']   = $this->featured();
		$this->data['recent']     = $this->recent();
		$this->data['pagination'] = $this->pagination();
	}

	/**
	 * Featured posts.
	 *
	 * @param int $count Number of featured posts to fetch.
	 * @return array
	 */
	public function featured( $count = 3 ) {
		$featured_query = new \WP_Query(
			array(
				'posts_per_page' => $count,
				'post_type'      => 'article',
				'tax_query'      => array(
					array(
						'taxonomy' => 'featured',
						'field'    => 'slug',
						'terms'    => 'home',
					),
				),
			)
		);

		return Timber::get_posts( $featured_query );
	}

	/**
	 * Recent posts.
	 *
	 * @param int|null $posts_per_page Number of recent posts to fetch per page (optional).
	 * @return array
	 */
	public function recent( $posts_per_page = null ) {
		// Configurar posts por página.
		if ( null === $posts_per_page ) {
			$posts_per_page = $this->posts_per_page;
		}

		// Detectar la página desde la URL (más fiable que las query vars).
		$paged = 1;
		if ( isset( $_SERVER['REQUEST_URI'] ) ) {
			$request_uri = sanitize_text_field( wp_unslash( $_SERVER['REQUEST_URI'] ) );
			if ( preg_match( '/\/page\/(\d+)\/?/', $request_uri, $matches ) ) {
				$paged = (int) $matches[1];
			}
		}

		// Asegurarnos de que paged sea al menos 1.
		$paged = max( 1, $paged );

		// Crear la consulta.
		$args = array(
			// 'post_type'      => array( 'post', 'article' ), // Buscar tanto post como article.
			'post_type'      => 'article',
			'posts_per_page' => $posts_per_page,
			'paged'          => $paged,
			'page'           => $paged,
		);

		$query = new \WP_Query( $args );

		// Guardar la consulta para su uso en el método pagination().
		$this->last_query = $query;

		// Devolver los posts.
		return Timber::get_posts( $query );
	}

	/**
	 * Pagination data for recent posts.
	 *
	 * @return array
	 */
	public function pagination() {
		// Detectar la página actual de la URL.
		$paged = 1;

		if ( isset( $_SERVER['REQUEST_URI'] ) ) {
			$request_uri = sanitize_text_field( wp_unslash( $_SERVER['REQUEST_URI'] ) );
			if ( preg_match( '/\/page\/(\d+)\/?/', $request_uri, $matches ) ) {
				$paged = (int) $matches[1];
			}
		}

		// Obtener la paginación actual.
		$pagination_data = $this->get_pagination_data();
		$total_pages     = $pagination_data['total'];

		// Forzar que la página actual sea la detectada desde la URL.
		$current_page               = $paged;
		$pagination_data['current'] = $current_page;

		// Generar los enlaces correctamente para la página principal.
		if ( 1 < $current_page ) {
			$pagination_data['prev_link'] = 1 === $current_page - 1
				? home_url( '/' )
				: home_url( '/page/' . ( $current_page - 1 ) . '/' );
		} else {
			$pagination_data['prev_link'] = '';
		}

		if ( $total_pages > $current_page ) {
			$pagination_data['next_link'] = home_url( '/page/' . ( $current_page + 1 ) . '/' );
		} else {
			$pagination_data['next_link'] = '';
		}

		return $pagination_data;
	}
}
