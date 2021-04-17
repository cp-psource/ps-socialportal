( function( $ ) {
	window.CB = typeof CB === 'undefined' ? {} : CB;

	var DOM = CB.DOM || {};

	/**
	 * Set tab state for the object[member, groups etc].
	 *
	 * @param object
	 */
	CB.DOM = $.extend( DOM, {

		// Clear BP cookies on logout
		logout: function() {
			var isSecure = 'https:' === window.location.protocol;
			var activityCookies = [ 'bp-activity-scope', 'bp-activity-filter', 'bp-activity-oldestpage' ];
			$.each( activityCookies, function( index, cookieName ) {
				$.removeCookie( cookieName, {
					path: '/',
					secure: isSecure
				} );
			} );

			var objects = [ 'members', 'groups', 'blogs', 'forums' ];
			$.each( objects, function( i, object ) {
				$.removeCookie( 'bp-' + object + '-scope', {
					path: '/',
					secure: isSecure
				} );
				$.removeCookie( 'bp-' + object + '-filter', {
					path: '/',
					secure: isSecure
				} );
				$.removeCookie( 'bp-' + object + '-extras', {
					path: '/',
					secure: isSecure
				} );
			} );

			$( 'body' ).trigger( 'cb:user:loggedout' );
		}

	} );

	// Notices manipulation.
	CB.DOM.Notices = {
		prepareNoticeMarkup: function( message, type ) {
			type = type || 'info';
			var notice = '<div id="site-feedback-message" class="site-feedback-message ' + type + '">';
			notice += '<div class="inner">';
			notice += '<div class="bp-template-notice ' + type + '">';
			notice += '<p>' + message + '<i class="fa fa-times-circle-o cb-close-notice" aria-hidden="true"></i>' + '</p>';
			notice += '</div></div></div>';
			return notice;
		},
		append: function( $context, message, type ) {
			if ( ! $context.length ) {
				return;
			}
			$context.prepend( this.prepareNoticeMarkup( message, type ) );
		},
		prepend: function( $context, message, type ) {
			if ( ! $context.length ) {
				return;
			}
			$context.prepend( this.prepareNoticeMarkup( message, type ) );
		},

		hideAll: function() {
			$( '.site-feedback-message' ).remove();
		},
		hide: function( $context ) {
			if ( ! $context.length ) {
				return;
			}
			$context.find( '.site-feedback-message' ).remove();
		}
	};
}( jQuery ) );
