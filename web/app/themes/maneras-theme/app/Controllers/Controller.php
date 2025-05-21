<?php

namespace ManerasTheme\Controllers;

class Controller {

	/**
	 * Data to be passed to view.
	 *
	 * @var array
	 */
	protected $data = array();

	/**
	 * Return the data to be passed to the view.
	 *
	 * @return array
	 */
	public function data() {
		return $this->data;
	}

	/**
	 * Convert object properties to an array suitable for view consumption.
	 *
	 * @return array
	 */
	public function toArray() {
		$data = $this->data();

		return array_reduce(
			array_keys( get_object_vars( $this ) ),
			function ( $carry, $key ) use ( $data ) {
				if ( $key === 'data' ) {
					return $carry;
				}

				$value = $this->{$key} instanceof self
				? $this->{$key}->toArray()
				: $this->{$key};

				$carry[ $key ] = $value;

				return $carry;
			},
			$data
		);
	}
}
