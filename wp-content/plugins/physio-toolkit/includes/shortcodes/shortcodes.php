<?php

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/*--------------------------------------------------------------------------------------
 * Icon Shortcode
 *-------------------------------------------------------------------------------------*/
function physio_toolkit_fa( $atts, $content = null ) {
	extract(shortcode_atts( array(
		'icon'        => 'fa-home',
		'href'        => '',
		'target'      => '_self',
        'color'       => '',
        'font_size'   => '',
	), $atts ) );

	$styles = array();

    // Text color
    if ( $color ) {
        $styles[] = 'color: ' . esc_attr( $color ) . ';';
    }

    // Font size
    if ( $font_size ) {
        $styles[] = 'font-size: ' . esc_attr( $font_size ) . 'px;';
    }

    // Create string from array
    $styles = implode( '', $styles );

    // Create style tag
    if ( $styles ) {
        $styles = wp_kses( $styles, array() );
        $styles = ' style="' . esc_attr( $styles ) . '"';
    }

    // Add fallback for FontAwesome 4 icons
    if ( '4' == get_theme_mod( 'qt_fontawesome_version' ) && 'fa ' != substr( $icon, 0, 3 ) ) {
        $icon = 'fa ' . $icon;
    }

    if ( empty( $href ) ) {
        return '<span class="icon-wrap"'. wp_kses_post( $styles ) .'><i class="' . strtolower( $icon ) . '"></i></span>';
    } else {
        return '<a class="icon-wrap" href="' . esc_url( $href ) . '" target="' . esc_attr( $target ) . '"'. wp_kses_post( $styles ) .'><i class="' . strtolower( $icon ) . '"></i></a>';
    }
}
add_shortcode( 'fa', 'physio_toolkit_fa' );

/*--------------------------------------------------------------------------------------
 * Button Shortcode
 *-------------------------------------------------------------------------------------*/
function physio_toolkit_button( $atts , $content = null ) {
	extract( shortcode_atts( array(
		'style'         => 'primary', 'outline',
		'icon'          => '',
		'href'          => '',
		'target'        => '_self',
        'fullwidth'     => '',
        'edges'         => '',
        'color'         => '',
        'background'    => '',
        'border_color'  => '',
        'custom_class'  => '',
	), $atts ) );

    $styles = array();

    // Text color
    if ( $color ) {
        $styles[] = 'color: ' . esc_attr( $color ) . ';';
    }

    // Background color
    if ( $background ) {
        $styles[] = 'background-color: ' . esc_attr( $background ) . ';';
    }

    // Border color
    if ( $border_color ) {
        $styles[] = 'border-color: ' . esc_attr( $border_color ) . ';';
    }

    // Create string from array
    $styles = implode( '', $styles );

    // Create style tag for the button
    if ( $styles ) {
        $styles = wp_kses( $styles, array() );
        $styles = ' style="' . esc_attr( $styles ) . '"';
    }

    // Create classes array
    $classes = array();

    $classes[] = $style;

    // Fullwidth
    if ( 'true' == $fullwidth ) {
        $classes[] = 'fullwidth';
    }

    // Rounded edges
    if ( 'rounded' == $edges ) {
        $classes[] = 'rounded';
    }

    // Custom class
    if ( $custom_class ) {
        $classes[] = $custom_class;
    }

    // Add fallback for FontAwesome 4 icons
    if ( '4' == get_theme_mod( 'qt_fontawesome_version' ) && 'fa ' != substr( $icon, 0, 3 ) ) {
        $icon = 'fa ' . $icon;
    }

    // Create string from array
    $classes = implode( ' ', $classes );

    return '<a href="' . esc_url( $href ) . '" class="btn btn-' . esc_attr( strtolower( $classes ) ) . '" target="' . esc_attr( $target ) . '" '. wp_kses_post( $styles ) .'>' . ( empty( $icon )  ? '' : '<i class="' . $icon . '"></i>' ) . $content . '</a>';
}
add_shortcode( 'button', 'physio_toolkit_button' );

/*--------------------------------------------------------------------------------------
 * Table Shortcode
 *-------------------------------------------------------------------------------------*/
function physio_toolkit_table( $atts, $content = null ) {
    extract( shortcode_atts( array(
        'cols' => 'none',
        'data' => 'none',
        'style' => 'default'
    ), $atts ) );

    $cols  = explode(',',$cols);
    $data  = explode(',',$data);
    $total = count($cols);
    
    $output = '<table class="qt-table ' . esc_attr( strtolower( $style ) ) . '"><thead>';
    
    foreach( $cols as $col ) {
        $output .= '<td>' . $col . '</td>';
    }
   
    $output .= '</thead><tr>';
    $counter = 1;
   
    foreach( $data as $datum ) {
        $output .= '<td>' . $datum . '</td>';
        if($counter%$total==0) {
            $output .= '</tr>';
        }
        $counter++;
    }

    $output .= '</table>';
    return $output;
}
add_shortcode( 'table', 'physio_toolkit_table' );

 /*--------------------------------------------------------------------------------------
 * Collapse (Accordion) Shortcode
 * @author Filip Stefansson
 * @see https://goo.gl/wTWkA4
 *-------------------------------------------------------------------------------------*/
function physio_toolkit_collapsibles( $atts, $content = null ) {

    if ( isset( $GLOBALS['collapsibles_count'] ) ) {
        $GLOBALS['collapsibles_count']++;
    } else {
        $GLOBALS['collapsibles_count'] = 0;
    }

    $defaults = array();
    extract( shortcode_atts( $defaults, $atts ) );

    // Extract the tab titles for use in the tab widget.
    preg_match_all( '/collapse title="([^\"]+)"/i', $content, $matches, PREG_OFFSET_CAPTURE );
    $tab_titles = array();
    
    if ( isset( $matches[1] ) ) {
        $tab_titles = $matches[1];
    }
    $output = '';
    
    if ( count( $tab_titles ) ) {
        $output .= '<div class="panel-group" id="accordion-' . $GLOBALS['collapsibles_count'] . '" data-panel="accordion-' . $GLOBALS['collapsibles_count'] . '">';
        $output .= do_shortcode( $content );
        $output .= '</div>';
    } else {
        $output .= do_shortcode( $content );
    }

    return $output;
}
add_shortcode( 'collapsibles', 'physio_toolkit_collapsibles' );
 
function physio_toolkit_collapse( $atts, $content = null ) {

    if ( ! isset($GLOBALS['current_collapse'] ) ) {
        $GLOBALS['current_collapse'] = 0;
    } else {
        $GLOBALS['current_collapse']++;
    }

    extract( shortcode_atts( array(
        "title"     => '',
        "active"    => '',
        "state"     => false,
        "id"        => '',
    ), $atts ) );

    // Check if panel is set to active
    if ( $state == "active" ) {
        $state = 'in';
        $active = 'active';
    }

    // Check if custom id isset
    if ( $id != '' ) {
        $id = strtolower( str_replace( ' ','-', $id ) );
    } else {
        $id = 'collapse_'. $GLOBALS['current_collapse'];
    }

    $output = '';
    $output .= '<div class="panel">';
    $output .= '<div class="panel-heading">';
    $output .= '<h3 class="panel-title">';
    $output .= '<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion-' . $GLOBALS['collapsibles_count'] . '" href="#'. esc_attr( $id ) .'" ' . ( ! empty( $active ) ? 'aria-expanded="true"' : '' ) . '>';
    $output .= $title;
    $output .= '</a>';
    $output .= '</h3>';
    $output .= '</div>';
    $output .= '<div id="'. esc_attr( $id ) .'" class="panel-collapse collapse ' . esc_attr( $state ) . '">';
    $output .= '<div class="panel-body">' . do_shortcode( $content ) . ' </div>';
    $output .= '</div>';
    $output .= '</div>';

    return $output;
}
add_shortcode( 'collapse', 'physio_toolkit_collapse' );

/*--------------------------------------------------------------------------------------
 * Dropcap Shortcode
 *-------------------------------------------------------------------------------------*/
function physio_toolkit_dropcap( $atts, $content = null ) {
    extract( shortcode_atts( array(
        'style'  => 'style1',
        'title'  => ''
    ), $atts ) );

    $outpout = '';

    if ( ! empty( $title ) ) :
        $output = '<div class="dropcap-wrap"><div class="dropcap-pull"><span class="dropcap ' . esc_attr( strtolower( $style ) ) .'">' . $content . '</span></div><span class="dropcap-title"> ' . $title .'</span></div>';
    else :
        $output = '<span class="dropcap ' . esc_attr( strtolower( $style ) ) .'">' . $content . '</span>';
    endif;

    return $output;
}
add_shortcode( 'dropcap', 'physio_toolkit_dropcap' );