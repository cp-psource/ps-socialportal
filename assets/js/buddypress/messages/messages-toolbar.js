( function( $ ) {
	// On toolbar participant click.
	$( document ).on( 'click', '.bp-messages-toolbar .participants-list a', function() {
		var participant = $( this ),
			entry = participant.closest( '.participant-entry' ),
			toolbar = entry.find( '.bp-messages-toolbar' ),
			participant_id = entry.data( 'participant-id' ),
			nonce = '';
		// load Participant info.
		toolbar.addClass( 'loading' );

		CB.Messages.getParticipantInfo( {
			user_id: participant_id,
			_wpnonce: nonce
		} ).done( function( response ) {
			toolbar.removeClass( 'loading' );
			if ( ! response.success ) {
				//@todo notify of error.
				// Notify of the error.

			}
		} );
	} );
}( jQuery ) );
