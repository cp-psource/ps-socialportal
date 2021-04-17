( function( $ ) {
	window.CB = typeof CB === 'undefined' ? {} : CB;

	CB.Notifications = {};

	CB.Notifications.Requests = {

		get: function( args ) {
			args = $.extend( {
				action: 'cb_notifications_get',
				scope: 'all',
				page: 0,
				offset: 0,
				max: 0,
				_wpnonce: ''
			}, args );

			return $.post( ajaxurl, args );
		},
		getSingle: function( args ) {
			args = $.extend( {
				action: 'cb_notifications_get_single',
				id: 0,
				_wpnonce: ''
			}, args );
			return $.post( ajaxurl, args );
		},
		deleteOne: function( args ) {
			args = $.extend( {
				action: 'cb_notifications_delete',
				id: 0,
				_wpnonce: ''
			}, args );
			return $.post( ajaxurl, args );
		},
		deleteAll: function( args ) {
			args = $.extend( {
				action: 'cb_notifications_delete_bulk',
				ids: [],
				_wpnonce: ''
			}, args );
			return $.post( ajaxurl, args );
		},
		markRead: function( args ) {
			args = $.extend( {
				action: 'cb_notifications_mark_read',
				id: 0,
				_wpnonce: ''
			}, args );

			return $.post( ajaxurl, args );
		},
		markUnread: function( args ) {
			args = $.extend( {
				action: 'cb_notifications_mark_unread',
				id: 0,
				_wpnonce: ''
			}, args );

			return $.post( ajaxurl, args );
		},
		markAllRead: function( args ) {
			args = $.extend( {
				action: 'cb_notifications_mark_read_bulk',
				ids: [],
				_wpnonce: ''
			}, args );

			return $.post( ajaxurl, args );
		},
		markAllUnread: function( args ) {
			args = $.extend( {
				action: 'cb_notifications_mark_unread_bulk',
				ids: [],
				_wpnonce: ''
			}, args );

			return $.post( ajaxurl, args );
		}
	};
}( jQuery ) );
