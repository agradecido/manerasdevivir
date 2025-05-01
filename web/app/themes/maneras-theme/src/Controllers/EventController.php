<?php
/**
 * EventController class.
 *
 * This class handles the retrieval of events for the Maneras theme.
 * It queries both upcoming and past events from the WordPress database.
 * The results are returned in an associative array.
 *
 * @package ManerasTheme\Controllers
 * @category Controllers
 * @version 1.0
 * @author Javier Sierra <agradecido@manerasdevivir.com>
 */

namespace ManerasTheme\Controllers;

use WP_Query;

class EventController {

	/**
	 * Retrieves upcoming and past events.
	 *
	 * @return array An associative array containing two WP_Query objects:
	 *               'upcoming' for upcoming events and 'past' for past events.
	 */
	public static function getEvents() {
		$today = date( 'Y-m-d' );

		// Upcoming events query.
		$upcoming_args = array(
			'post_type'      => 'event',
			'posts_per_page' => -1,
			'meta_key'       => 'start_date',
			'orderby'        => 'meta_value',
			'order'          => 'ASC',
			'meta_query'     => array(
				array(
					'key'     => 'start_date',
					'value'   => $today,
					'compare' => '>=',
					'type'    => 'DATE',
				),
			),
		);
		$upcoming      = new WP_Query( $upcoming_args );

		// Past events query.
		$past_args = array(
			'post_type'      => 'event',
			'posts_per_page' => 10,
			'meta_key'       => 'start_date',
			'orderby'        => 'meta_value',
			'order'          => 'DESC',
			'meta_query'     => array(
				array(
					'key'     => 'start_date',
					'value'   => $today,
					'compare' => '<',
					'type'    => 'DATE',
				),
			),
		);
		$past      = new WP_Query( $past_args );

		return array(
			'upcoming' => $upcoming,
			'past'     => $past,
		);
	}
}
