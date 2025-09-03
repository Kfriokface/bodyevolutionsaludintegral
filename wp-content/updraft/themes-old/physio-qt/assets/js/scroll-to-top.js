/* Scroll To Top Button */
define( ['jquery'], function ( $ ) {
	'use strict';

	var isVisible = false;
		$(window).scroll(function(){
			var shouldBeVisible = $( window ).scrollTop() > 1000;
			if ( shouldBeVisible && !isVisible ) {
				isVisible = true;
				$('.scroll-to-top').addClass('visible');
			} else if ( isVisible && !shouldBeVisible ) {
				isVisible = false;
				$('.scroll-to-top').removeClass('visible');
			}
	});

	$('.scroll-to-top').on('click', function(e){
		e.preventDefault();
		$('html, body').animate({ scrollTop: 0, }, 700 );
	});

});