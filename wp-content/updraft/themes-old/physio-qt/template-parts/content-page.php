<?php
/**
 * Template part for displaying page content in page.php.
 *
 * @package physio-qt
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'clearfix' ); ?>>
	<div class="hentry--content">
		<?php the_content(); ?>
		<div class="clearfix"></div>
		<?php
			wp_link_pages( array(
				'before'      => '<div class="pagination"><span class="page-links-title">' . esc_html__( 'Pages:', 'physio-qt' ) . '</span>',
				'after'       => '</div>',
				'link_before' => '<span>',
				'link_after'  => '</span>',
			) );
		?>
	</div>
</article>