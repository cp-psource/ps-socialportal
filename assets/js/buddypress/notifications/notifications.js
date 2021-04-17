( function( $ ) {
	var requests = CB.Notifications.Requests;

	$( document ).ready( function() {
		var $selectedTab, ids, scope, $nContainer;
		// on bulk select, enable toolbar.
		$( document ).on( 'click', '#select-all-notifications', function() {
			toggleToolbar( $( this ).closest( '.notifications-container' ) );
		} );

		//on multiple select, check if we should enable/disable.
		$( document ).on( 'click', '.notifications-container .notification-check', function() {
			toggleToolbar( $( this ).closest( '.notifications-container' ) );
		} );
		// Selecting/Deselecting all notifications
		$( '#select-all-notifications' ).click( function( event ) {
			if ( this.checked ) {
				$( '.notification-check' ).each( function() {
					this.checked = true;
				} );
			} else {
				$( '.notification-check' ).each( function() {
					this.checked = false;
				} );
			}
		} );

		// refresh
		$selectedTab = $( '#subnav' ).find( '.current' );
		// since I don't know who decided to create a mess with notification tabs html, we will need to do the manual thing here

		ids = $selectedTab.length ? $selectedTab.attr( 'id' ).split( '-' ) : [];

		if ( ! ids.length ) {
			scope = 'all';
		} else if ( 'notifications' === ids[0] ) {
			scope = ids[1];
		} else {
			scope = ids[0];
		}
		// reset scope.
		if ( 'my' === scope ) {
			scope = 'unread';// what a mess.
		}

		$nContainer = $( '.notifications-container' );

		if ( $nContainer.find( '.notification-entry' ).length ) {
			$nContainer.find( '.visible-on-load' ).removeClass( 'visible-on-load' );
		}

		// save min notification id.
		updateMinID( $nContainer );

		// Refresh
		$( document ).on( 'click', '.notification-actions-toolbar a', function() {
			var $this = $( this ),
				$list = $this.closest( '.notifications-container' ),
				nonce = $list.data( 'nonce' );
			if ( $this.hasClass( 'reload' ) ) {
				$this.trigger( 'click:notifications:reload', [ $this, $list, scope, nonce ] );
			} else if ( $this.hasClass( 'bulk-delete' ) ) {
				$this.trigger( 'click:notifications:delete:bulk', [ $this, $list, scope, nonce ] );
			} else if ( $this.hasClass( 'mark-read' ) ) {
				$this.trigger( 'click:notifications:mark:read:bulk', [ $this, $list, scope, nonce ] );
			} else if ( $this.hasClass( 'mark-unread' ) ) {
				$this.trigger( 'click:notifications:mark:unread:bulk', [ $this, $list, scope, nonce ] );
			}
			// for now, let us disable event propagation. In future, we may want to do it for known actions only.
			return false;
		} );

		// Refresh
		$( document ).on( 'click', '.notification-actions a', function() {
			var $this = $( this ),
				$entry = $this.closest( '.notification-entry' ),
				$list = $this.closest( '.notifications-container' ),
				nonce = $list.data( 'nonce' ),
				id = $entry.data( 'id' );
			if ( $this.hasClass( 'delete' ) ) {
				$this.trigger( 'click:notification:delete', [ $this, $entry, $list, id, scope, nonce ] );
			} else if ( $this.hasClass( 'mark-read' ) ) {
				$this.trigger( 'click:notification:mark:read', [ $this, $entry, $list, id, scope, nonce ] );
			} else if ( $this.hasClass( 'mark-unread' ) ) {
				$this.trigger( 'click:notification:mark:unread', [ $this, $entry, $list, id, scope, nonce ] );
			}
			// for now, let us disable event propagation. In future, we may want to do it for known actions only.
			return false;
		} );

		$( document ).on( 'click', '.notifications-container .load-more', function() {
			var $this = $( this ),
				$list = $this.closest( '.notifications-container' ),
				nonce = $list.data( 'nonce' );
			$this.trigger( 'click:notifications:load:more', [ $this, $list, scope, nonce ] );
			return false;
		} );
	} ); // end of domready.

	$( document ).on( 'click:notifications:load:more', function( evt, $button, $list, scope, nonce ) {
		$button.addClass( 'loading' );
		loadNextN( $list, 20, scope, nonce ).then( function() {
			$button.removeClass( 'loading' );
		} );
	} );

	$( document ).on( 'click:notifications:reload', function( evt, $action, $list, scope, nonce ) {
		var $toolbar = $list.find( '.notification-actions-toolbar' );
		$toolbar.addClass( 'loading' );
		requests.get( {
			page: 1,
			scope: scope,
			_wpnonce: nonce
		} ).then( function( response ) {
			var $entries;
			$toolbar.removeClass( 'loading' );
			if ( ! response.success ) {
				$list.prepend( response.data.message );
				return;
			}
			$entries = $list.find( '.notifications-list' );
			$entries.fadeOut( 500, function() {
				$entries.html( response.data.contents );
				$entries.fadeIn( 500 );
				updateMinID( $list );
				checkAndHideLoadMore( $list, response );
			} );
		} );
	} );

	$( document ).on( 'click:notifications:delete:bulk', function( evt, $action, $list, scope, nonce ) {
		var $toolbar = $list.find( '.notification-actions-toolbar' );
		$toolbar.addClass( 'loading' );
		requests.deleteAll( {
			ids: getSelectedItems( $list ),
			_wpnonce: nonce
		} ).then( function( response ) {
			$toolbar.removeClass( 'loading' );
			if ( ! response.success ) {
				$list.prepend( response.data.message );
				return;
			}

			$.each( response.data.ids, function( index, value ) {
				removeItem( $list, value );
			} );
			// load n Enetris
			loadNextN( $list, response.data.ids.length, scope, nonce );
		} );
	} );

	$( document ).on( 'click:notifications:mark:read:bulk', function( evt, $action, $list, scope, nonce ) {
		var $toolbar = $list.find( '.notification-actions-toolbar' );
		$toolbar.addClass( 'loading' );
		requests.markAllRead( {
			ids: getSelectedItems( $list ),
			_wpnonce: nonce
		} ).then( function( response ) {
			var removeEntry;
			$toolbar.removeClass( 'loading' );
			if ( ! response.success ) {
				$list.prepend( response.data.message );
				return;
			}
			removeEntry = scope === 'unread';

			$.each( response.data.ids, function( index, id ) {
				$list.find( '#notification-entry-' + id ).removeClass( 'unread-notification' ).addClass( 'read-notification' );
				if ( removeEntry ) {
					removeItem( $list, id );
				}
			} );

			if ( removeEntry ) {
				loadNextN( $list, response.data.ids.length, scope, nonce );
			}
		} );
	} );

	$( document ).on( 'click:notifications:mark:unread:bulk', function( evt, $action, $list, scope, nonce ) {
		var $toolbar = $list.find( '.notification-actions-toolbar' );
		$toolbar.addClass( 'loading' );
		requests.markAllUnread( {
			ids: getSelectedItems( $list ),
			_wpnonce: nonce
		} ).then( function( response ) {
			var removeEntry;
			$toolbar.removeClass( 'loading' );
			if ( ! response.success ) {
				$list.prepend( response.data.message );
				return;
			}
			removeEntry = scope === 'read';

			$.each( response.data.ids, function( index, id ) {
				$list.find( '#notification-entry-' + id ).removeClass( 'read-notification' ).addClass( 'unread-notification' );
				if ( removeEntry ) {
					removeItem( $list, id );
				}
			} );

			if ( removeEntry ) {
				loadNextN( $list, response.data.ids.length, scope, nonce );
			}
		} );
	} );

	$( document ).on( 'click:notification:mark:read', function( evt, $button, $entry, $list, id, scope, nonce ) {
		$button.addClass( 'loading' );
		requests.markRead( {
			id: id,
			_wpnonce: nonce
		} ).then( function( response ) {
			$button.removeClass( 'loading' );
			if ( ! response.success ) {
				$entry.append( response.data.message );
				return;
			}
			$entry.removeClass( 'unread-notification' ).addClass( 'read-notification' );

			if ( scope === 'unread' ) {
				removeItem( $list, id );
				loadNextN( $list, 1, scope, nonce );
			}
		} );
	} );

	$( document ).on( 'click:notification:mark:unread', function( evt, $button, $entry, $list, id, scope, nonce ) {
		$button.addClass( 'loading' );
		requests.markUnread( {
			id: id,
			_wpnonce: nonce
		} ).then( function( response ) {
			$button.removeClass( 'loading' );
			if ( ! response.success ) {
				$entry.append( response.data.message );
				return;
			}
			$entry.removeClass( 'read-notification' ).addClass( 'unread-notification' );

			if ( scope === 'read' ) {
				removeItem( $list, id );
				loadNextN( $list, 1, scope, nonce );
			}
		} );
	} );

	$( document ).on( 'click:notification:delete', function( evt, $button, $entry, $list, id, scope, nonce ) {
		$button.addClass( 'loading' );
		requests.deleteOne( {
			id: id,
			_wpnonce: nonce
		} ).then( function( response ) {
			$button.removeClass( 'loading' );
			if ( ! response.success ) {
				$entry.append( response.data.message );
				return;
			}

			removeItem( $list, id );
			loadNextN( $list, 1, scope, nonce );
		} );
	} );

	// show toolbar if one or more item is checked, else hide.
	function toggleToolbar( $list ) {
		var $toolbar = $list.find( '.bulk-toolbar-options' );

		var selectedCount = $list.find( '.notification-check:checked' ).length;
		if ( selectedCount > 0 ) {
			$toolbar.removeClass( 'toolbar-hidden' ).addClass( 'toolbar-visible' );
		} else {
			$toolbar.removeClass( 'toolbar-visible' ).addClass( 'toolbar-hidden' );
		}
	}

	// Remove an entry from list.
	function removeItem( $list, id ) {
		$list.find( '#notification-entry-' + id ).slideUp( 500, function() {
			$( this ).remove();
		} );
	}

	// Load next N entries.
	function loadNextN( $list, n, scope, nonce ) {
		return requests.get( {
			page: 1,
			next: n,
			id: $list.data( 'min-id' ),
			scope: scope,
			_wpnonce: nonce
		} ).then( function( response ) {
			var $entries;
			if ( ! response.success ) {
				// fail silently.
				return;
			}

			$entries = $list.find( '.notifications-list' );
			$entries.append( response.data.contents );
			updateMinID( $list );
			checkAndHideLoadMore( $list, response );
			return response;
		} );
	}

	function checkAndHideLoadMore( $list, response ) {
		if ( ! response.data.has_more ) {
			$list.find( '.load-more-wrapper' ).slideUp( 500 );
		}
	}

	// loop through the list and save min id.
	function updateMinID( $nContainer ) {
		var minID = 0;
		$nContainer.find( '.notifications-list .notification-entry' ).each( function() {
			var id = $( this ).data( 'id' );
			if ( ! minID ) {
				minID = id;
			}

			if ( minID > id ) {
				minID = id;
			}
		} );

		$nContainer.data( 'min-id', minID );
	}

	// Get checked notifications.
	function getSelectedItems( $list ) {
		return $list.find( '.notification-check:checked' ).map( function() {
			return $( this ).val();
		} ).get();
	}
}( jQuery ) );
