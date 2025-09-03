<?php
/**
 * Jumbotron Template Part
 *
 * @package physio-qt
 */

// Carousel pause on hover
$carousel_pause_hover = ( true != get_field( 'pause_hover' ) ) ? 'null' : 'hover';

// Built the outer wrapper classes
$slider_classes = array();
$slider_classes[] = get_field( 'slide_animation' );
$slider_classes[] = ( true != get_field( 'enable_touch_support' ) ) ? '' : 'carousel-touch';
$slider_classes[] = ( true != get_field( 'slide_fixed_height' ) ) ? '' : 'fixed-height';
$slider_classes = implode( ' ',  array_filter( $slider_classes ) );
?>

<div id="jumbotron-fullwidth" class="jumbotron carousel slide <?php echo esc_attr( $slider_classes ); ?>" data-ride="carousel" <?php printf( 'data-interval="%s"', get_field( 'slide_autocycle' ) ? get_field( 'slide_interval' ) : 'false' ); ?> data-pause="<?php echo esc_attr( $carousel_pause_hover ); ?>">

    <div class="carousel-inner">

        <?php $physio_qt_count_slides = count( get_field( 'slides' ) ); ?>

        <?php if ( $physio_qt_count_slides > 1 ) : ?>
            <a class="carousel-control left" href="#jumbotron-fullwidth" role="button" data-slide="prev"><i class="fa fa-caret-left"></i></a>
            <a class="carousel-control right" href="#jumbotron-fullwidth" role="button" data-slide="next"><i class="fa fa-caret-right"></i></a>
        <?php endif; ?>

        <?php 
            $i = -1;
            while ( have_rows( 'slides' ) ) : 
                the_row();
                $i++;

                // Get the image
                $get_slide_image = get_sub_field( 'slide_image' );

                // Get the image meta
                $get_slide_image_alt = get_post_meta( $get_slide_image, '_wp_attachment_image_alt', true );

                // Check if image alt text is added, else display slide heading as alt
                if ( '' == $get_slide_image_alt ) :
                    $get_slide_image_alt = strip_tags( get_sub_field( 'slide_heading' ) );
                endif;

                // Get the url for the img src
                $slide_image = wp_get_attachment_image_src( $get_slide_image, 'physio-qt-slider-l' );

                // Get the srcset images
                $slide_image_srcset = physio_qt_srcset_sizes( $get_slide_image, array( 'physio-qt-slider-s', 'physio-qt-slider-m', 'physio-qt-slider-l' ) );

                // Get the caption option field
                $slide_caption = get_field( 'slide_captions' );

                // Get the caption alignment field
                $slide_caption_align = get_field( 'slide_caption_alignment' );

                if ( '' != get_sub_field( 'slide_caption_align' ) ) {
                    $slide_caption_align = get_sub_field( 'slide_caption_align' );
                }
                
                // Get the link url field
                $slide_link = get_sub_field( 'slide_link' );

                // Get the link target field
                $slide_link_target = get_sub_field( 'slide_link_target' );

                // Get the slide headig tag
                if ( get_sub_field( 'slide_heading_tag' ) ) {
                    $slide_heading_tag = get_sub_field( 'slide_heading_tag' );
                } else {
                    $slide_heading_tag = 'h1';
                }
            ?>

            <div class="item slide-id-<?php echo esc_attr( get_row_index() ); ?><?php echo 0 === $i ? ' active' : ''; ?>">
                <?php if ( ! empty( $slide_link ) && 'no_captions' === $slide_caption ) : ?>
                    <a href="<?php echo esc_url( $slide_link ); ?>"<?php echo ( 'yes' === $slide_link_target ) ? ' target="_blank"' : ''; ?>>
                <?php endif; ?>
                <div class="slide-image">
                    <?php echo wp_get_attachment_image( $get_slide_image, 'physio-qt-slider-l', false, array( 'alt' => esc_attr( $get_slide_image_alt ), 'srcset' => esc_html( $slide_image_srcset ), 'sizes' => '100vw' ) ); ?>
                </div>
                <?php if ( ! empty( $slide_link ) && 'no_captions' === $slide_caption ) : ?>
                    </a>
                <?php endif; ?>
               
                <?php if ( 'use_captions' === $slide_caption && ( get_sub_field( 'slide_small_heading' ) || get_sub_field( 'slide_heading' ) || get_sub_field( 'slide_content' ) ) ) : ?>
                    <div class="container">
                        <div class="jumbotron-caption <?php echo esc_attr( $slide_caption_align ); ?>">
                            <?php if( get_sub_field( 'slide_small_heading' ) ) : ?>
                                <span class="caption-small-heading"><?php the_sub_field( 'slide_small_heading' ); ?></span>
                            <?php endif; ?>
                            <?php if( get_sub_field( 'slide_heading' ) ) : ?>
                                <div class="caption-heading"><<?php echo esc_attr( $slide_heading_tag ); ?> class="caption-heading-tag"><?php echo the_sub_field( 'slide_heading' ); ?></<?php echo esc_attr( $slide_heading_tag ); ?>></div>
                                <?php endif; ?>
                            <?php if( get_sub_field( 'slide_content' ) || have_rows( 'slide_buttons' ) ) : ?>
                                <div class="caption-content">
                                    <?php
                                        the_sub_field( 'slide_content' );
                                        while ( have_rows( 'slide_buttons' ) ) :
                                            the_row();
                                            // Get the slide button link
                                            $slide_button_link = get_sub_field( 'slide_button_link' );
                                            // Get the slide button text
                                            $slide_button_text = get_sub_field( 'slide_button_text' );
                                            // Get the slide button style
                                            $slide_button_style = get_sub_field( 'slide_button_style' );
                                            ?>
                                            <a href="<?php echo esc_url( $slide_button_link ); ?>" class="btn btn-<?php echo esc_attr( $slide_button_style ); ?>"><?php echo esc_html( $slide_button_text ); ?></a>
                                            <?php
                                        endwhile;
                                    ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        
        <?php endwhile; ?>

    </div>
</div>