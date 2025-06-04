<?php

namespace ManerasTheme\Controllers;

use Timber\Timber;

class Footer extends Controller {

	/**
	 * Get footer navigation and site information
	 *
	 * @return array
	 */
	public function get_context() {
		$context = Timber::context();

		$app = new App();

		$context['site'] = $app->site();

		$context['footer_menu'] = Timber::get_menu( 'footer_navigation' );

		return $context;
	}
}
