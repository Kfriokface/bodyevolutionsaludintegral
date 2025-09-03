<?php
/**
 * This file represents an example of the code that themes would use to register
 * the required plugins.
 *
 * It is expected that theme authors would copy and paste this code into their
 * functions.php file, and amend to suit.
 *
 * @package    TGM-Plugin-Activation
 * @subpackage Example
 * @version    2.5.0-alpha
 * @author     Thomas Griffin, Gary Jones
 * @copyright  Copyright (c) 2011, Thomas Griffin
 * @license    http://opensource.org/licenses/gpl-2.0.php GPL v2 or later
 * @link       https://github.com/thomasgriffin/TGM-Plugin-Activation
 */

/**
 * Register the required plugins for this theme.
 */
function physio_qt_register_required_plugins() {

    /**
     * Array of plugin arrays. Required keys are name and slug.
     * If the source is NOT from the .org repo, then source is also required.
     */
    $plugins = array(

        array(
            'name'               => 'Advanced Custom Fields Pro',
            'slug'               => 'advanced-custom-fields-pro',
            'source'             => get_theme_file_path( '/bundled-plugins/advanced-custom-fields-pro.zip' ),
            'required'           => true,
            'version'            => '5.9.3',
            'external_url'       => 'https://www.advancedcustomfields.com/pro/'
        ),
        array(
            'name'               => 'Page Builder by SiteOrigin',
            'slug'               => 'siteorigin-panels',
            'required'           => true,
        ),
        array(
            'name'               => 'Physio Toolkit',
            'slug'               => 'physio-toolkit',
            'source'             => get_theme_file_path( '/bundled-plugins/physio-toolkit.zip' ),
            'required'           => true,
            'version'            => '1.5.3',
            'external_url'       => 'https://themeforest.net/user/qreativethemes/portfolio'
        ),
        array(
            'name'               => 'SiteOrigin Widgets Bundle',
            'slug'               => 'so-widgets-bundle',
            'required'           => true,
        ),
        array(
            'name'               => 'One Click Demo Import',
            'slug'               => 'one-click-demo-import',
            'required'           => true,
        ),
        array(
            'name'               => 'Booked - Appointment Booking for WordPress',
            'slug'               => 'booked',
            'source'             => get_theme_file_path( '/bundled-plugins/booked.zip' ),
            'required'           => false,
            'version'            => '2.3',
            'external_url'       => 'https://codecanyon.net/item/booked-appointment-booking-for-wordpress/9466968'
        ),
        array(
            'name'               => 'Breadcrumb NavXT',
            'slug'               => 'breadcrumb-navxt',
            'required'           => false,
        ),
        array(
            'name'               => 'Simple Page Sidebars',
            'slug'               => 'simple-page-sidebars',
            'required'           => false,
        ),
        array(
            'name'               => 'Contact Form 7',
            'slug'               => 'contact-form-7',
            'required'           => false,
        ),
        array(
            'name'               => 'Easy Fancybox',
            'slug'               => 'easy-fancybox',
            'required'           => false,
        ),
        array(
            'name'               => 'WooCommerce',
            'slug'               => 'woocommerce',
            'required'           => false,
        ),
    );
    tgmpa( $plugins );
}
add_action( 'tgmpa_register', 'physio_qt_register_required_plugins' );