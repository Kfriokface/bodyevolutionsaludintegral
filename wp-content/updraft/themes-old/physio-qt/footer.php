<?php
/**
 * The template for displaying the footer.
 *
 * @package physio-qt
 */

// Get the top and main footer column settings
$physio_qt_top_footer_columns = (int) get_theme_mod( 'top_footer_columns', 4 );
$physio_qt_main_footer_columns = (int) get_theme_mod( 'main_footer_columns', 4 );

// Get the bottom footer text settings
$physio_qt_bottom_footer_left = get_theme_mod( 'bottom_footer_left' );
$physio_qt_bottom_footer_right = get_theme_mod( 'bottom_footer_right' );
?>

<footer class="footer">

	<div class="footer--main-container">

		<?php if ( $physio_qt_top_footer_columns > 0 && is_active_sidebar( 'top-footer' ) ) : ?>
			<div class="footer--top">
				<div class="container">
					<div class="footer--top-container">
						<div class="row">
							<?php dynamic_sidebar( 'top-footer' ); ?>
						</div>
					</div>
				</div>
			</div>
		<?php endif; ?>

		<?php if ( $physio_qt_main_footer_columns > 0 && is_active_sidebar( 'main-footer' ) ) : ?>
			<div class="footer--middle">
				<div class="container">
					<div class="row">
						<?php dynamic_sidebar( 'main-footer' ); ?>
					</div>
				</div>
			</div>
		<?php endif; ?>

	</div>

	<?php if ( $physio_qt_bottom_footer_left != '' || $physio_qt_bottom_footer_right != '' ) : ?>
		<div class="footer--bottom">
			<div class="container">
				<div class="row">
					<div class="col-xs-12 col-md-6">
						<div class="bottom-left">
							<p><?php echo wp_kses_post( do_shortcode( $physio_qt_bottom_footer_left ) ); ?></p>
						</div>
					</div>
					<div class="col-xs-12 col-md-6">
						<div class="bottom-right">
							<p><?php echo wp_kses_post( do_shortcode( $physio_qt_bottom_footer_right ) ); ?></p>
						</div>
					</div>
				</div>
			</div>
		</div>
	<?php endif; ?>

	<?php if ( 'hide' !== get_theme_mod( 'scroll_to_top_button', 'show' ) ) : ?>
		<a href="#" class="scroll-to-top"><i class="fa fa-caret-up"></i></a>
	<?php endif; ?>

</footer>

</div><!-- end layout boxes -->

<?php wp_footer(); ?>

</body>
</html>