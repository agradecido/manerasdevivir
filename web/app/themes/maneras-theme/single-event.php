<?php
/**
 * single-event.php
 *
 * Template for displaying a single event post.
 * This file is the bridge between the "event" CPT and the theme's rendering system.
 */

namespace ManerasTheme;

// Always get the global $post object.
global $post;
$post = get_post();

$fields = array();
$meta   = get_post_meta( $post->ID );
foreach ( $meta as $key => $value ) {
	if ( in_array( $key, array( 'sender_email', '_edit_lock', '_edit_last', '_thumbnail_id' ), true ) ) {
		continue;
	}
	if ( strpos( $key, '_' ) === 0 ) {
		continue;
	}
	$fields[ $key ] = maybe_unserialize( $value[0] );
}

// Never display the sender's email.
if ( isset( $fields['sender_email'] ) ) {
	unset( $fields['sender_email'] );
}

if ( function_exists( 'render' ) ) {
	// Explicitly pass $post and $fields to the view.
	render( 'pages/events/single-event', compact( 'post', 'fields' ) );
}
