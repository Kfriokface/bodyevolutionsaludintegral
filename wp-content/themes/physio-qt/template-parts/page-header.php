<?php
/**
 * Page Header Template Part
 *
 * @package physio-qt
 */

$physio_head_tag 	= is_single() ? 'h2' : 'h1';
$physio_get_the_id 	= physio_qt_get_the_ID();
?>

<?php if ( 'hide' !== get_theme_mod( 'page_header', 'show' ) && 'hide' !== get_field( 'page_header', $physio_get_the_id ) ) : ?>
	<div class="page-header">
		<div class="container">
			<div class="page-header--wrap">

				<?php
					$physio_subtitle = '';

					if ( is_home() || ( is_single() && 'post' === get_post_type() ) ) {
						
						$physio_title = get_the_title( $physio_get_the_id );
						$physio_subtitle = get_field( 'subtitle', $physio_get_the_id );

						// Set no title if page_for_post option is not set for frontpage, blog and single posts
						if ( ! $physio_get_the_id ) {
							$physio_title = esc_html__( 'Blog', 'physio-qt' );
						}

					} elseif ( physio_qt_woocommerce_active() && is_woocommerce() ) {

						ob_start();
						woocommerce_page_title();
						$physio_title    = ob_get_clean();
						$physio_subtitle = get_field( 'subtitle', (int) $physio_get_the_id );

					} elseif ( is_category() || is_tag() || is_author() || is_year() || is_month() || is_day() || is_tax() ) {
						
						$physio_title = get_the_archive_title();

					} elseif ( is_search() ) {
						
						$physio_title = esc_html__( 'Search Results For', 'physio-qt' ) . ' &quot;' . get_search_query() . '&quot;';

					} elseif ( is_404() ) {
						
						$physio_title = esc_html__( 'Error 404', 'physio-qt');

					} else {
						
						$physio_title = get_the_title();
						$physio_subtitle = get_field( 'subtitle' );
					}
				?>

				<?php if ( 'hide' !== get_field( 'display_page_title', $physio_get_the_id ) ) : ?>
					<<?php echo esc_html( $physio_head_tag ); ?> class="page-header--title"><?php echo wp_kses_post( $physio_title ); ?></<?php echo esc_html( $physio_head_tag ); ?>>
				<?php endif; ?>

				<?php if ( $physio_subtitle ): ?>
					<h3 class="page-header--subtitle"><?php echo wp_kses_post( $physio_subtitle ); ?></h3>
				<?php endif;?>

			</div>
		</div>
	</div>
<?php endif; ?>