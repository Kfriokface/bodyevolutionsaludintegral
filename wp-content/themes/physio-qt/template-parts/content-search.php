<?php
/**
 * Template part for displaying results in search pages.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package physio-qt
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'clearfix' ); ?>>
	<header class="hentry--header">
		<?php the_title( sprintf( '<h2 class="entry-title hentry--title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>
	</header><!-- .entry-header -->

	<div class="hentry--content">
		<?php the_excerpt(); ?>
	</div><!-- .entry-summary -->

</article><!-- #post-## -->
