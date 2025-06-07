<?php
/**
 * Trait WithBreadcrumbs
 * 
 * Provides breadcrumb functionality for controllers
 *
 * @package ManerasTheme\Traits
 */

namespace ManerasTheme\Traits;

use ManerasTheme\Breadcrumbs;

trait WithBreadcrumbs {
	
	/**
	 * Setup breadcrumbs for the current page.
	 */
	protected function setup_breadcrumbs() {
		$breadcrumbs = new Breadcrumbs();
		$this->data['breadcrumbs'] = $breadcrumbs->get_items();
		$this->data['breadcrumbs_json_ld'] = $breadcrumbs->get_json_ld();
	}
	
	/**
	 * Get breadcrumbs data.
	 * 
	 * @return array
	 */
	public function breadcrumbs() {
		return $this->data['breadcrumbs'] ?? [];
	}
	
	/**
	 * Get breadcrumbs JSON-LD schema.
	 * 
	 * @return string
	 */
	public function breadcrumbs_json_ld() {
		return $this->data['breadcrumbs_json_ld'] ?? '';
	}
}
