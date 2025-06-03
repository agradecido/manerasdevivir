<?php
/**
 * Archive template for 'article' post type
 *
 * @package ManerasTheme
 */

use ManerasTheme\View;

// Render the articles archive template.
View::render( 'archives/archive-articles.twig', array(), \ManerasTheme\Controllers\ArticlesArchive::class );
