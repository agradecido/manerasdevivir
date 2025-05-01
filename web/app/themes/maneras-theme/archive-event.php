<?php
/**
 *
 * Template for displaying the “event” post type archive.
 * This file bridges the CPT “event” archive to the theme’s rendering system.
 *
 * @package ManerasTheme
 * @category Theme
 * @version 1.0
 * @author Javier Sierra <agradecido@manerasdevivir.com>
 */

namespace ManerasTheme;

require_once __DIR__ . '/vendor/autoload.php';

use function ManerasTheme\render;

if ( function_exists( 'ManerasTheme\\render' ) ) {
	render( 'archive-event' );
} else {
	// Fallback to a basic loop if Blade rendering is unavailable.
	get_header();
	?>
	<div class="container mx-auto px-4 py-12">
		<h1 class="text-3xl font-bold mb-6"><?php post_type_archive_title(); ?></h1>

		<?php if ( have_posts() ) : ?>
		<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
			<?php
			while ( have_posts() ) :
				the_post();
				?>
				<?php
				// Collect custom fields (ACF or post_meta).
				if ( function_exists( 'get_fields' ) ) {
					$fields = get_fields( get_the_ID() ) ?: array();
				} else {
					$fields = get_post_meta( get_the_ID() );
				}
				$start = ! empty( $fields['start_date'] )
				? date_i18n( 'j F, Y', strtotime( $fields['start_date'] ) )
				: '';
				$city  = $fields['event_city'] ?? '';
				?>
			<a href="<?php the_permalink(); ?>"
				class="block bg-white rounded-lg shadow hover:shadow-lg transition">
				<?php if ( has_post_thumbnail() ) : ?>
				<div class="h-48 bg-cover bg-center rounded-t-lg"
					style="background-image:url('<?php echo get_the_post_thumbnail_url( get_the_ID(), 'large' ); ?>')"></div>
				<?php endif; ?>
				<div class="p-4">
				<h2 class="text-xl font-semibold mb-2"><?php the_title(); ?></h2>
				<?php if ( $start ) : ?>
					<p class="text-gray-600 text-sm mb-1"><strong>Fecha:</strong> <?php echo esc_html( $start ); ?></p>
				<?php endif; ?>
				<?php if ( $city ) : ?>
					<p class="text-gray-600 text-sm"><strong>Ciudad:</strong> <?php echo esc_html( $city ); ?></p>
				<?php endif; ?>
				</div>
			</a>
			<?php endwhile; ?>
		</div>

			<?php
			// Pagination for long archives.
			the_posts_pagination(
				array(
					'mid_size'  => 2,
					'prev_text' => '&laquo; Anterior',
					'next_text' => 'Siguiente &raquo;',
				)
			);
			?>

		<?php else : ?>
		<p class="text-gray-500">No hay conciertos para mostrar.</p>
		<?php endif; ?>
	</div>
	<?php
	get_footer();
}
