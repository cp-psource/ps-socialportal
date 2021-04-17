( function( $ ) {
	CB.Activity = CB.Activity || {};

	CB.Activity.Requests = {
		// Get filtered Activity with scope and filter.
		get: function( args ) {
			var utils = CB.ItemUtils;

			args = $.extend( {
				scope: null,
				filter: null
			},
			args
			);

			// Save the type and filter
			utils.setObjectPreference( 'activity', 'scope', args.scope );
			utils.setObjectPreference( 'activity', 'filter', args.filter );

			return $.post( ajaxurl, {
				action: 'activity_filter',
				_wpnonce_activity_filter: $( '#_wpnonce_activity_filter' ).val(),
				scope: args.scope,
				filter: args.filter
			} );
		},
		// Get old activities(pages).
		getOld: function( args ) {
			var utils = CB.ItemUtils;
			args = $.extend( {
				action: 'activity_get_older_updates',
				page: 1,
				search_terms: '',
				excluded: '',
				scope: utils.getObjectPreference( 'activity', 'scope' ),
				filter: utils.getObjectPreference( 'activity', 'filter' )
			},
			args
			);

			args.exclude_just_posted = $.isArray( args.excluded ) ? args.excluded.join( ',' ) : args.excluded;
			delete ( args.excluded );

			return $.post( ajaxurl, args );
		},
		// Get single activity.
		getSingle: function( activityID ) {
			return $.post( ajaxurl, {
				action: 'activity_get_single',
				activity_id: activityID
			} );
		},

		// post new activity update.
		postUpdate: function( args ) {
			if ( ! args ) {
				args = {};
			}

			args = $.extend( {
				action: 'post_update',
				content: '',
				object: '',
				item_id: 0,
				since: '',
				_wpnonce: '',
				_bp_as_nonce: ''
			}, args );

			return $.post( ajaxurl, args );
		},

		// delete activity.
		deleteActivity: function( args ) {
			args = $.extend( {
				action: 'activity_delete',
				id: 0,
				_wpnonce: ''
			}, args );
			return $.post( ajaxurl, args );
		},

		// Mark favorite.
		favorite: function( args ) {
			args = $.extend( {
				action: 'activity_mark_fav',
				id: 0,
				_wpnonce: ''
			}, args );

			return $.post( ajaxurl, args );
		},

		// unfavorite activity.
		unfavorite: function( args ) {
			args = $.extend( {
				action: 'activity_mark_unfav',
				id: 0,
				_wpnonce: ''
			}, args );

			return $.post( ajaxurl, args );
		},

		// mark activity spam.
		spam: function( args ) {
			args = $.extend( {
				action: 'activity_mark_spam',
				id: 0,
				_wpnonce: ''
			}, args );
			return $.post( ajaxurl, args );
		},

		// Post activity comment.
		postComment: function( args ) {
			if ( ! args ) {
				args = {};
			}

			args = $.extend( {
				action: 'activity_post_comment',
				comment_id: 0,
				activity_id: 0,
				content: '',
				_wpnonce: '',
				nonce2: ''
			}, args );
			args['_bp_as_nonce_' + args.comment_id] = args.nonce2;
			delete args.nonce2;

			return $.post( ajaxurl, args );
		},

		// delete comments
		deleteComment: function( args ) {
			args = $.extend( {
				action: 'activity_delete_comment',
				id: 0,
				_wpnonce: ''
			}, args );
			return $.post( ajaxurl, args );
		},
		// spam comments
		spamComment: function( args ) {
			args = $.extend( {
				action: 'activity_mark_comment_spam',
				id: 0,
				_wpnonce: ''
			}, args );

			return $.post( ajaxurl, args );
		}
	};
}( jQuery ) );
