<?php
/**
 * Breadcrumbs Template Part
 *
 * @package physio-qt
 */

if ( function_exists( 'bcn_display' ) && ! is_front_page() ) :
	bcn_display();
endif;
?>