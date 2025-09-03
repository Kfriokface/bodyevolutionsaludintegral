// Require JS Configurations
require.config({
	paths: {
		jquery: 'assets/js/return.jquery',
		underscore: 'assets/js/return.underscore'
	}
});

// Get the base url of the teme
require.config( {
	baseUrl: physio_qt.themePath
} );

require([
	'jquery',
	'underscore',
	'assets/js/stickynav',
	'assets/js/scroll-to-top',
	'assets/js/doubletaptogo',
	'assets/js/jquery-mobile-touch-support.min',
], function ( $, _ ) {

	// Properly update the ARIA states on blur (keyboard) and mouse out events
	$( '[role="menubar"]' ).on( 'focus.aria  mouseenter.aria', '[aria-haspopup="true"]', function ( ev ) {
		$( ev.currentTarget ).attr( 'aria-expanded', true );
	} );

	$( '[role="menubar"]' ).on( 'blur.aria  mouseleave.aria', '[aria-haspopup="true"]', function ( ev ) {
		$( ev.currentTarget ).attr( 'aria-expanded', false );
	} );

	// If website visited on touch device fix for display submenu
	if( $( 'body' ).hasClass( 'doubletap' ) ) {
		$( '.menu-item-has-children' ).doubleTapToGo();
	}

	// Bootstrap Tooltip used for logo / awards hover
	$( '[data-toggle="tooltip"]' ).tooltip();

	// Add toggle button for sub menu's on mobile view
	$( '.main-navigation li.menu-item-has-children' ).each( function() {
		$( this ).prepend( '<div class="nav-toggle-mobile-submenu"><i class="fa fa-angle-down"></i></div>' );
	});
	$( '.nav-toggle-mobile-submenu' ).click( function() {
		$( this ).parent().toggleClass( 'nav-toggle-dropdown' );
	} );

	// Bootstrap accordion - active panel
	$( '.panel-title a' ).on( 'click', function(e) {
        e.preventDefault();
        if(!$(this).parents( '.panel-title' ).hasClass( 'active' ) ) {
            $( '.panel-title' ).removeClass( 'active' );
            $(this).parent().addClass( 'active' ).next().addClass( 'active' );
        } else {
            $( '.panel-title' ).removeClass( 'active' );
        }
    });

	// Bootstrap accordion - open by url hash
	$( document ).ready( function() {

		var hash = window.location.hash;
		var sticky = $( 'body' ).hasClass( 'sticky-navigation' ) ? $( '.header-wrapper' ).outerHeight() + 110 : 110;

	    if( hash.length ) {

	        var accordion = $( '.accordion-toggle[href="' + hash + '"]' );

	        if ( accordion.length ) {
		        $( 'html,body' ).animate({
		            scrollTop: accordion.parents( '.panel' ).offset().top - sticky
		        }, 500, function() {
		            accordion.trigger( 'click' );
		        });
		    }
	    }
	});

    // Wrap div around first word of widget title (for SiteOrigin widget titles)
	$( '.widget-title' ).each( function() {
		var widgetTitle,
		openTag = '<span class="normal">',
		closeTag = '</span>';

		widgetTitle = $( this ).text().split(" ");
		widgetTitle.unshift(openTag);
		widgetTitle.splice(2, 0, closeTag);
		widgetTitle = widgetTitle.join(" ");
		$( this ).html( widgetTitle );
	});

	// Scroll to #href link (one-page option)
	$( '.main-navigation a[href^="#"]' ).on( 'click', function( event ) {
	    var target = $( this.getAttribute( 'href' ) );
	    if( target.length ) {
	        event.preventDefault();
	        $( 'html, body' ).stop().animate({
	            scrollTop: target.offset().top
	        }, 1000);
	    }
	});

	// Bootstrap carousel - jumbotron functions
	if( $( '.jumbotron' ).length ) {

		var $this = $( this ),
			sliderTouch = $( '.carousel-touch' );

		// Touch support
		sliderTouch.swiperight( function() {
			$( this ).carousel( 'prev' );
		});
		
		sliderTouch.swipeleft( function() {
			$( this ).carousel( 'next' );
		});
	}

	// Bootstrap carousel - testimonial functions
	if( $( '.testimonial-carousel' ).length ) {

		var $this = $( this ),
			sliderTouch = $( '.carousel-touch' );

		sliderTouch.swiperight( function() {
			$( this ).carousel( 'prev' );
		});
		
		sliderTouch.swipeleft( function() {
			$( this ).carousel( 'next' );
		});
	}

	// Wrap qt table in html for responsive view
	if( $( '.custom-table, .qt-table' ).length > 0 ) {
		$( '.custom-table, .qt-table' ).wrap( "<div class='qt-table-wrap'></div>" );
	}
});