<?php
/**
 * Template part for displaying single posts.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package physio-qt
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'clearfix' ); ?>>
	<?php if ( has_post_thumbnail() ) : ?>
		<div class="hentry--post-thumbnail">
			<a href="<?php esc_url( the_permalink() ); ?>" class="hentry--thumbnail">
				<?php the_post_thumbnail( 'post-thumbnail', array( 'class' => 'img-responsive' ) ); ?>
			</a>
		</div>
	<?php endif; ?>

	<div class="hentry--content">
		<?php if ( 'post' === get_post_type() && 'show' === get_theme_mod( 'blog_metadata', 'show' ) ) : ?>
			<div class="hentry--meta-data">
				<span class="vcard author meta--author"><span class="fn">
					<?php echo get_theme_mod( 'blog_written_by', esc_html__( 'By ', 'physio-qt' ) ); ?> <?php the_author(); ?>
				</span></span>
				<span class="meta--seperator"></span>
				<span class="post-date updated meta-data--date">
					<time datetime="<?php the_time( 'c' ); ?>"><?php echo esc_attr( get_the_date() ); ?></time>
				</span>
				<?php if ( comments_open() ) : ?>
					<span class="meta--seperator"></span>
					<span class="meta--comments"><a href="<?php echo esc_url( comments_link() ); ?>"><?php echo esc_attr( get_comments_number() ); ?> <?php echo esc_html_e( 'Comments', 'physio-qt' ); ?></a></span>
				<?php endif; ?>
				<?php if ( has_category() ) : ?>
					<span class="meta--seperator"></span>
					<span class="meta--categories"><?php esc_html_e( '' , 'physio-qt' ); ?> <?php the_category( ', ' ); ?></span>
				<?php endif; ?>
				<?php if ( has_tag() ) : ?>
					<span class="meta--seperator"></span>
					<span class="meta--tags"><?php esc_html_e( '' , 'physio-qt' ); ?> <?php the_tags( '', ', ' ); ?></span>
				<?php endif; ?>
			</div>
		<?php endif; ?>

		<?php the_title( '<h1 class="entry-title hentry--title">', '</h1>' ); ?>
		<?php the_content(); ?>

	</div>

</article>
