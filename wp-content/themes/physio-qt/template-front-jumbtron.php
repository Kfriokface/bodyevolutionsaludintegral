<?php
/*
Template Name: Front Page Slider
*/

get_header();

// Get the jumbotron template part if the slider has images
if ( have_rows( 'slides' ) ) {
	get_template_part( 'template-parts/jumbotron' );
}
?>

<div id="primary" class="content-area">
	<div class="container">
		<?php
			if ( have_posts() ) :
				while ( have_posts() ) :
					the_post();
					the_content();
				endwhile;
			endif;
		?>
	</div>
</div>

<?php get_footer(); ?>