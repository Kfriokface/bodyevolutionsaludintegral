<?php
/**
 * One click demo import functions
 *
 * @package physio-qt
 */

/**
 *  Define all files that need to be imported
 */
function physio_qt_import_files() {
    return array(
        array(
            'import_file_name'             => 'Physio',
            'local_import_file'            => get_theme_file_path( '/demo-files/content.xml' ),
            'local_import_widget_file'     => get_theme_file_path( '/demo-files/widgets.json' ),
            'local_import_customizer_file' => get_theme_file_path( '/demo-files/customizer.dat' ),
            'import_preview_image_url'     => get_theme_file_uri( 'screenshot.png' ),
            'import_notice'                => sprintf( esc_html__( 'Please use the demo importer only on a clean installation. Use the %1s plugin to clean the installation (this will delete all content)', 'physio-qt' ), '<a href="https://wordpress.org/plugins/wp-reset/" target="blank">WordPress Reset</a>' ),
            'preview_url'                  => 'https://demos.qreativethemes.com/physio',
        ),
    );
}
add_filter( 'pt-ocdi/import_files', 'physio_qt_import_files' );

/**
 *  Filters
 */
add_filter( 'pt-ocdi/regenerate_thumbnails_in_content_import', '__return_false' );
add_filter( 'pt-ocdi/disable_pt_branding', '__return_true' );

/**
 *  After import setup
 */
function physio_qt_after_import_setup() {

    // Menus to Import and assign
	$top_menu 	   = get_term_by('name', 'Topbar Navigation', 'nav_menu');
	$main_menu	   = get_term_by('name', 'Main Navigation', 'nav_menu');
	$services_menu = get_term_by('name', 'Services Navigation', 'nav_menu');
	$footer_menu   = get_term_by('name', 'Footer Navigation', 'nav_menu');

	set_theme_mod( 'nav_menu_locations', array(
			'top-nav' 		=> $top_menu->term_id,
			'primary'		=> $main_menu->term_id,
			'services-nav'	=> $services_menu->term_id,
			'footer-nav' 	=> $footer_menu->term_id
		)
	);

	// Assign front page and blog page
    $front_page_id = get_page_by_title( 'Home' );
    $blog_page_id  = get_page_by_title( 'Blog' );

    update_option( 'show_on_front', 'page' );
    update_option( 'page_on_front', $front_page_id->ID );
    update_option( 'page_for_posts', $blog_page_id->ID );

	// Update Booked Plugin default colors
	update_option( 'booked_light_color','#9a65a5' );
	update_option( 'booked_dark_color','#535961' );
	update_option( 'booked_button_color','#9560a0' );

	// Empty default breadcrumbs seperator
	add_option( 'bcn_options', array( 'hseparator' => '' ) );

	// Set the logo in the customizer on import
	set_theme_mod( 'logo', get_theme_file_uri( '/assets/images/logo.png' ) );

	// Set 404 image in the customizer on import
	set_theme_mod( 'qt_404_page_image', get_theme_file_uri( '/assets/images/404.png' ) );

	// Set the featured button text in the customizer
	set_theme_mod( 'featured_button_text', 'Book Appointment' );
	set_theme_mod( 'featured_button_url', '#' );

	// Set the bottom footer text in the customizer
	set_theme_mod( 'bottom_footer_left', 'Copyright 2020 Physio WP by QreativeThemes' );
	set_theme_mod( 'bottom_footer_right', 'Change text via Customize > Theme Options > Bottom Footer' );

	// Set Font Awesome version to 5.0 to new imports
	set_theme_mod( 'qt_fontawesome_version', '5' );
	set_theme_mod( 'qt_fontawesome_fallback', 'disable' );
}
add_action( 'pt-ocdi/after_import', 'physio_qt_after_import_setup' );