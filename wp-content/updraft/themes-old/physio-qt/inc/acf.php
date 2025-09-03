<?php
/**
 * Functions for ACF
 */

/**
 * Hide ACF from admin menu, just for safety
 */
if ( 'yes' !== get_theme_mod( 'qt_show_acf', 'no' ) && ! defined( 'ACF_LITE' ) ) {
	define( 'ACF_LITE', true );
}

/**
 * Fix if ACF is not activated after theme install
 * No function prefixing here because ACF get_field function
 */
if ( ! is_admin() && ! function_exists( 'get_field' ) ) {
    function get_field( $key ) {
        return get_post_meta( get_the_ID(), $key, true );
    }
    function have_rows( $value = false ) {
    	return false;
	}
}

/**
 * Remove ACF PRO plugin update notice - users will be notified for updates through TGM
 */
if ( ! function_exists( 'physio_qt_acf_remove_update_notification' ) ) {
	function physio_qt_acf_remove_update_notification( $value ) {
		if ( function_exists( 'acf_pro_get_license_key' ) && acf_pro_get_license_key() ) {
			return $value;
		}
		return false;
	}
	add_filter( 'acf/settings/show_updates', 'physio_qt_acf_remove_update_notification' );
}