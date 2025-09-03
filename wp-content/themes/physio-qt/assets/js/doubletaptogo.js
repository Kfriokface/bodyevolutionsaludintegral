/* Scroll To Top Button */
define( ['jquery'], function ( $ ) {
	'use strict';

	/*
	 By Osvaldas Valutis, www.osvaldas.info
	 Available for use under the MIT License
	*/

	$.fn.doubleTapToGo = function( params ) {

		if ( ( 'ontouchstart' in window ) && Modernizr.mq( '(min-width: 992px)' ) || window.DocumentTouch && document instanceof DocumentTouch ) {

			this.each( function() {
				var curItem = false;

				$( this ).on( 'click', function( e ) {
					var item = $( this );
					if( item[ 0 ] != curItem[ 0 ] ) {
						e.preventDefault();
						curItem = item;
					}
				});

				$( document ).on( 'click.td', 'a', function ( e ) {
					var resetItem = true,
						parents	  = $( e.target ).parents();

					for( var i = 0; i < parents.length; i++ )
						if( parents[ i ] == curItem[ 0 ] )
							resetItem = false;

					if( resetItem )
						curItem = false;
				});
			});

			return this;
		}
	};
} );