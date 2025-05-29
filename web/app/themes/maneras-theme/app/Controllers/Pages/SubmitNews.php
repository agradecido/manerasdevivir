<?php

namespace ManerasTheme\Controllers\Pages;

use ManerasTheme\Features\NewsSubmission;
use Timber\Timber;

class SubmitNews {
	public static function register(): void {
		add_filter( 'timber/context', array( self::class, 'addContext' ) );
		add_action( 'template_redirect', array( self::class, 'handleSubmission' ) );
	}

	public static function addContext( array $context ): array {
		$context['submitted'] = isset( $_GET['news_submitted'] );
		return $context;
	}

	public static function handleSubmission(): void {
		if ( ! is_page( 'enviar-noticia' ) ) {
			return;
		}
		NewsSubmission::handleSubmission();
	}
}
