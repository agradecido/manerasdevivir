<?php

namespace ManerasTheme\Controllers;

class App extends Controller {

	/**
	 * Site information.
	 *
	 * @return array
	 */
	public function site() {
		$url  = home_url();
		$host = wp_parse_url( $url, PHP_URL_HOST );

		return array(
			'name'        => get_bloginfo( 'name' ),
			'description' => get_bloginfo( 'description' ),
			'url'         => $url,
			'host'        => $host,
		);
	}

	/**
	 * Navigation menus.
	 *
	 * @return array
	 */
	public function menu() {
		$menus = [];

		// Primary Navigation
		$primary_menu_items = wp_get_nav_menu_items('primary_navigation');
		$processed_primary = [];
		if ($primary_menu_items) {
			foreach ($primary_menu_items as $item) {
				// Basic processing, can be expanded
				$processed_primary[] = [
					'id' => $item->ID,
					'title' => $item->title,
					'url' => $item->url,
					'target' => $item->target,
					'classes' => implode(' ', $item->classes ?: []),
					// Note: Children need to be processed recursively if a hierarchical menu is needed directly
				];
			}
		}
		$menus['primary'] = $processed_primary;

		// Footer Navigation
		$footer_menu_items = wp_get_nav_menu_items('footer_navigation');
		$processed_footer = [];
		if ($footer_menu_items) {
			foreach ($footer_menu_items as $item) {
				$processed_footer[] = [
					'id' => $item->ID,
					'title' => $item->title,
					'url' => $item->url,
					'target' => $item->target,
					'classes' => implode(' ', $item->classes ?: []),
				];
			}
		}
		$menus['footer'] = $processed_footer;

		return $menus;
	}

	/**
	 * Sidebar data.
	 *
	 * @return mixed
	 */
	public function sidebar() {
		if (is_active_sidebar('sidebar-primary')) {
			ob_start();
			dynamic_sidebar('sidebar-primary');
			return ob_get_clean(); // Returns the HTML content as a string
		}
		return null;
	}
}
