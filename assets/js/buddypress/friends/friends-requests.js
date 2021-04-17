( function( CB, $ ) {
	CB.Friends = CB.Friends || {};
	CB.Friends.Requests = {
		// request friendship.
		requestFriendship: function( args ) {
			args = $.extend( {
				action: 'friends_request_friendship',
				id: 0,
				_wpnonce: ''
			},
			args
			);
			return $.post( ajaxurl, args );
		},
		// cancel friendship request.
		cancelRequest: function( args ) {
			args = $.extend( {
				action: 'friends_cancel_friendship_request',
				id: 0,
				_wpnonce: ''
			},
			args
			);
			return $.post( ajaxurl, args );
		},
		// cancel friendship.
		cancelFriendship: function( args ) {
			args = $.extend( {
				action: 'friends_cancel_friendship',
				id: 0,
				_wpnonce: ''
			},
			args
			);
			return $.post( ajaxurl, args );
		},

		// accept friendship.
		acceptFriendship: function( args ) {
			args = $.extend( {
				action: 'friends_accept_friendship',
				id: 0,
				_wpnonce: ''
			},
			args
			);
			return $.post( ajaxurl, args );
		},
		// reject request.
		rejectRequest: function( args ) {
			args = $.extend( {
				action: 'friends_reject_friendship',
				id: 0,
				_wpnonce: ''
			},
			args
			);
			return $.post( ajaxurl, args );
		}

	};
}( CB || {}, jQuery ) );
