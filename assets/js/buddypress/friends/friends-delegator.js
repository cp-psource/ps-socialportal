( function( $ ) {
	/* Add / Remove friendship buttons */
	$( document ).on( 'click', '.friendship-button a', function() {
		var
			button = $( this ),
			fid = button.attr( 'id' ),
			nonce = bp_get_query_var( '_wpnonce', button.attr( 'href' ) ),
			buttonWrapper = button.parent( '.generic-button' );

		fid = fid.split( '-' );
		fid = fid[fid.length - 1];

		if ( button.hasClass( 'not_friends' ) ) {
			button.trigger( 'click:request:friendship', [ button, buttonWrapper, fid, nonce ] );
		} else if ( button.hasClass( 'pending_friend' ) ) {
			button.trigger( 'click:cancel:friendship:request', [ button, buttonWrapper, fid, nonce ] );
		} else if ( button.hasClass( 'is_friend' ) ) {
			button.trigger( 'click:cancel:friendship', [ button, buttonWrapper, fid, nonce ] );
		} else if ( button.hasClass( 'accept_friendship' ) ) {
			button.trigger( 'click:accept:friendship', [ button, buttonWrapper, fid, nonce ] );
		} else if ( button.hasClass( 'reject_friendship' ) ) {
			button.trigger( 'click:reject:friendship', [ button, buttonWrapper, fid, nonce ] );
		}

		return false;
	} );
}( jQuery ) );
