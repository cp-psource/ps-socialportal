( function( $ ) {
	$( document ).ready( function() {
		var $siteHeaderRow, blogChecked;
		// Close Flash Notice.
		$( document ).on( 'click', '.cb-close-notice', function() {
			var $feedback = $( this ).parents( '.site-feedback-message' );
			$feedback.slideUp( 150 );
			return false;
		} );

		// fix balloon css pos.
		$siteHeaderRow = $( '#site-header-row-main' );
		if ( $siteHeaderRow.prev( '.site-header-row' ).length ) {
			$siteHeaderRow.find( '.site-header-not-logged-in-link [data-balloon-pos]' ).each( function() {
				$( this ).attr( 'data-balloon-pos', 'up' );// can't use .data() dues to balloons implementation.
			} );
		}

		// Enable Greedy nav for nav tabs(dir & single item ).
		$( '.bp-nav-tabs' ).each( function() {
			var $this = $( this );
			if ( $this.hasClass( 'no-greedy-nav' ) ) {
				return;
			}

			$this.greedyNav( {
				LinksSelector: 'ul',
				buttonLabel: '<i class="fa fa-ellipsis-h" aria-hidden="true"></i>'
			} );
		} );

		//menu.
		$( '.bp-nav-tabs-style-icons-only a' ).each( function() {
			var $this = $( this ),
				$parent = $this.parent();

			$parent.attr( 'aria-label', $this.text() );
			// we can not use data API as the balloon uses attribute for csss.
			$parent.attr( 'data-balloon-pos', 'up' );
			$this.text( '' );
		} );

		if ( typeof WebuiPopovers !== 'undefined' ) {
			$( '.account-nav-item>a, .notifications-nav-item>a' ).webuiPopover( 'destroy' ).webuiPopover(
				{
					content: function() {
						return $( this ).next( '.header-nav-dropdown-links' ).html();
					},
					title: function() {
						return '';
					},
					placement: 'bottom-left',
					style: 'quick-dropdown',
					animation: 'pop'
				}
			);
			// Cog button drop down.
			$( document ).on( 'click', 'a.dropdown-toggle', function() {
				var $this = $( this );
				if ( ! $this.data( 'target' ) ) {
					$this.webuiPopover(
						{
							content: function() {
								return $( this ).next( '.dropdown-menu' ).html();
							},
							title: function() {
								return '';
							},
							style: 'buttonset',
							placement: 'auto-top',
							animation: 'pop'
						}
					);
				}
				$this.webuiPopover( 'show' );
				return false;
			} );
		}

		$( '#wp-admin-bar-logout, #wp-admin-bar-user-logout, a.logout' ).on( 'click', function() {
			CB.DOM.logout();
		} );

		/* Close site wide notices in the sidebar */
		$( document ).on( 'click', '#close-notice', function() {
			var noticeID;
			$( this ).addClass( 'loading' );
			$( '#sidebar div.error' ).remove();

			noticeID = $( '.notice' ).attr( 'rel' ).substr( 2, $( '.notice' ).attr( 'rel' ).length );

			CB.Request.updateSitewideNotice( noticeID, $( '#close-notice-nonce' ).val() ).done( function( response ) {
				$( '#close-notice' ).removeClass( 'loading' );

				if ( response[0] + response[1] === '-1' ) {
					$( '.notice' ).prepend( response.substr( 2, response.length ) );
					$( '#sidebar div.error' ).hide().fadeIn( 200 );
				} else {
					$( '.notice' ).slideUp( 100 );
				}
			} );

			return false;
		} );

		// Register page blog details.
		if ( $( 'body' ).hasClass( 'register' ) ) {
			blogChecked = $( '#signup_with_blog' );

			// hide "Blog Details" block if not checked by default
			if ( ! blogChecked.prop( 'checked' ) ) {
				$( '#blog-details' ).toggle();
			}

			// toggle "Blog Details" block whenever checkbox is checked
			blogChecked.change( function() {
				$( '#blog-details' ).toggle();
			} );
		}
	} );

	// when images are loaded, updated height of columns.
	$( window ).on( 'load', function() {
		CB.DOM.ItemList.updateItemListLayout( 'body' );
	} );

	// on window resize, we need to update the height again.
	$( window ).resize( function() {
		// resizing is only required for equal heights.
		// for masonry, the masonry plugin does it on their own.
		if ( CBBPSettings.itemListDisplayType === 'equalheight' ) {
			CB.DOM.ItemList.updateItemListLayout( 'body' );
		}
	} );
}( jQuery ) );
