( function( $ ) {
	$( document ).ready( function() {
		/** Group Join / Leave Buttons **************************************/

		// Confirmation when clicking Leave Group in group headers
		$( document ).on( 'click', '.group-button .leave-group', function() {
			if ( false === confirm( CBBPSettings.leaveGroupConfirm ) ) {
				return false;
			}
		} );

		$( document ).on( 'click', '.group-button a', function() {
			var gid = $( this ).parent().attr( 'id' ),
				nonce = $( this ).attr( 'href' ),
				thelink = $( this );

			gid = gid.split( '-' );
			gid = gid[1];

			nonce = nonce.split( '?_wpnonce=' );
			nonce = nonce[1].split( '&' );
			nonce = nonce[0];

			// Leave Group confirmation within directories - must intercept
			// AJAX request
			if ( thelink.hasClass( 'leave-group' ) && false === confirm( CBBPSettings.leave_group_confirm ) ) {
				return false;
			}

			$.post( ajaxurl, {
				action: 'joinleave_group',
				gid: gid,
				_wpnonce: nonce
			},
			function( response ) {
				var parentdiv = thelink.parent();

				// user groups page
				if ( ! $( 'body.directory' ).length ) {
					window.location.reload();

					// groups directory
				} else {
					$( parentdiv ).fadeOut( 200,
						function() {
							parentdiv.fadeIn( 200 ).html( response );

							var mygroups = $( '#groups-personal span' ),
								add = 1;

							if ( thelink.hasClass( 'leave-group' ) ) {
								// hidden groups slide up
								if ( parentdiv.hasClass( 'hidden' ) ) {
									parentdiv.closest( 'li' ).slideUp( 200 );
								}

								add = 0;
							} else if ( thelink.hasClass( 'request-membership' ) ) {
								add = false;
							}

							// change the "My Groups" value
							if ( mygroups.length && add !== false ) {
								if ( add ) {
									mygroups.text( ( mygroups.text() >> 0 ) + 1 );
								} else {
									mygroups.text( ( mygroups.text() >> 0 ) - 1 );
								}
							}
						}
					);
				}
			} );
			return false;
		} );
	} );
}( jQuery ) );
