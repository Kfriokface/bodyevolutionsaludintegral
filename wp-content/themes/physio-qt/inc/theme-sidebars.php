<?php
/**
 * Register sidebars for widgets
 *
 * @package physio-qt
 */

function physio_qt_sidebars() {

	// Set id back to sidebar-1 for new theme installations
	$sidebar_name = ( is_active_sidebar( 'blog-sidebar' ) ) ? 'blog-sidebar' : 'sidebar-1';
	
	// Blog Sidebar
	register_sidebar( array(
		'name'          => esc_html__( 'Blog Sidebar', 'physio-qt' ),
		'description'   => esc_html__( 'Widgets for the blog sidebar', 'physio-qt' ),
		'id'            => $sidebar_name,
		'before_widget' => '<div class="widget %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h4 class="widget-title">',
		'after_title'   => '</h4>',
	) );

	// Topbar - Left
	register_sidebar( array(
		'name'          => esc_html__( 'Topbar - Left Side', 'physio-qt' ),
		'description'   => esc_html__( 'Widgets for the left topbar side. Icon box, social, menu or text widget only is recommended. If no widgets are added the site tagline under Settings > Reading will be displayed', 'physio-qt' ),
		'id'            => 'topbar-sidebar-left',
		'before_widget' => '<div class="widget %2$s">',
		'after_widget'  => '</div>',
	) );

	// Topbar - Right
	register_sidebar( array(
		'name'          => esc_html__( 'Topbar - Right Side', 'physio-qt' ),
		'description'   => esc_html__( 'Widgets for the right topbar side. Icon box, social, menu or text widget only is recommended. If no widgets are added the default topbar menu under Appearance > Menu\'s will be displayed', 'physio-qt' ),
		'id'            => 'topbar-sidebar',
		'before_widget' => '<div class="widget %2$s">',
		'after_widget'  => '</div>',
	) );

	// Header
	register_sidebar( array(
		'name'          => esc_html__( 'Header', 'physio-qt' ),
		'description'   => esc_html__( 'Contact details in the header. Icon box and social widget only is recommended.', 'physio-qt' ),
		'id'            => 'header-sidebar',
		'before_widget' => '<div class="widget %2$s">',
		'after_widget'  => '</div>',
	) );

	// Normal Page Sidebar
	register_sidebar( array(
		'name'          => esc_html__( 'Page Sidebar', 'physio-qt' ),
		'description' 	=> esc_html__( 'Widgets for the default page(s) sidebar', 'physio-qt'),
		'id'            => 'page-sidebar',
		'before_widget' => '<div class="widget %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h4 class="widget-title">',
		'after_title'   => '</h4>',
	) );

	// Shop Sidebar
	register_sidebar( array(
		'name'          => esc_html__( 'Shop Sidebar', 'physio-qt' ),
		'description'   => esc_html__( 'Widgets for the shop (WooCommerce) sidebar', 'physio-qt'),
		'id'            => 'shop-sidebar',
		'before_widget' => '<div class="widget %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h4 class="widget-title">',
		'after_title'   => '</h4>',
	) );

	// Top Footer
	$physio_qt_top_footer_columns = (int) get_theme_mod( 'top_footer_columns', 4 );
	if ( $physio_qt_top_footer_columns > 0 ) {
		register_sidebar( array(
			'name'          => esc_html__( 'Footer - Top', 'physio-qt' ),
			'description'   => esc_html__( 'Widgets for the top footer. Change column amount at: Appearance &gt; Customize &gt; Theme Options &gt; Top Footer', 'physio-qt' ),
			'id'            => 'top-footer',
			'before_widget' => sprintf( '<div class="col-xs-12 col-md-%d"><div class="widget %%2$s">', round( 12 / $physio_qt_top_footer_columns ) ),
			'after_widget'  => '</div></div>',
			'before_title'  => '<h6 class="widget-title">',
			'after_title'   => '</h6>',
		) );
	}

	// Main Footer
	$physio_qt_main_footer_columns = (int) get_theme_mod( 'main_footer_columns', 4 );
	if ( $physio_qt_main_footer_columns > 0 ) {
		register_sidebar( array(
			'name'          => esc_html__( 'Footer - Main', 'physio-qt' ),
			'description'   => esc_html__( 'Widgets for the main footer. Change column amount at: Appearance &gt; Customize &gt; Theme Options &gt; Main Footer', 'physio-qt' ),
			'id'            => 'main-footer',
			'before_widget' => sprintf( '<div class="col-xs-12 col-md-%d"><div class="widget %%2$s">', round( 12 / $physio_qt_main_footer_columns ) ),
			'after_widget'  => '</div></div>',
			'before_title'  => '<h6 class="widget-title">',
			'after_title'   => '</h6>',
		) );
	}
}
add_action( 'widgets_init', 'physio_qt_sidebars' );