<?php

namespace ManerasTheme\Controllers;

use Timber\Timber;

class App extends Controller {

	/**
	 * Site information.
	 *
	 * @return array
	 */
	public function site() {
		return array(
			'name'        => get_bloginfo( 'name' ),
			'description' => get_bloginfo( 'description' ),
			'url'         => home_url(),
		);
	}

	/**
	 * Navigation menus.
	 *
	 * @return array
	 */
	public function menu() {
		return array(
			'primary' => Timber::get_menu( 'primary_navigation' ),
			'footer'  => Timber::get_menu( 'footer_navigation' ),
		);
	}

	/**
	 * Sidebar data.
	 *
	 * @return mixed
	 */
	public function sidebar() {
		if ( is_active_sidebar( 'sidebar-primary' ) ) {
			return Timber::get_widgets( 'sidebar-primary' );
		}

		return null;
	}
}
