( function( $ ) {
	window.CB = window.CB || {};
	CB.Messages = CB.Messages || {};

	CB.Messages.Requests = {
		loadThreads: function( args ) {
			args = $.extend( {
				action: 'cb_messages_get_threads',
				page: 0,
				search_terms: '',
				type: 'all',
				_wpnonce: ''
			}, args );
			return $.post( ajaxurl, args );
		},
		loadThread: function( args ) {
			args = $.extend( {
				action: 'cb_messages_get_thread',
				id: 0,
				_wpnonce: ''
			}, args );
			return $.post( ajaxurl, args );
		},
		postReply: function( args ) {
			args = $.extend( {
				action: 'cb_messages_add_reply',
				thread_id: 0,
				content: '',
				_wpnonce: ''
			}, args );

			return $.post( ajaxurl, args );
		},
		postNewMessage: function( args ) {
			args = $.extend( {
				action: 'cb_messages_new_message',
				send_to: [],
				content: '',
				_wpnonce: ''
			}, args );

			return $.post( ajaxurl, args );
		},
		deleteThread: function( args ) {
			args = $.extend( {
				action: 'cb_messages_delete_thread',
				id: 0,
				_wpnonce: ''
			}, args );

			return $.post( ajaxurl, args );
		}
	};
}( jQuery ) );
