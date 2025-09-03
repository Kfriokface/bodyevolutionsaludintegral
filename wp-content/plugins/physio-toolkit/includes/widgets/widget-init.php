<?php
/**
 * Get and register custom widgets
 *
 * @package physio-toolkit
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Get all widget files
$physio_toolkit_get_widgets = array(
	'widget-brand-image',
	'widget-brochure',
	'widget-counter',
	'widget-cta-banner',
	'widget-cta-button',
	'widget-facebook',
	'widget-featured-page',
	'widget-google-map-iframe',
	'widget-icon-box',
	'widget-opening-hours',
	'widget-recent-posts-block',
	'widget-single-testimonial',
	'widget-social-icons',
	'widget-team-member',
	'widget-testimonials'
);

foreach ( $physio_toolkit_get_widgets as $widget_file ) {
	require_once( trailingslashit( plugin_dir_path( __FILE__ ) ) . 'files/' . $widget_file . '.php' );
}

// Register all widgets
if ( ! function_exists( 'physio_toolkit_register_widgets' ) ) {
	function physio_toolkit_register_widgets() {

		// All widgets have the QT_ prefix because the theme is used since 2016 with this prefix
		// Changing this prefix is not possible and will break all widgets on customer websites

		// Define all theme widgets
		$physio_toolkit_register_widgets = apply_filters( 'physio-toolkit-register-widgets', array(
			'QT_Brand_Image',
			'QT_Brochure',
			'QT_Counter',
			'QT_Call_To_Action',
			'QT_CTA_Button',
			'QT_Facebook',
			'QT_Feature_Page',
			'QT_Google_Map_Iframe',
			'QT_Icon_Box',
			'QT_Opening_Hours',
			'QT_Recent_Posts_Block',
			'QT_Single_Testimonial',
			'QT_Social_Icons',
			'QT_Team_Member',
			'QT_Testimonials'
		) );

		foreach ( $physio_toolkit_register_widgets as $widget_name ) {
			register_widget( $widget_name );
		}
	}
	add_action( 'widgets_init', 'physio_toolkit_register_widgets' );
}