<?php

namespace ManerasTheme\Features;

// Removed: use Timber\Timber;
use WP_Error;

class NewsSubmission {

	/**
	 * Initializes the news submission feature.
	 * Registers the shortcode and handles form submission.
	 */
	public static function init() {
		add_shortcode( 'news_submission_form', array( self::class, 'renderForm' ) );
		add_action( 'init', array( self::class, 'handleSubmission' ) );
	}

	/**
	 * Renders the news submission form.
	 *
	 * @return string Rendered form HTML.
	 */
	public static function renderForm(): string {
		$twig = \ManerasTheme\Theme::get_twig();
		if (!$twig) {
			if (defined('WP_DEBUG') && WP_DEBUG) {
				error_log('Twig environment not available in NewsSubmission::renderForm');
			}
			return '<!-- News submission form could not be rendered: Twig not available -->';
		}
		return $twig->render(
			'partials/form-news-submission.twig',
			array(
				'submitted' => isset( $_GET['news_submitted'] ),
			)
		);
	}

	/**
	 * Handles the news submission form submission.
	 *
	 * Validates the nonce, sanitizes input, and saves the news article as a draft post.
	 * Also handles file uploads for images.
	 */
	public static function handleSubmission(): void {
		if ( ! isset( $_POST['cgcof_news_submit'] ) ) {
			return;
		}

		// Include WordPress media handling functions.
		require_once ABSPATH . 'wp-admin/includes/image.php';
		require_once ABSPATH . 'wp-admin/includes/file.php';
		require_once ABSPATH . 'wp-admin/includes/media.php';

		if ( ! isset( $_POST['cgcof_news_nonce'] ) || ! wp_verify_nonce( $_POST['cgcof_news_nonce'], 'cgcof_news_submit' ) ) {
			return;
		}

		$title     = sanitize_text_field( $_POST['news_title'] ?? '' );
		$content   = wp_kses_post( $_POST['news_content'] ?? '' );
		$signature = sanitize_text_field( $_POST['news_signature'] ?? '' );
		$source    = sanitize_text_field( $_POST['news_source'] ?? '' );
		$email     = sanitize_email( $_POST['news_email'] ?? '' );

		$post_id = wp_insert_post(
			array(
				'post_title'   => $title,
				'post_content' => $content,
				'post_status'  => 'draft',
				'post_type'    => 'article',
			)
		);

		if ( is_wp_error( $post_id ) ) {
			return;
		}

		$ip        = isset( $_SERVER['REMOTE_ADDR'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REMOTE_ADDR'] ) ) : '';
		$timestamp = current_time( 'mysql' );

		update_post_meta( $post_id, 'firma_sender', $signature );
		update_post_meta( $post_id, 'fuente', $source );
		update_post_meta( $post_id, 'email_sender', $email );
		update_post_meta( $post_id, 'ip_sender', $ip );
		update_post_meta( $post_id, 'timestamp', $timestamp );
		update_post_meta( $post_id, 'fuente', $source );

		$allowed_types = array( 'image/jpeg', 'image/png', 'image/gif', 'image/webp' );

		if ( empty( $_FILES['news_images'] ) || ! is_array( $_FILES['news_images'] ) ) {
			return;
		}

		if ( ! empty( $_FILES['news_images']['name'][0] ) ) {
			$sanitized_names = array_map( 'sanitize_file_name', $_FILES['news_images']['name'] );
			$image_ids       = array();
			foreach ( $sanitized_names as $i => $name ) {
				if ( ! isset( $_FILES['news_images']['size'][ $i ] ) || $_FILES['news_images']['size'][ $i ] > 1048576 ) {
					continue;
				}
				if ( ! isset( $_FILES['news_images']['type'][ $i ] ) || ! in_array( $_FILES['news_images']['type'][ $i ], $allowed_types, true ) ) {
					continue;
				}

				$file = array(
					'name'     => $name,
					'type'     => sanitize_mime_type( $_FILES['news_images']['type'][ $i ] ),
					'tmp_name' => isset( $_FILES['news_images']['tmp_name'][ $i ] ) ? sanitize_text_field( $_FILES['news_images']['tmp_name'][ $i ] ) : '',
					'error'    => isset( $_FILES['news_images']['error'][ $i ] ) ? (int) $_FILES['news_images']['error'][ $i ] : 0,
					'size'     => isset( $_FILES['news_images']['size'][ $i ] ) ? (int) $_FILES['news_images']['size'][ $i ] : 0,
				);

				$_FILES['single_image'] = $file;
				$attachment_id          = media_handle_upload( 'single_image', $post_id );

				if ( ! is_wp_error( $attachment_id ) ) {
					add_post_meta( $post_id, '_news_image_ids', $attachment_id );

					if ( 0 === $i ) {
						set_post_thumbnail( $post_id, $attachment_id );
					}

					wp_update_post(
						array(
							'ID'          => $attachment_id,
							'post_parent' => $post_id,
						)
					);

					$image_ids[] = $attachment_id;
				}
			}

			// Add gallery shortcode if there are images.
			if ( ! empty( $image_ids ) ) {
				$gallery_shortcode = '[gallery ids="' . implode( ',', $image_ids ) . '" link="file" columns="3"]';
				$post_content      = get_post_field( 'post_content', $post_id );
				$post_content     .= "\n\n" . $gallery_shortcode;

				wp_update_post(
					array(
						'ID'           => $post_id,
						'post_content' => $post_content,
					)
				);

				add_post_meta( $post_id, '_has_image_gallery', '1', true );
			}
		}

		wp_safe_redirect( add_query_arg( 'news_submitted', '1', wp_get_referer() ) );
		exit;
	}
}
