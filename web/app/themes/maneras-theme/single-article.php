<?php
/**
 *
 * single-article.php
 *
 * Template for displaying single article posts
 * This file is a bridge between the custom post type "article" and the theme's rendering system.
 */

namespace ManerasTheme;

if ( function_exists( 'ManerasTheme\\render' ) ) {
	render( 'single-article' );
} else {
	// Fallback.
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