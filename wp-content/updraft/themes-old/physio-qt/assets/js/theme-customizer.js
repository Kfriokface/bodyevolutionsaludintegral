/**
 * customizer.js
 *
 * Theme Customizer enhancements for a better user experience.
 *
 * Contains handlers to make Theme Customizer preview reload changes asynchronously.
 */

( function( $ ) {
	"use strict";
	
	// Site title and description.
	wp.customize( 'blogname', function( value ) {
		value.bind( function( to ) {
			$( '.header .header-wrapper .header-logo a h1' ).text( to );
		} );
	} );
	wp.customize( 'blogdescription', function( value ) {
		value.bind( function( to ) {
			$( '.header .header-topbar .header-description' ).text( to );
		} );
	} );


	/**
	 * Topbar
	 */
	// Topbar Background Color
	wp.customize( 'topbar_bg', function( value ) {
		value.bind( function( newval ) {
			$( '.header .header-topbar' ).css( 'background-color', newval );
		} );
	} );

	// Topbar Text Color
	wp.customize( 'topbar_text_color', function( value ) {
		value.bind( function( newval ) {
			$( '.header .header-topbar, .header .header-topbar, .header .header-topbar .header-topbar-sidebar .icon-box--title' ).css('color', newval );
		} );
	} );

	// Topbar Link Color
	wp.customize( 'topbar_link_color', function( value ) {
		value.bind( function( newval ) {
			$( '.header-topbar-sidebar .menu > li > a, .header .header-topbar a, .header .header-topbar .header-topbar-sidebar a.icon-box .icon-box--title' ).css('color', newval );
		} );
	} );

	// Topbar Navigation Submenu Background Color
	wp.customize( 'topbar_submenu_background_color', function( value ) {
		value.bind( function( newval ) {
			$( '.header-topbar-sidebar .menu .sub-menu li a' ).css( 'background-color', newval );
		} );
	} );

	// Topbar Navigation Submenu Text Color
	wp.customize( 'topbar_submenu_text_color', function( value ) {
		value.bind( function( newval ) {
			$( '.header-topbar-sidebar .menu .sub-menu li a' ).css( 'color', newval );
		} );
	} );



	/**
	 * Header
	 */

	// Header Mobile Background Color
	wp.customize( 'header_mobile_background_color', function( value ) {
		value.bind( function( newval ) {
			$( '.header .header-wrapper' ).css( 'background-color', newval );
		} );
	} );

	// Header Desktop Background Color
	wp.customize( 'header_desktop_background_color', function( value ) {
		value.bind( function( newval ) {
			$( '.header .header-wrapper' ).css( 'background-color', newval );
		} );
	} );

	// Header Featured Button Background Color
	wp.customize( 'featured_button_background_color', function( value ) {
		value.bind( function( newval ) {
			$( '.header .header-wrapper .featured-button a' ).css( 'background-color', newval );
		} );
	} );

	// Header Featured Button Text Color
	wp.customize( 'featured_button_text_color', function( value ) {
		value.bind( function( newval ) {
			$( '.header .header-wrapper .featured-button a' ).css( 'color', newval );
		} );
	} );


	/**
	 * Page Header (Title Area)
	 */

	// Page Header Title Color
	wp.customize( 'page_header_title_color', function( value ) {
		value.bind( function( newval ) {
			$( '.page-header--title' ).css( 'color', newval );
		} );
	} );

	// Page Header Subtitle Color
	wp.customize( 'page_header_subtitle_color', function( value ) {
		value.bind( function( newval ) {
			$( '.page-header--subtitle' ).css( 'color', newval );
		} );
	} );

	// Page Header Background Color
	wp.customize( 'page_header_background_color', function( value ) {
		value.bind( function( newval ) {
			$( '.page-header' ).css( 'background-color', newval );
		} );
	} );



	/**
	 * Breadcrumbs
	 */

	// Breadcrumbs Text Color
	wp.customize( 'breadcrumbs_text_color', function( value ) {
		value.bind( function( newval ) {
			$( '.breadcrumbs a' ).css( 'color', newval );
		} );
	} );

	// Breadcrumbs Active Color
	wp.customize( 'breadcrumbs_active_color', function( value ) {
		value.bind( function( newval ) {
			$( '.breadcrumbs span > span' ).css( 'color', newval );
		} );
	} );

	// Breadcrumbs Background Color
	wp.customize( 'breadcrumbs_background_color', function( value ) {
		value.bind( function( newval ) {
			$( '.breadcrumbs' ).css( 'background-color', newval );
		} );
	} );



	/**
	 * Top Footer
	 */

	// Top Footer Text Color
	wp.customize( 'top_footer_text_color', function( value ) {
		value.bind( function( newval ) {
			$( '.footer .footer--top' ).css( 'color', newval );
			$( '.footer .icon-box--icon i' ).css( 'color', newval );
			$( '.footer .icon-box--title' ).css( 'color', newval );
			$( '.footer .icon-box--description' ).css( 'color', newval );
			$( '.footer .social-icons a' ).css( 'color', newval );
		} );
	} );

	// Top Footer Background Color
	wp.customize( 'top_footer_background_color', function( value ) {
		value.bind( function( newval ) {
			$( '.footer .footer--top' ).css( 'color', newval );
		} );
	} );



	/**
	 * Middle Footer
	 */

	// Middle Footer Text Color
	wp.customize( 'main_footer_text_color', function( value ) {
		value.bind( function( newval ) {
			$( '.footer .footer--middle' ).css( 'color', newval );
		} );
	} );

	// Middle Footer Link Color
	wp.customize( 'main_footer_link_color', function( value ) {
		value.bind( function( newval ) {
			$( '.footer .footer--middle .widget_nav_menu ul.menu li a' ).css( 'color', newval );
		} );
	} );

	// Middle Footer Widget Title Color
	wp.customize( 'main_footer_widget_title_color', function( value ) {
		value.bind( function( newval ) {
			$( '.footer .widget-title' ).css( 'color', newval );
		} );
	} );

	// Middle Footer Background Color
	wp.customize( 'main_footer_background_color', function( value ) {
		value.bind( function( newval ) {
			$( '.footer .footer--middle' ).css( 'background-color', newval );
		} );
	} );



	/**
	 * Bottom Footer
	 */

	// Bottom Footer Text Color
	wp.customize( 'bottom_footer_text_color', function( value ) {
		value.bind( function( newval ) {
			$( '.footer .footer--bottom' ).css( 'color', newval );
		} );
	} );

	// Bottom Footer Link Color
	wp.customize( 'bottom_footer_link_color', function( value ) {
		value.bind( function( newval ) {
			$( '.footer .footer--bottom a' ).css( 'color', newval );
		} );
	} );

	// Bottom Footer Background Color
	wp.customize( 'bottom_footer_background_color', function( value ) {
		value.bind( function( newval ) {
			$( '.footer .footer--bottom' ).css( 'background-color', newval );
		} );
	} );



	/**
	 * Theme Colors
	 */
	 
	// Content Background Color
	wp.customize( 'boxed_background', function( value ) {
		value.bind( function( newval ) {
			$( 'body .layout-boxed' ).css( 'background-color', newval );
		} );
	} );

	// Theme Text Color
	wp.customize( 'text_color', function( value ) {
		value.bind( function( newval ) {
			$( 'body' ).css( 'color', newval );
		} );
	} );

	// Theme Widget Title Color
	wp.customize( 'widgettitle_color', function( value ) {
		value.bind( function( newval ) {
			$( '.widget-title' ).css( 'color', newval );
		} );
	} );

	// Theme Button Background Color
	wp.customize( 'button_color', function( value ) {
		value.bind( function( newval ) {
			$( '.btn.btn-primary' ).css( 'background-color', newval );
			$( '.woocommerce div.product form.cart .button.single_add_to_cart_button' ).css( 'background-color', newval );
			$( '.woocommerce-cart .wc-proceed-to-checkout a.checkout-button' ).css( 'background-color', newval );
		} );
	} );

	// Theme Button Text Color
	wp.customize( 'button_text_color', function( value ) {
		value.bind( function( newval ) {
			$( '.btn.btn-primary' ).css( 'color', newval );
			$( '.woocommerce div.product form.cart .button.single_add_to_cart_button' ).css( 'color', newval );
			$( '.woocommerce-cart .wc-proceed-to-checkout a.checkout-button' ).css( 'color', newval );
		} );
	} );

} )( jQuery );