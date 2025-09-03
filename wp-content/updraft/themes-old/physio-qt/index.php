<?php
/**
 * The main template file.
 *
 * @package physio-qt
 */

get_header();

// Set the sidebar to right
$physio_sidebar = get_field( 'sidebar', (int) get_option( 'page_for_posts' ) );
if ( ! $physio_sidebar ) :
	$physio_sidebar = 'right';
endif;

// Set the sidebar pull to no pull
$physio_sidebar_pull = get_field( 'pull_sidebar', (int) get_option( 'page_for_posts' ) );
if ( ! $physio_sidebar_pull || 'hide' === $physio_sidebar ) :
	$physio_sidebar_pull = 'no_pull';
endif;

// Get the blog layout option
$physio_blog_layout = physio_qt_blog_layout();

// Get the blog grid column option
$physio_grid_columns = get_theme_mod( 'blog_columns', '2' );

// Get the page header template
get_template_part( 'template-parts/page-header' );

// Get the breadcrumbs page option
$physio_breadcrumbs = get_field( 'breadcrumbs', (int) get_option( 'page_for_posts' ) );

// Get the correct blog sidebar name
$sidebar_name = ( is_active_sidebar( 'blog-sidebar' ) ) ? 'blog-sidebar' : 'sidebar-1';

if ( 'pull' !== $physio_sidebar_pull && 'hide' !== get_theme_mod( 'breadcrumbs', 'show' ) && 'hide' !== $physio_breadcrumbs ) : ?>
	<div class="breadcrumbs">
		<div class="container">
			<?php get_template_part( 'template-parts/breadcrumbs' ); ?>
		</div>
	</div>
<?php endif; ?>

<div id="primary" class="content-area">
	<div class="container">
		<div class="row">
				
			<main id="main" class="content col-xs-12<?php echo ( is_active_sidebar( $sidebar_name ) && 'left' === $physio_sidebar ) ? ' col-md-9 col-md-push-3' : ''; echo ( is_active_sidebar( $sidebar_name ) && 'right' === $physio_sidebar ) ? ' col-md-9' : ''; ?>">

				<?php if ( 'no_pull' !== $physio_sidebar_pull && 'hide' !== get_theme_mod( 'breadcrumbs', 'show' ) && 'hide' !== $physio_breadcrumbs ) : ?>
					
					<div class="breadcrumbs">
						<?php get_template_part( 'template-parts/breadcrumbs' ); ?>
					</div>

				<?php endif; ?>

				<?php if ( 'default' == $physio_blog_layout ) : ?>

					<div class="blog-list">

				<?php elseif ( 'grid' == $physio_blog_layout ) : ?>

					<div class="blog-grid columns-<?php echo esc_attr( $physio_grid_columns ); ?>">

				<?php endif; ?>

					<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
						
						<div class="hentry--border">

							<?php if ( 'default' === $physio_blog_layout ) : ?>
								
								<?php get_template_part( 'template-parts/content', get_post_format() ); ?>

							<?php elseif ( 'grid' === $physio_blog_layout ) : ?>

								<?php get_template_part( 'template-parts/blog-grid' ); ?>

							<?php endif; ?>

						</div>

						<?php endwhile;

						// Previous/next page navigation.
						the_posts_pagination( array(
							'prev_text' => '<i class="fa fas fa-angle-left"></i>',
							'next_text' => '<i class="fa fas fa-angle-right"></i>',
						) );

					else :

						get_template_part( 'template-parts/content', 'none' );

					endif; ?>

				</div>
			</main>

			<?php if ( 'hide' !== $physio_sidebar && is_active_sidebar( $sidebar_name ) ) : ?>
				<div class="col-xs-12 col-md-3<?php echo 'left' === $physio_sidebar ? ' col-md-pull-9' : ''; ?>">
					<aside class="sidebar<?php echo 'pull' === $physio_sidebar_pull ? ' pull--sidebar' : ''; ?>">
						<?php dynamic_sidebar( $sidebar_name ); ?>
					</aside>
				</div>
			<?php endif; ?>
			
		</div>
	</div>
</div>

<?php get_footer(); ?>