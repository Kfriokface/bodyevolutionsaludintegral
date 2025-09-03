<?php
/**
 * Get the Google Fonts for the typography customizer fields
 *
 * @package physio-qt
 */

if ( !function_exists( 'physio_qt_list_google_fonts' ) ) {
	function physio_qt_list_google_fonts() {

		$fonts = get_transient( "google_typography_fonts" );

		if ( false === $fonts ) {

			$api_key = 'AIzaSyA8I4NStBOCezuj3ZzFfy4Gx2ybJerLUMo';
			$request = wp_remote_get( 'https://www.googleapis.com/webfonts/v1/webfonts?key='. $api_key . '' );

			if ( is_wp_error( $request ) ) {

			   $error_message = $request->get_error_message();
			   echo wp_kses_post( $error_message );

			} else {
				
				$json = wp_remote_retrieve_body( $request );
				$data = json_decode( $json, true );

				$items = $data["items"];
                $fonts = array();

                foreach ( $items as $index => $item ) {
                	$fonts[$item['family']] = $item['family'];
                }
				
				set_transient( "google_typography_fonts", $fonts, 60 * 60 * 24 );
			}
		}
		
		return $fonts;
	}
}