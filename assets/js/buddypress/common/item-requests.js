( function( $ ) {
	CB.Request = {

		/* Filter the current content list (groups/members/blogs/topics) */
		getItems: function( object, scope, filter, search_terms, page, extras, template ) {
			if ( 'activity' === object ) {
				return;//CB.Activity.Requests.get({ scope:scope, filter: filter });
			}

			if ( null === scope ) {
				scope = 'all';
			}
			var utils = CB.ItemUtils;
			/* Save the settings we want to remain persistent */
			utils.setObjectPreference( object, 'scope', scope );
			utils.setObjectPreference( object, 'filter', filter );
			utils.setObjectPreference( object, 'extras', extras );

			if ( 'friends' === object || 'group_members' === object ) {
				object = 'members';
			}

			//if (bp_ajax_request) {
			// bp_ajax_request.abort();
			//}

			// Get directory preferences (called "cookie" for legacy reasons).
			var cookies = {};
			cookies['bp-' + object + '-filter'] = utils.getObjectPreference( object, 'filter' );
			cookies['bp-' + object + '-scope'] = utils.getObjectPreference( object, 'scope' );

			var cookie = encodeURIComponent( $.param( cookies ) );

			return $.post( ajaxurl, {
				action: object + '_filter',
				cookie: cookie,
				object: object,
				filter: filter,
				search_terms: search_terms,
				scope: scope,
				page: page,
				extras: extras,
				template: template
			} );
		},
		updateSitewideNotice: function( id, nonce ) {
			return $.post( ajaxurl, {
				action: 'messages_close_notice',
				notice_id: id,
				nonce: nonce
			} );
		}
	};
}( jQuery ) );
