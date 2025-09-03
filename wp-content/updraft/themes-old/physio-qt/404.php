<?php
/**
 * The template for displaying 404 pages (not found).
 *
 * @package physio-qt
 */

get_header();

// Get the page header template
get_template_part( 'template-parts/page-header' );

// 404 Page customizer settings
$error_logo = get_theme_mod( '404_page_image', get_theme_file_uri( '/assets/images/404.png' ) );
$error_text_title = get_theme_mod( '404_page_text_title', 'Oops! That page can\'t be found' );
$error_text = get_theme_mod( '404_page_text', 'It looks like nothing was found at this location. Maybe try a search below?' );
$error_search = get_theme_mod( '404_page_search', 'show' );
?>

<div id="primary" class="content-area container">
	<div class="row">
			
		<main id="main" class="content col-xs-12">

			<?php if ( 'hide' !== get_theme_mod( 'breadcrumbs', 'show' ) && 'hide' !== get_field( 'breadcrumbs' ) ) : ?>
				<div class="breadcrumbs">
					<div class="container">
						<?php get_template_part( 'template-parts/breadcrumbs' ); ?>
					</div>
				</div>
			<?php endif; ?>

			<?php if ( ! empty( $error_logo ) ) : ?>
				<img src="<?php echo esc_url( $error_logo ); ?>" alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>" />
			<?php endif; ?>

			<?php if ( ! empty( $error_text_title ) ) : ?>
				<h1><?php echo wp_kses_post( $error_text_title); ?></h1>
			<?php endif; ?>

			<?php if ( ! empty( $error_text ) ) : ?>
				<p><?php echo wp_kses_post( $error_text ); ?></p>
			<?php endif; ?>
			
			<?php if ( 'show' === $error_search ) : ?>
				<?php get_search_form(); ?>
			<?php endif; ?>
		</main>

	</div>
</div>

<?php get_footer(); ?>