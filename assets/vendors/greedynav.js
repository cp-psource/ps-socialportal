/*
GreedyNav.js - http://lukejacksonn.com/actuate
Licensed under the MIT license - http://opensource.org/licenses/MIT
Copyright (c) 2015 Luke Jackson
https://github.com/lukejacksonn/GreedyNav
*/

( function( $ ) {
	$.fn.greedyNav = function( options ) {
		var settings = $.extend( {
			buttonSelector: '.greedy-button-handle',
			buttonClass: 'greedy-button-handle',
			buttonLabel: 'More...',
			LinksSelector: '.greedy-visible-links',
			hiddenLinksSelector: '.greedy-hidden-links',
			classHiddenLinks: 'greedy-hidden-links',
			classHidden: 'greedy-hidden',
			classVisible: 'greedy-visible',
			closingTime: 1000
		}, options );

		//nav.greedy
		var $btn = this.find( settings.buttonSelector );
		if ( ! $btn.length ) {
			$btn = $( '<button class="' + settings.buttonClass + '">' + settings.buttonLabel + '</button>' ).appendTo( this );
		}

		var $alinks = $( this ).find( settings.LinksSelector ),
			$vlinks = $( this ).find( settings.LinksSelector ),
			$hlinks = $( this ).find( settings.hiddenLinksSelector );

		if ( ! $hlinks.length ) {
			$hlinks = $( '<ul class="' + settings.classHiddenLinks + ' ' + settings.classHidden + '"></ul>' ).appendTo( this );
		}

		var numOfItems = 0,
			totalSpace = 0,
			closingTime = settings.closingTime,
			breakWidths = [];

		var availableSpace, numOfVisibleItems, requiredSpace, timer;
		numOfVisibleItems = $vlinks.children().length;
		//reset dimension.
		function resetDim() {
			totalSpace = 0;
			numOfItems = 0;
			breakWidths = [];
			// Get initial state
			$alinks.children().each( function() {
				totalSpace += $( this ).outerWidth( true );
				numOfItems += 1;
				breakWidths.push( totalSpace );
			} );

			/*$hlinks.children().each( function() {
                totalSpace += $(this).outerWidth(true);
                numOfItems += 1;
                breakWidths.push(totalSpace);
            });*/
		}

		function check() {
			// console.log('call');
			var handleWidth = $hlinks.children().length > 0 ? $btn.outerWidth( true ) : 0;
			// Get inner width, without border, padding.
			availableSpace = $vlinks.width() - handleWidth;// - 15;

			requiredSpace = breakWidths[numOfVisibleItems - 1];
			//console.log(availableSpace, requiredSpace, numOfVisibleItems, numOfItems);
			// There is not enough space
			if ( requiredSpace > availableSpace ) {
				$vlinks.children().last().prependTo( $hlinks );
				numOfVisibleItems -= 1;
				check();
				// There is more than enough space
			} else if ( availableSpace > breakWidths[numOfVisibleItems] ) {
				$hlinks.children().first().appendTo( $vlinks );
				numOfVisibleItems += 1;
				check();
			}
			// Update the button accordingly
			$btn.attr( 'count', numOfItems - numOfVisibleItems );
			if ( ! $hlinks.children().length ) {
				// $btn.removeClass(settings.classVisible);
				$btn.addClass( settings.classHidden );
			} else {
				$btn.removeClass( settings.classHidden );
				//$btn.addClass(settings.classVisible);
			}
		}

		resetDim();
		setTimeout( function() {
			check();
		}, 100 );

		// when all fonts are loaded.
		$( document ).on( 'cb:fonts:loaded', function() {
			resetDim();
			check();
		} );

		// check();
		// Window listeners
		$( window ).resize( function() {
			check();
		} );

		$btn.on( 'click.greedyNav', function() {
			$hlinks.toggleClass( settings.classHidden );
			$hlinks.toggleClass( settings.classVisible );
			clearTimeout( timer );
		} );

		$hlinks.on( 'mouseleave', function() {
			// Mouse has left, start the timer
			timer = setTimeout( function() {
				$hlinks.removeClass( settings.classVisible );
				$hlinks.addClass( settings.classHidden );
			}, closingTime );
		} ).on( 'mouseenter', function() {
			// Mouse is back, cancel the timer
			clearTimeout( timer );
		} );

		var $this = this;
		$( 'body' ).on( 'click.greedyNav', function( e ) {
			if ( $( e.target ).closest( $this ).length === 0 ) {
				$hlinks.addClass( settings.classHidden );
				$hlinks.removeClass( settings.classVisible );
				if ( timer ) {
					clearTimeout( timer );
				}
			}
		} );
	};
}( jQuery ) );
