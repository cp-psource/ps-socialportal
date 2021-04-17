( function( CB, $ ) {
	// handle kind of sticky view.
	$( document ).ready( function() {
		var $messagesContainer = $( '#bp-messages-view-container' ),
			$messagesVisibleView = $( '#bp-messages-view-visible' ),
			$threadsPanel = $( '#bp-threads-panel' ),
			$messagesPanel = $( '#bp-messages-panel' ),
			isDesktop = $( window ).width() >= 992,
			footerOffsetTop = $( '#site-footer' ).offset().top;
		$messagesVisibleView.removeClass( 'hidden' );
		if ( ! $messagesContainer.length ) {
			return;
		}

		function updateStatus() {
			if ( ! isDesktop ) {
				return;
			}
			// container
			var wHeight = $( window ).height(),
				scrollTop = $( window ).scrollTop(),
				containerOffsetTop = $messagesContainer.offset().top,
				aHeight, vHeight, mbHeight;

			// console.log( scrollTop, footerOffsetTop-150);
			// reached footer.
			if ( scrollTop > footerOffsetTop - 150 ) {
				return;// don't do anything.
			}

			// calculate height of the container
			// available height for the container
			vHeight = wHeight - containerOffsetTop + scrollTop;

			aHeight = Math.min( wHeight, vHeight );
			// console.log(aHeight, vHeight);
			if ( aHeight < wHeight ) {
				mbHeight = aHeight;
			} else {
				mbHeight = wHeight;
			}
			$messagesContainer.height( vHeight + 150 );
			// set fixed height.
			$threadsPanel.height( mbHeight - 10 );
			$messagesPanel.height( mbHeight - 10 );

			// var top = $messages_container.offset().top - ;
			//   if( top<= 0 ) {
			//     top=0;
			//}
			//$messages_visible_panel.css({top:top+'px'});
		}

		// on dom ready.
		updateStatus();
		// on scroll.
		var debounce = $.cbDebounce;

		$( window ).scroll( debounce( updateStatus, 20 ) );
		// on resize.
		$( window ).resize( debounce( updateStatus, 10 ) );
	} );
}( CB || {}, jQuery ) );
