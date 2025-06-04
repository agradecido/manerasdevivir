<?php
/**
 * Interview Post Type
 * The Interview post type is used to manage interviews on the site.
 * PHP Version 8.3
 *
 * Plugin Name:       Maneras Core Functionality
 * Description:       Interview post type for Maneras Core.
 * Version:           1.0.0
 * Author:            Javier Sierra <agradecido@manerasdevivir.com>
 * License:           MIT
 * Text Domain:       maneras-core
 */

namespace ManerasCore\PostTypes;

use PostTypes\PostType;

class Interview {

	/**
	 * Custom meta fields for the Interview post type.
	 *
	 * @var array<string, callable|string>
	 */
	private array $fields = array(
		'entrevistado'       => 'sanitize_text_field',
		'entrevistador'      => 'sanitize_text_field',
		'fecha_entrevista'   => 'sanitize_text_field',
		'lugar'              => 'sanitize_text_field',
		'evento_relacionado' => 'intval',
		'email_contacto'     => 'sanitize_email',
		'fuente'             => 'sanitize_textarea_field',
		'canonical'          => 'sanitize_textarea_field',
		'destacada'          => 'boolval',
		'robots'             => 'sanitize_textarea_field',
		'id_entrevista'      => 'intval',
	);

	/**
	 * Constructor for the Interview class.
	 * Registers the custom post type and hooks.
	 */
	public function __construct() {
		$this->registerPostType();
		$this->registerHooks();
	}

	/**
	 * Determine the HTML input type based on the sanitizer and field name.
	 *
	 * @param string $sanitizer The sanitizer function name.
	 * @param string $fieldName The name of the field.
	 *
	 * @return string The HTML input type.
	 */
	private function getInputType( string $sanitizer, string $fieldName ): string {
		if ( str_contains( $fieldName, 'fecha' ) || str_contains( $fieldName, 'date' ) ) {
			return 'date';
		}

		return match ( $sanitizer ) {
			'sanitize_email'              => 'email',
			'sanitize_textarea_field'     => 'textarea',
			'esc_url_raw', 'sanitize_url' => 'url',
			'intval'                      => 'number',
			'floatval'                    => 'number',
			'boolval'                     => 'checkbox',
			'sanitize_title'              => 'text',
			'sanitize_text_field'         => 'text',
			default                       => 'text',
		};
	}

	/**
	 * Register the 'interview' custom post type.
	 *
	 * @return void
	 */
	protected function registerPostType(): void {
		$interview = new PostType(
			array(
				'name'     => 'interview',
				'singular' => 'Entrevista',
				'plural'   => 'Entrevistas',
				'slug'     => 'entrevistas',
			)
		);

		$interview->options(
			array(
				'public'          => true,
				'has_archive'     => true,
				'menu_icon'       => 'dashicons-format-audio',
				'menu_position'   => 5,
				'taxonomies'      => array( 'post_tag', 'featured', 'province' ),
				'supports'        => array(
					'title',
					'editor',
					'excerpt',
					'thumbnail',
					'custom-fields',
				),
				'show_in_menu'    => true,
				'show_in_rest'    => true,
				'rewrite'         => array(
					'slug'       => 'entrevistas',
					'with_front' => false,
				),
				'capability_type' => 'post',
			)
		);

		$interview->register();
	}


	/**
	 * Register actions for metabox and save logic.
	 *
	 * @return void
	 */
	protected function registerHooks(): void {
		add_action( 'add_meta_boxes', array( $this, 'addMetaBox' ) );
		add_action( 'save_post_interview', array( $this, 'saveMetaBoxData' ) );
	}

	/**
	 * Add the interview details metabox.
	 *
	 * @return void
	 */
	public function addMetaBox(): void {
		add_meta_box(
			'interview_details',
			__( 'Detalles de la Entrevista', 'maneras-core' ),
			array( $this, 'renderMetaBox' ),
			'interview',
			'normal',
			'high'
		);
	}

	/**
	 * Render the metabox fields for an interview.
	 *
	 * @param \WP_Post $post Current post object.
	 *
	 * @return void
	 */
	public function renderMetaBox( \WP_Post $post ): void {
		wp_nonce_field( 'save_interview_meta_box_data', 'interview_meta_box_nonce' );

		echo '<table class="form-table">';

		foreach ( $this->fields as $field => $sanitizer ) {
			$value      = get_post_meta( $post->ID, $field, true );
			$label      = ucfirst( str_replace( '_', ' ', $field ) );
			$input_type = $this->getInputType( $sanitizer, $field );

			echo "<tr><th><label for='{$field}'>{$label}</label></th><td>";

			if ( $input_type === 'textarea' ) {
				echo "<textarea class='widefat' name='{$field}' id='{$field}'>" . esc_textarea( $value ) . '</textarea>';
			} elseif ( $input_type === 'checkbox' ) {
				$checked = ! empty( $value ) ? 'checked' : '';
				echo "<input type='checkbox' name='{$field}' id='{$field}' value='1' {$checked} />";
			} else {
				echo "<input class='widefat' type='{$input_type}' name='{$field}' id='{$field}' value='" . esc_attr( $value ) . "' />";
			}

			echo '</td></tr>';
		}

		echo '</table>';
	}

	/**
	 * Save metabox data when an interview is saved.
	 *
	 * @param int $post_id Post ID.
	 *
	 * @return void
	 */
	public function saveMetaBoxData( int $post_id ): void {
		if (
			defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ||
			! isset( $_POST['interview_meta_box_nonce'] ) ||
			! wp_verify_nonce( $_POST['interview_meta_box_nonce'], 'save_interview_meta_box_data' ) ||
			( isset( $_POST['post_type'] ) && 'interview' === $_POST['post_type'] && ! current_user_can( 'edit_post', $post_id ) )
		) {
			return;
		}

		foreach ( $this->fields as $field => $sanitizer ) {
			if ( 'boolval' === $sanitizer ) {
				$value = isset( $_POST[ $field ] ) ? true : false;
			} elseif ( isset( $_POST[ $field ] ) ) {
				$value = call_user_func( $sanitizer, $_POST[ $field ] );
			} else {
				continue;
			}

			update_post_meta( $post_id, $field, $value );
		}
	}
}