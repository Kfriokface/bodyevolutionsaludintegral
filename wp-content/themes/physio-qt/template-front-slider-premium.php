<?php
/*
Template Name: Front Page Alternative Slider
*/

get_header();
?>

<div class="theme-alt-slider <?php echo ( 'no_overlay' === get_theme_mod( 'header_widgets_absolute', 'overlay' ) ) ? 'header-widgets-no-overlay' : '';?>">
	<?php 
		// Get the Revolution Slider & Layer Slider field
		if( 'rev' === get_field( 'use_this_slider' ) && function_exists( 'putRevSlider' ) ) {
			putRevSlider( get_field( 'revolution_slider_id' ) );
		} 
		else if ( 'layer' === get_field( 'use_this_slider' ) && function_exists( 'layerslider' ) ) {
			layerslider( get_field( 'layer_slider_id' ) );
		}
	?>
</div>

<div id="primary" class="content-area">
	<div class="container">
		<?php 
			if ( have_posts() ) : while ( have_posts() ) : the_post();
				the_content();
			endwhile; endif;
		?>
	</div>
</div>

<?php get_footer(); ?>