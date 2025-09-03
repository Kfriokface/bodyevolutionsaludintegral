<?php
/**
 * All theme add_actions & functions
 *
 * @package physio-qt
 */

/**
 * Add admin area notice to install our plugin that contain the theme widgets
 *
 * Updated WordPress requirements from Envato forced us to move the widgets from the theme folder in to a plugin
 *
 * @since 1.8
 *
 */
if ( ! function_exists( 'physio_qt_custom_admin_notice' ) ) {
    function physio_qt_custom_admin_notice() {

        if ( ! class_exists( 'PhysioToolkit' ) ) { ?>
	        <div class="notice notice-error notice-qt">
	        	<h1><?php esc_html_e( 'Required: Install "Physio Toolkit" plugin', 'physio-qt' ); ?></h1>
	            <p>
	            	<?php echo sprintf( 
						esc_html__( 'Due to new WordPress requirements from Envato we are required to move some theme files (widgets and custom fields) to a plugin. %s Installing this plugin is necessary for the theme. The benefit of this plugin is that you won\'t lose any content when switching themes.', 'physio-qt' ),
						'<br>'
					); ?>
	            </p>
	            <p>
	            	<?php echo sprintf( 
						esc_html__( 'Please navigate to %s → %s to install and activate the %s plugin.', 'physio-qt' ),
						'Appearance',
						'<strong><a href="'. esc_url( admin_url( 'themes.php?page=tgmpa-install-plugins' ) ) .'">Install Plugins</a></strong>',
						'<strong>"Physio Toolkit"</strong>'
					); ?>
				</p>
	        </div>
		<?php
		}
    }
    add_action( 'admin_notices', 'physio_qt_custom_admin_notice' );
}

/**
 * Check if WooCommerce is active
 */
if( ! function_exists( 'physio_qt_woocommerce_active' ) ) {
	function physio_qt_woocommerce_active() {
		return class_exists( 'Woocommerce' );
	}
}

/**
 * Return the Google Font URL
 */
if ( ! function_exists( 'physio_qt_font_slug' ) ) {
	function physio_qt_font_slug() {

		$fonts_url = '';
		$fonts = array();

		$fonts = apply_filters( 'pre_google_web_fonts', $fonts );

		foreach ( $fonts as $key => $value ) {
			$fonts[$key] = $key . ':' . implode( ',', $value );
		}

		if ( $fonts ) {
			$query_args = array(
				'family' => urlencode( implode( '|', $fonts ) ),
				'subset' => urlencode( 'latin,latin-ext' ),
			);

			$fonts_url = add_query_arg( $query_args, '//fonts.googleapis.com/css' );
		}
		 
		return $fonts_url;
	}
}

/**
 * Creates the style from the array for the page headers
 */
if ( ! function_exists( 'physio_qt_create_style_array' ) ) {
    function physio_qt_create_style_array( $settings ) {

        // Begin of the style tag
        $array_style = 'style="';
        
        foreach ( $settings as $key => $value ) {

            if( $value ) {
            
                // If background isset add url()
                if( 'background-image' === $key ) {
                    $array_style .= $key . ': url(\'' . esc_url( $value ) . '\'); ';
                }
                else {
                    $array_style .= $key . ': ' . esc_attr( $value ) . '; ';
                }
            }
        }

        // End of the style tag
        $array_style .= '"';

        // Return the array
        return $array_style;
    }
}

/**
 * Get the sizes for the image srcset where used
 */
if( ! function_exists( 'physio_qt_srcset_sizes' ) ) {
	function physio_qt_srcset_sizes( $img_id, $sizes ) {
		$srcset = array();

		foreach ( $sizes as $size ) {
			$img = wp_get_attachment_image_src( $img_id, $size );
			$srcset[] = sprintf( '%s %sw', $img[0], $img[1] ); //
		}

		return implode( ', ' , $srcset );
	}
}

/**
 * Get the correct page/post ID
 */
if( ! function_exists( 'physio_qt_get_the_ID' ) ) {
	function physio_qt_get_the_ID() {

		$get_the_id = get_the_ID();

		if ( is_home() || is_singular( 'post' ) ) {
			$get_page_id = absint( get_option( 'page_for_posts' ) );
			$get_the_id  = $get_page_id;
		}

		if ( physio_qt_woocommerce_active() && is_woocommerce() ) {
			$get_shop_id = absint( get_option( 'woocommerce_shop_page_id' ) );
			$get_the_id  = $get_shop_id;
		}

		return $get_the_id;
	}
}

/**
 * Add all inline styles from the ACF page settings
 */
if ( ! function_exists( 'physio_qt_inline_styles' ) ) {
    function physio_qt_inline_styles() {

    	// Get the page ID
        $get_the_id = physio_qt_get_the_ID();

        // Create array for the settings
        $inline_css = '';

        // Create array for page heading settings
        $page_header_settings = array();

        // Check if page heading align isset
        if ( get_field( 'header_text_align', $get_the_id )  ) {
            $page_header_settings['text-align'] = get_field( 'header_text_align', $get_the_id );
        }

        // Check if page heading background image isset
        if ( get_field( 'header_bg', $get_the_id ) ) {
            $page_header_settings['background-image'] = get_field( 'header_bg', $get_the_id );
            $page_header_settings['background-position-x'] = get_field( 'header_bg_horizontal', $get_the_id );
            $page_header_settings['background-position-y'] = get_field( 'header_bg_vertical', $get_the_id );
            $page_header_settings['background-size'] = get_field( 'header_bg_size', $get_the_id );
            $page_header_settings['background-attachment'] = get_field( 'header_bg_attachment', $get_the_id );
        }

        // Check if page heading backgroud color isset
        if ( get_field( 'header_bg_color', $get_the_id ) ) {
            $page_header_settings['background-color'] = get_field( 'header_bg_color', $get_the_id );
        }

        if ( $page_header_settings ) {
            $inline_css .= '.page-header{';
            foreach ( $page_header_settings as $key => $value ) {
                if ( $value ) {
                    if ( $key != 'background-image' ) {
                        $inline_css .= $key . ': '. esc_attr( $value ) .';';
                    } else {
                        $inline_css .= $key . ': url('. esc_url( $value ) .');';
                    }
                }
            }
            $inline_css .= '}';
        }

        // Check if page title color isset
        $title_settings = array();

        if ( get_field( 'page_title_color', $get_the_id ) ) {
            $title_settings['color'] = get_field( 'page_title_color', $get_the_id );
        }

        if ( $title_settings ) {
            $inline_css .= '.page-header--title{';
            foreach ( $title_settings as $key => $value ) {
                if ( $value ) {
                    $inline_css .= $key . ':'.esc_attr( $value ).';';
                }
            }
            $inline_css .= '}';
        }

        // Check if subtitle color isset
        $subtitle_settings = array();

        if ( get_field( 'subtitle_color', $get_the_id ) ) {
            $subtitle_settings['color'] = get_field( 'subtitle_color', $get_the_id );
        }

        if ( $subtitle_settings ) {
            $inline_css .= '.page-header--subtitle{';
            foreach ( $subtitle_settings as $key => $value ) {
                if ( $value ) {
                    $inline_css .= $key . ':'.esc_attr( $value ).';';
                }
            }
            $inline_css .= '}';
        }

        // Check if fixed height isset for the homepage slider
        if ( true == get_field( 'slide_fixed_height', $get_the_id ) ) {

            if ( get_field( 'slide_height_mobile', $get_the_id ) ) {
                $inline_css .= '
                    @media (max-width:580px) { 
                        .jumbotron .slide-image img {
                            height:'. absint( get_field( 'slide_height_mobile', $get_the_id ) ) .'px;
                        }
                    }
                ';
            }

            if ( get_field( 'slide_height_tablet', $get_the_id ) ) {
                $inline_css .= '
                    @media (min-width:581px) {
                        .jumbotron .slide-image img {
                            height:'. absint( get_field( 'slide_height_tablet', $get_the_id ) ) .'px;
                        }
                    }
                ';
            }

            if ( get_field( 'slide_height_desktop_small', $get_the_id ) ) {
                $inline_css .= '
                    @media (min-width:992px) {
                        .jumbotron .slide-image img {
                            height:'. absint( get_field( 'slide_height_desktop_small', $get_the_id ) ) .'px;
                        }
                    }
                ';
            }

            if ( get_field( 'slide_height_desktop_large', $get_the_id ) ) {
                $inline_css .= '
                    @media (min-width:1200px) {
                        .jumbotron .slide-image img {
                            height:'. absint( get_field( 'slide_height_desktop_large', $get_the_id ) ) .'px;
                        }
                    }
                ';
            }
        }

        if ( have_rows( 'slides', $get_the_id ) ) {

            while ( have_rows( 'slides', $get_the_id ) ) {
                the_row();
                $index = get_row_index();

                // Texts
                $slide_subheading               = get_sub_field( 'slide_subheading_color', $get_the_id );
                $slide_heading                  = get_sub_field( 'slide_heading_color', $get_the_id );
                $slide_content                  = get_sub_field( 'slide_content_color', $get_the_id );
                $slide_buttons                  = get_sub_field( 'button_colors', $get_the_id );

                // Button default
                $slide_btn_color                = $slide_buttons['slide_button_color'];
                $slide_btn_background           = $slide_buttons['slide_button_background'];
                $slide_btn_background_hover     = $slide_buttons['slide_button_background_hover'];

                // Button outline
                $slide_btn_outline_color        = $slide_buttons['slide_button_outline_color'];
                $slide_btn_outline_border       = $slide_buttons['slide_button_outline_border'];
                $slide_btn_outline_border_hover = $slide_buttons['slide_button_outline_border_hover'];

                // Overlay
                $slide_overlay_background       = get_sub_field( 'slide_overlay_color', $get_the_id );
                $slide_overlay_opacity          = get_sub_field( 'slide_overlay_opacity', $get_the_id );

                if ( $slide_subheading ) {

                    $inline_css .= '

                    	.jumbotron .slide-id-'.$index.' .jumbotron-caption .caption-small-heading {
                            color: '. esc_attr( $slide_subheading ) .';
                        }
                    ';
                }

                if ( $slide_heading ) {

                    $inline_css .= '

                        .jumbotron .slide-id-'.$index.' .jumbotron-caption .caption-heading-tag {
                            color: '. esc_attr( $slide_heading ) .';
                        }
                    ';
                }

                if ( $slide_content ) {

                    $inline_css .= '

                        .jumbotron .slide-id-'.$index.' .jumbotron-caption .caption-content p {
                            color: '. esc_attr( $slide_content ) .';
                        }
                    ';
                }

                if ( $slide_btn_color || $slide_btn_background ) {

                    $inline_css .= '

                        .jumbotron .slide-id-'.$index.' .btn.btn-primary {
                            ';

                            if ( $slide_btn_color ) {

                                $inline_css .= '
                                    color: '. esc_attr( $slide_btn_color ) .';
                                ';
                            }

                            if ( $slide_btn_background ) {
                                
                                $inline_css .= '
                                    background-color: '. esc_attr( $slide_btn_background ) .';
                                ';
                            }

                            $inline_css .= '
                        }
                    ';
                }

                if ( $slide_btn_background_hover ) {

                    $inline_css .= '
                        
                        .jumbotron .slide-id-'.$index.' .btn.btn-primary:hover {
                            background-color: '. esc_attr( $slide_btn_background_hover ) .';
                        }
                    ';
                }

                if ( $slide_btn_outline_color || $slide_btn_outline_border ) {

                    $inline_css .= '

                        .jumbotron .slide-id-'.$index.' .btn.btn-outline {
                            ';

                            if ( $slide_btn_outline_color ) {
                                
                                $inline_css .= '
                                    color: '. esc_attr( $slide_btn_outline_color ) .';
                                ';
                            }

                            if ( $slide_btn_outline_border ) {
                                
                                $inline_css .= '
                                    border-color: '. esc_attr( $slide_btn_outline_border ) .';
                                ';
                            }

                            $inline_css .= '
                        }
                    ';
                }

                if ( $slide_btn_outline_border_hover ) {

                    $inline_css .= '
                        
                        .jumbotron .slide-id-'.$index.' .btn.btn-outline:hover {
                            border-color: '. esc_attr( $slide_btn_outline_border_hover ) .';
                        }
                    ';
                }

                if ( $slide_overlay_background || $slide_overlay_opacity ) {

                    $inline_css .= '
                    
                        .jumbotron .slide-id-'.$index.' .slide-image::before {
                            opacity: '. esc_attr( $slide_overlay_opacity ) .';
                            background-color: '. esc_attr( $slide_overlay_background ) .';
                        }
                    ';
                }
            }
        }

        return preg_replace( '/\s+/S', " ", $inline_css );
    }
}

/**
 *  Get the correct blog layout
 */
if ( ! function_exists( 'physio_qt_blog_layout' ) ) {
    function physio_qt_blog_layout() {

        $blog_layout = get_theme_mod( 'blog_layout', 'default' );

        if ( isset( $_REQUEST['layout'] ) && $_REQUEST['layout'] == 'grid' ) {
            $blog_layout = 'grid';
        }

        return $blog_layout;
    }
}

/**
 *  Custom excerpt length option for the blog posts page
 */
if ( ! function_exists( 'physio_qt_custom_excerpt' ) ) {
    function physio_qt_custom_excerpt() {

    	$custom_length = get_theme_mod( 'blog_excerpt_length' );

        if ( $custom_length != '' ) {
			
			if ( has_excerpt() ) {
				$excerpt = get_the_excerpt();
			} else {
				$excerpt = get_the_content();
			}

			$excerpt = wp_trim_words( $excerpt, absint( $custom_length ), '...' );
			echo '<p>'. wp_kses_post( $excerpt ) .'</p>';
			echo esc_url( physio_qt_read_more_link() );

		} else {

			if ( has_excerpt() ) {
				the_excerpt();
			} else {
				the_content();
			}
		}
    }
}

/**
 * Fire the wp_body_open action.
 *
 * No theme prefix here because this is a backwards compatibility function to support WordPress versions prior to 5.2.0.
 */
if ( ! function_exists( 'wp_body_open' ) ) {
    function wp_body_open() {
        do_action( 'wp_body_open' );
    }
}