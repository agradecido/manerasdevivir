<?php
/**
 * Event Post Type
 * PHP Version 8.3
 *
 * Plugin Name:       Maneras Core Functionality
 * Description:       Event post type for Maneras Core.
 * Version:           1.0.0
 * Author:            Javier Sierra <agradecido@manerasdevivir.com>
 * License:           MIT
 * Text Domain:       maneras-core
 */
namespace ManerasCore\PostTypes;

use PostTypes\PostType;

class Event {

	private array $fields = array(
		'start_day'               => 'sanitize_text_field',
		'start_date'              => 'sanitize_text_field',
		'end_date'                => 'sanitize_text_field',
		'artists'                 => 'sanitize_textarea_field',
		'city'                    => 'sanitize_text_field',
		'country'                 => 'sanitize_text_field',
		'administrative_division' => 'sanitize_text_field',
		'venue'                   => 'sanitize_text_field',
		'venue_lat'               => 'floatval',
		'venue_lon'               => 'floatval',
		'venue_address'           => 'sanitize_textarea_field',
		'price'                   => 'sanitize_text_field',
		'precio_anticipada'       => 'sanitize_text_field',
		'avdance_price'           => 'sanitize_text_field',
		'precio_taquilla'         => 'sanitize_text_field',
		'door_ticket_price'       => 'sanitize_text_field',
		'purchase_url'            => 'esc_url_raw',
		'provider'                => 'sanitize_text_field',
		'provider_id'             => 'intval',
		'poster'                  => 'esc_url_raw',
		'provincia_slug'          => 'sanitize_title',
		'slug'                    => 'sanitize_title',
		'sender_email'            => 'sanitize_email',
		'comments'                => 'sanitize_textarea_field',
		'festival_name'           => 'sanitize_text_field',
		'id_event'                => 'intval',
	);

	public function __construct() {
		$this->registerPostType();
		$this->registerHooks();
	}

	private function getInputType( string $sanitizer, string $fieldName ): string {
		if ( str_contains( $fieldName, 'date' ) ) {
			return 'date';
		}

		return match ( $sanitizer ) {
			'sanitize_email'     => 'email',
			'sanitize_textarea_field' => 'textarea',
			'esc_url_raw'        => 'url',
			'sanitize_url'       => 'url',
			'intval'             => 'number',
			'floatval'           => 'number',
			'sanitize_title'     => 'text',
			'sanitize_text_field' => 'text',
			default              => 'text',
		};
	}

	protected function registerPostType(): void {
		$event = new PostType(
			array(
				'name'     => 'event',
				'singular' => 'Evento',
				'plural'   => 'Eventos',
				'slug'     => 'conciertos',
			)
		);

		$event->options(
			array(
				'supports'        => array( 'title', 'editor', 'thumbnail' ),
				'has_archive'     => true,
				'public'          => true,
				'rewrite'         => array( 'slug' => 'conciertos' ),
				'menu_icon'       => 'dashicons-calendar',
				'show_in_menu'    => true,
				'show_in_rest'    => true,
				'capability_type' => 'post',
			)
		);

		$event->register();
	}

	protected function registerHooks(): void {
		add_action( 'add_meta_boxes', array( $this, 'addMetaBox' ) );
		add_action( 'save_post_event', array( $this, 'saveMetaBoxData' ) );
	}

	public function addMetaBox(): void {
		add_meta_box(
			'event_details',
			__( 'Detalles del Evento', 'maneras-core' ),
			array( $this, 'renderMetaBox' ),
			'event',
			'normal',
			'high',
		);
	}

	public function renderMetaBox( \WP_Post $post ): void {
		wp_nonce_field( 'save_event_meta_box_data', 'event_meta_box_nonce' );

		echo '<table class="form-table">';

		foreach ( $this->fields as $field => $sanitizer ) {
			$value = get_post_meta( $post->ID, $field, true ) ?: '';
			// Adaptar valor si es tipo date (para campos DATETIME)
			if ( $this->getInputType( $sanitizer, $field ) === 'date' && preg_match('/^\d{4}-\d{2}-\d{2}/', $value) ) {
				$value = substr( $value, 0, 10 ); // solo YYYY-MM-DD
			}
			$label     = ucfirst( str_replace( '_', ' ', $field ) );
			$inputType = $this->getInputType( $sanitizer, $field );

			echo "<tr><th><label for='{$field}'>{$label}</label></th><td>";

			if ( $inputType === 'textarea' ) {
				echo "<textarea class='widefat' name='{$field}' id='{$field}'>" . esc_textarea( $value ) . '</textarea>';
			} else {
				echo "<input class='widefat' type='{$inputType}' name='{$field}' id='{$field}' value='" . esc_attr( $value ) . "' />";
			}

			echo '</td></tr>';
		}

		echo '</table>';
	}

	public function saveMetaBoxData( int $post_id ): void {
		if (
			defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ||
			! isset( $_POST['event_meta_box_nonce'] ) ||
			! wp_verify_nonce( $_POST['event_meta_box_nonce'], 'save_event_meta_box_data' ) ||
			( isset( $_POST['post_type'] ) && 'event' === $_POST['post_type'] && ! current_user_can( 'edit_post', $post_id ) )
		) {
			return;
		}

		foreach ( $this->fields as $field => $sanitizer ) {
			if ( isset( $_POST[ $field ] ) ) {
				$value = call_user_func( $sanitizer, $_POST[ $field ] );
				update_post_meta( $post_id, $field, $value );
			}
		}
	}
}
