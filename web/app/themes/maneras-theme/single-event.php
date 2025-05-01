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

// Collect custom fields (ACF or generic metadata).
$fields = array();
if ( function_exists( 'get_fields' ) ) {
	$fields = get_fields( $post->ID ) ?: array();
} else {
	$meta = get_post_meta( $post->ID );
	foreach ( $meta as $key => $value ) {
		if ( in_array( $key, array( 'sender_email', '_edit_lock', '_edit_last', '_thumbnail_id' ), true ) ) {
			continue;
		}
		if ( strpos( $key, '_' ) === 0 ) {
			continue;
		}
		$fields[ $key ] = maybe_unserialize( $value[0] );
	}
}

// Never display the sender's email.
if ( isset( $fields['sender_email'] ) ) {
	unset( $fields['sender_email'] );
}

if ( function_exists( 'ManerasTheme\\render' ) ) {
	// Explicitly pass $post and $fields to the view.
	render( 'single-event', compact( 'post', 'fields' ) );
} else {
	// Classic fallback.
	get_header();
	while ( have_posts() ) :
		the_post();
		?>
		<article class="container mx-auto px-4 py-8">
			<h1 class="text-3xl font-bold mb-4"><?php the_title(); ?></h1>
			<div class="prose max-w-none">
				<?php the_content(); ?>
			</div>
		</article>
		<?php
	endwhile;
	get_footer();
}