( function( $ ) {
	$( document ).ready( function() {
		/** Invite Friends Interface ****************************************/

		/* Select a user from the list of friends and add them to the invite list */
		$( '#send-invite-form' ).on( 'click', '#invite-list input', function() {
			// invites-loop template contains a div with the .invite class
			// We use the existence of this div to check for old- vs new-
			// style templates.
			var invitesNewTemplate = $( '#send-invite-form > .invite' ).length,
				friendID, friendAction;

			$( '.ajax-loader' ).toggle();

			// Dim the form until the response arrives
			if ( invitesNewTemplate ) {
				$( this ).parents( 'ul' ).find( 'input' ).prop( 'disabled', true );
			}

			friendID = $( this ).val();

			if ( $( this ).prop( 'checked' ) === true ) {
				friendAction = 'invite';
			} else {
				friendAction = 'uninvite';
			}

			if ( ! invitesNewTemplate ) {
				$( '.item-list-tabs li.selected' ).addClass( 'loading' );
			}

			$.post( ajaxurl, {
				action: 'groups_invite_user',
				friend_action: friendAction,
				_wpnonce: $( '#_wpnonce_invite_uninvite_user' ).val(),
				friend_id: friendID,
				group_id: $( '#group_id' ).val()
			},
			function( response ) {
				if ( $( '#message' ) ) {
					$( '#message' ).hide();
				}

				var object = 'invite',
					filter = 'bp-invite-filter',
					scope = 'bp-invite-scope';

				if ( invitesNewTemplate ) {
					CB.Request.getItems( object, scope, filter, false, 1, '' ).done( function( response ) {
						var $itemListContainer = $( 'div.' + object );
						$itemListContainer.fadeOut( 100, function() {
							$( this ).html( response );
							$itemListContainer.trigger( 'updated:item:list', [ object, $itemListContainer ] );
							$( this ).fadeIn( 100 );
						} );
						$( '.item-list-tabs li.selected' ).removeClass( 'loading' );
					} );
				} else {
					// Old-style templates manipulate only the
					// single invitation element
					$( '.ajax-loader' ).toggle();

					if ( friendAction === 'invite' ) {
						$( '#friend-list' ).append( response );
					} else if ( friendAction === 'uninvite' ) {
						$( '#friend-list li#uid-' + friendID ).remove();
					}

					$( '.item-list-tabs li.selected' ).removeClass( 'loading' );
				}
			} );
		} );

		/* Remove a user from the list of users to invite to a group */
		$( '#send-invite-form' ).on( 'click', 'a.remove', function() {
			// invites-loop template contains a div with the .invite class
			// We use the existence of this div to check for old- vs new-
			// style templates.
			var invitesNewTemplate = $( '#send-invite-form > .invite' ).length,
				friendID = $( this ).attr( 'id' );

			$( '.ajax-loader' ).toggle();

			friendID = friendID.split( '-' );
			friendID = friendID[1];

			$.post( ajaxurl, {
				action: 'groups_invite_user',
				friend_action: 'uninvite',
				_wpnonce: $( '#_wpnonce_invite_uninvite_user' ).val(),
				friend_id: friendID,
				group_id: $( '#group_id' ).val()
			},
			function( response ) {
				if ( invitesNewTemplate ) {
					// With new-style templates, we refresh the
					// entire list
					var object = 'invite',
						filter = 'bp-invite-filter',
						scope = 'bp-invite-scope';
					CB.Request.getItems( object, scope, filter, false, 1, '' ).done( function( response ) {
						var $itemListContainer = $( 'div.' + object );
						$itemListContainer.fadeOut( 100, function() {
							$( this ).html( response );
							$itemListContainer.trigger( 'updated:item:list', [ object, $itemListContainer ] );
							$( this ).fadeIn( 100 );
						} );
						$( '.item-list-tabs li.selected' ).removeClass( 'loading' );
					} );
				} else {
					// Old-style templates manipulate only the
					// single invitation element
					$( '.ajax-loader' ).toggle();
					$( '#friend-list #uid-' + friendID ).remove();
					$( '#invite-list #f-' + friendID ).prop( 'checked', false );
				}
			} );

			return false;
		} );
	} );
}( jQuery ) );
