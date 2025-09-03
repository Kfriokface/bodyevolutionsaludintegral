/* Sticky Navigation */
define( ['jquery', 'underscore'], function ( $, _ ) {
	'use strict';

	var navigation   = $('.header-wrapper'),
		bodyClass    = $('body'),
		stickyNavTop = navigation.offset().top,
		navbarHeight = navigation.height(),
		adminBar     = $('body').hasClass('admin-bar') ? 32 : 0;

	$( window ).on( 'scroll', function() {
		if( $( window ).scrollTop() > stickyNavTop - adminBar ) {
			if( bodyClass.hasClass( 'sticky-navigation' ) ) {
				$( '.sticky-offset' ).height( navbarHeight );
				navigation.addClass( 'is-sticky' );
			}
		} else {
			navigation.removeClass( 'is-sticky' );
		}
	});

	var updateLayout = _.debounce(function() {
		stickyNavTop;
		navbarHeight;
		$( '.sticky-offset' ).height( navbarHeight );
	}, 100 );

	window.addEventListener( "resize", updateLayout, false );
});