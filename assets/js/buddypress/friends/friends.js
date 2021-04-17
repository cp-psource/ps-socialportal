( function( $ ) {
	var requests = CB.Friends.Requests;

	$( document ).on( 'click:request:friendship', function( evt, button, buttonWrapper, fid, nonce ) {
		buttonWrapper.addClass( 'loading' );
		requests.requestFriendship( { id: fid, _wpnonce: nonce } ).then( function( response ) {
			processRequest( response, buttonWrapper );
		} );
	} );

	$( document ).on( 'click:cancel:friendship:request', function( evt, button, buttonWrapper, id, nonce ) {
		buttonWrapper.addClass( 'loading' );
		requests.cancelRequest( { id: id, _wpnonce: nonce } ).then( function( response ) {
			processRequest( response, buttonWrapper );
		} );
	} );

	$( document ).on( 'click:cancel:friendship', function( evt, button, buttonWrapper, id, nonce ) {
		buttonWrapper.addClass( 'loading' );
		requests.cancelFriendship( { id: id, _wpnonce: nonce } ).then( function( response ) {
			processRequest( response, buttonWrapper );
		} );
	} );

	// On accept button click.
	$( document ).on( 'click:accept:friendship', function( evt, button, buttonWrapper, id, nonce ) {
		buttonWrapper.addClass( 'loading' );
		buttonWrapper.siblings( '.reject_friendship' ).hide();
		requests.acceptFriendship( { id: id, _wpnonce: nonce } ).then( function( response ) {
			processAcceptReject( response, buttonWrapper );
		} );
	} );

	// On Reject button click.
	$( document ).on( 'click:reject:friendship', function( evt, button, buttonWrapper, id, nonce ) {
		buttonWrapper.addClass( 'loading' );
		buttonWrapper.siblings( '.accept_friendship' ).hide();
		requests.rejectRequest( { id: id, _wpnonce: nonce } ).then( function( response ) {
			processAcceptReject( response, buttonWrapper );
		} );
	} );

	// process response for add, remove, cancel friendship/requests.
	function processRequest( response, buttonWrapper ) {
		buttonWrapper.removeClass( 'loading' );
		if ( ! response.success ) {
			buttonWrapper.parents( '.list-item' ).append( response.data.message );
			return;
		}

		// if we are here, te request succeeded.
		buttonWrapper.fadeOut( 100, function() {
			buttonWrapper.trigger( 'webui:refresh' );
			buttonWrapper.replaceWith( response.data.button );
		} );
	}

	// process response for add, remove, cancel friendship/requests.
	function processAcceptReject( response, buttonWrapper ) {
		buttonWrapper.removeClass( 'loading' );
		if ( ! response.success ) {
			buttonWrapper.parents( '.list-item' ).append( response.data.message );
			return;
		}

		// if we are here, te request succeeded.
		buttonWrapper.fadeOut( 100, function() {
			buttonWrapper.trigger( 'webui:refresh' );
			buttonWrapper.replaceWith( response.data.message );
		} );
	}
}( jQuery ) );
