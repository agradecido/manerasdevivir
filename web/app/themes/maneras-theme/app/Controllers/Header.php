<?php
/**
 * Header Controller
 *
 * @package ManerasTheme
 * @subpackage Controllers
 */

namespace ManerasTheme\Controllers;

use Timber\Timber;

/**
 * Header Controller class
 *
 * Handles header-related functionality and data
 */
class Header extends Controller {

	/**
	 * Get header navigation and site information
	 *
	 * @return array
	 */
	public function get_context() {
		$context = Timber::context();

		$app = new App();

		$context['site'] = $app->site();

		$context['primary_menu'] = Timber::get_menu( 'primary_navigation' );

		return $context;
	}
}
