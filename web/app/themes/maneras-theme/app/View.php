<?php

namespace ManerasTheme;

use Timber\Timber;
use ManerasTheme\Controllers\Controller;

class View {

	/**
	 * Render a view with the specified controller.
	 *
	 * @param string    $template  The template to render.
	 * @param array     $data      Additional data to pass to the view.
	 * @param mixed     $controller The controller instance or class name.
	 * @param bool|null $return    Whether to return the rendered view.
	 * @return string|void
	 */
	public static function render( $template, $data = array(), $controller = null, $return = false ) {
		// Get context from controller.
		$context = static::get_controller_context( $controller );

		// Merge additional data.
		if ( ! empty( $data ) ) {
			$context = array_merge( $context, $data );
		}

		// Render the view.
		return Timber::render( $template, $context, false, $return );
	}

	/**
	 * Get the context from a controller.
	 *
	 * @param mixed $controller The controller instance or class name.
	 * @return array
	 */
	protected static function get_controller_context( $controller = null ) {
		// Default controller name based on current template.
		if ( is_null( $controller ) ) {
			$controller = static::get_default_controller();
		}

		// If controller is a string, instantiate it.
		if ( is_string( $controller ) && class_exists( $controller ) ) {
			$controller = new $controller();
		}

		// If controller is an instance of Controller, get data.
		if ( $controller instanceof Controller ) {
			return $controller->toArray();
		}

		// Fallback to empty context.
		return array();
	}

	/**
	 * Get the default controller class based on the current template.
	 *
	 * @return string
	 */
	protected static function get_default_controller() {
		if ( is_front_page() ) {
			return \ManerasTheme\Controllers\FrontPage::class;
		}

		if ( is_singular() ) {
			return \ManerasTheme\Controllers\Single::class;
		}

		if ( is_archive() || is_home() ) {
			return \ManerasTheme\Controllers\Archive::class;
		}

		if ( is_search() ) {
			return \ManerasTheme\Controllers\Search::class;
		}

		if ( is_404() ) {
			return \ManerasTheme\Controllers\NotFound::class;
		}

		return \ManerasTheme\Controllers\App::class;
	}
}
