( function( CB, $ ) {
	var requests = CB.Messages.Requests;
	var debounce = $.cbDebounce;
	var threadsRequest = null;
	var isCompose = CommunityBuilderBP.isCompose;
	$( document ).ready( function() {
		var $messagesContainer = $( '#bp-messages-view-container' ),
			$messagesVisibleView = $( '#bp-messages-view-visible' ),
			$messagesToolBar = $( '#bp-messages-toolbar' ),
			$messageContents = $( '#bp-messages-list' ),
			$threadsPanel = $( '#bp-threads-panel' ),
			$messagePanel = $( '#bp-messages-panel' ),
			$writePanel = $( '.bp-message-write-panel' ),
			threadsQueryNonce = $threadsPanel.data( 'nonce' ),
			currentThreadId = 0,
			nonce = '';

		// Make the message view visible.
		$messagesVisibleView.removeClass( 'hidden' );
		var url = window.location.href,
			sendtoUserName = bp_get_query_var( 'r', url );

		if ( ! $messagesContainer.length ) {
			return;
		}

		//Setup message loading on thread click.
		$( document ).on( 'click', '.message-thread-entry', function() {
			var thread = $( this );//.parents('.message-thread-entry');

			if ( ! thread.length ) {
				return;
			}

			loadMessage( thread.data( 'thread-id' ), thread.data( 'nonce' ) ).done( function( response ) {
				thread.trigger( 'bp:message:loaded', [ thread, $messageContents, $messagesToolBar, response.data ] );
			} );

			return false;
		} );

		// Setup loading more messages.
		$( document ).on( 'click', '.bp-thread-list .load-more-messages', function() {
			var $this = $( this );
			if ( $this.hasClass( 'loading' ) ) {
				return;
			}

			if ( threadsRequest ) {
				threadsRequest.abort();
			}

			$this.addClass( 'loading' );
			$this.find( 'a' ).text( $this.data( 'text-loading' ) );
			threadsRequest = requests.loadThreads( { page: parseInt( $this.data( 'page' ), 10 ) + 1, _wpnonce: threadsQueryNonce } ).done( function( response ) {
				$this.removeClass( 'loading' );
				$this.find( 'a' ).text( $this.data( 'text-load-more' ) );
				if ( ! response.success ) {
					return;
				}
				$this.hide();
				$this.after( response.data.threads );
				// $this.data('page', response.data.page);
			} );
			return false;
		} );

		// do not allow non js submit.
		$( '#search-message-form' ).on( 'submit', function() {
			return false;
		} );
		// filter messages.
		$( document ).on( 'input', '#messages_search', debounce( function() {
			if ( threadsRequest ) {
				threadsRequest.abort();
			}

			var $this = $( this ),
				$form = $this.closest( 'form' );
			$form.addClass( 'loading' );
			threadsRequest = requests.loadThreads( { page: 1, _wpnonce: threadsQueryNonce, search_terms: $this.val() } ).done( function( response ) {
				$form.removeClass( 'loading' );
				if ( ! response.success ) {
					return;
				}
				var $threads = $( '#bp-thread-list' );
				$threads.fadeOut( 500, function() {
					$threads.html( response.data.threads );
					$threads.fadeIn();
				} );
			} );
			return false;
		}, 150 ) );

		$( document ).on( 'click', '.message-compose', function() {
			if ( $( '.bp-message-send-to-holder' ).length ) {
				return false;
			}

			// reset thread id.
			$messageContents.data( 'thread-id', 0 );
			$writePanel.data( 'thread-id', 0 );

			$messageContents.html( '' );
			$messagesToolBar.html( '' );
			var $messages = $( '#bp-messages-contents' );
			$messages.prepend( $( '#bp-message-send-to-template' ).html() );
			var $input = $messages.find( '.send-to-input' );

			// Add autocomplete to send_to field
			$input.bp_mentions( {
				data: [],
				suffix: ' '
			} );

			return false;
		} );

		if ( isCompose ) {
			$( '.message-compose' ).trigger( 'click' );
		} else {
			// on page load, initialize view(with thread content?).
			loadInitialMessage();
		}
		// Reload current message thread.
		$( document ).on( 'click', '.bp-message-reload', function() {
			var $this = $( this ),
				id = $this.data( 'thread-id' ),
				nonce = $this.data( 'nonce' );

			return false;
		} );
		// delete current thread.
		$( document ).on( 'click', '.message-delete-button', function() {
			var $this = $( this ),
				id = $this.data( 'thread-id' ),
				nonce = bp_get_query_var( '_wpnonce', $this.attr( 'href' ) );

			requests.deleteThread( {
				_wpnonce: nonce,
				id: id
			} ).done( function( response ) {
				if ( response.success ) {
					$this.trigger( 'bp:message:thread:deleted', [ response.data, $this, id ] );
				} else {
					$this.trigger( 'bp:message:thread:delete:failed', [ response.data, $this, id ] );
				}
			} );
			// delete
			// load next or previous

			return false;
		} );

		// On message send(new message or reply).
		$( document ).on( 'click', '#bp-message-send-btn', function() {
			var $this = $( this ),
				$panel = $this.parents( '.bp-message-write-panel' ),
				nonce = $panel.data( 'nonce' ),
				threadID = $panel.data( 'thread-id' );

			var content = $panel.find( '.bp-message-content' ).html();
			content = content.replace( /<br>|<div>/gi, '\n' ).replace( /<\/div>/gi, '' );
			if ( ! content.length ) {
				return false;
			}
			// remove content.
			$panel.find( '.bp-message-content' ).html( '' );

			// It's a new message thread.
			if ( ! threadID || 0 == threadID ) {
				var $send_to = $( '.send-to-input' );
				var send_to = $send_to.val().split( ',' );
				$send_to.val( '' );
				requests.postNewMessage( { _wpnonce: nonce, thread_id: threadID, content: content, send_to: send_to } ).then( function( response ) {
					if ( response.success ) {
						$messageContents.trigger( 'bp:message:new:posted', [ response.data, $messageContents, $messagesToolBar, $panel, $send_to ] );//console.log(response);
					} else {
						$messageContents.trigger( 'bp:message:new:failed', [ response.data, $messageContents, $panel ] );
					}
				} );
				return false;
			}

			// It's a reply.
			requests.postReply( { _wpnonce: nonce, thread_id: threadID, content: content } ).then( function( response ) {
				if ( response.success ) {
					$messageContents.trigger( 'bp:message:reply:posted', [ response.data, $messageContents, $panel, $messagePanel ] );//console.log(response);
				} else {
					$messageContents.trigger( 'bp:message:reply:failed', [ response.data, $messageContents, $panel ] );
				}
			} );

			//    var currentThreadId =
			//  requests.post
			return false;
		} );

		// Load first or current message on page load.
		function loadInitialMessage() {
			// parse the current threadID for single View.
			if ( parseInt( $messageContents.data( 'thread-id' ), 10 ) ) {
				currentThreadId = $messageContents.data( 'thread-id' );
				nonce = $messageContents.data( 'nonce' );
			} else {
				currentThreadId = $( '#bp-thread-list' ).find( '.message-thread-entry' ).first().data( 'thread-id' );
			}

			// only load if there is something to load.
			if ( parseInt( currentThreadId, 10 ) <= 0 || undefined === currentThreadId ) {
				$( '.message-compose' ).trigger( 'click' );
				return;
			}

			loadMessage( currentThreadId, nonce ).then( function( response ) {
				if ( ! response.success ) {
					return response;
				}

				var thread = $( '#bp-thread-list' ).find( '#m-' + currentThreadId );

				if ( thread.length ) {
					thread.trigger( 'bp:message:loaded:first', [ thread, $messageContents, $messagesToolBar, response.data ] );
				} else {
					$messageContents.trigger( 'bp:message:loaded:first', [ thread, $messageContents, $messagesToolBar, response.data ] );
				}
			} );
		}

		// Load a message and keep ui updated.
		function loadMessage( threadID, nonce ) {
			$( '.bp-message-send-to-holder' ).remove();
			var thread = $( '#bp-thread-list' ).find( '#m-' + threadID );
			// remove from all thread.
			$( '.message-thread-entry' ).removeClass( 'current-thread' );

			if ( thread.length ) {
				nonce = thread.data( 'nonce' );
			} else {
				$messagesToolBar.hide();
				return $.Deferred().reject( {} );
			}

			thread.addClass( 'current-thread loading' );
			$messagesToolBar.addClass( 'loading' );

			return requests.loadThread( { id: threadID, _wpnonce: nonce } ).done( function( response ) {
				$messagesToolBar.removeClass( 'loading' );
				thread.removeClass( 'loading' );
				if ( ! response.success ) {
					return response;
				}
				$messageContents.data( 'thread-id', response.data.thread_id );
				$writePanel.data( 'thread-id', response.data.thread_id );

				$messageContents.html( response.data.messages );
				$messagesToolBar.html( response.data.info );
				// first load is different.
				// rename vent later.
				return response;
			} );
		}

		$( document ).on( 'bp:message:thread:deleted', function( evt, data, $btn, id ) {
			var thread = $threadsPanel.find( '#m-' + id );
			if ( ! thread.length ) {
				loadInitialMessage();
				return;
			}

			var $next = thread.next();
			thread.remove();
			if ( ! $next.length ) {
				loadInitialMessage();
				return;
			}
			// if we are here, Load the next message.
			loadMessage( $next.data( 'thread-id' ), $next.data( 'nonce' ) );
		} );

		// thread.trigger('bp:message:loaded:first', [thread, $messageContents, $messagesToolBar, response.data]);
	} ); //end of dom ready.

	// scroll to contents.
	$( document ).on( 'bp:message:loaded.scrollToContents', function( evt, thread, $messageContents, $messagesToolBar, responseData ) {
		$( 'html,body' ).animate( { scrollTop: $messagesToolBar.offset().top - 100 }, 'slow' );
	} );

	// On reply posted, add it to the bottom of the message list.
	$( document ).on( 'bp:message:reply:posted', function( evt, data, $messageContents, $panel, $messagePanel ) {
		var $el = $( data.contents ).appendTo( $messageContents.find( '#message-thread' ) );//.append();
		/* var offsetPanel = $panel.offset().top;
        var elOffset = $el.offset().top;
        console.log(elOffset, offsetPanel);
        // needs scrolling.
        if( elOffset> offsetPanel) {
            var offsetLength = elOffset - offsetPanel - 200;
            var offset = $('#bp-messages-list').offset().top - offsetLength;
        var $m = $('#bp-messages-list');
           var offset = $m.offset().top - $m.offsetParent().offset().top;

            console.log(offset);
            $m.offset({ top: offset});
        }*/
	} );

	$( document ).on( 'bp:message:new:posted', function( evt, data, $messageContents, $messagesToolBar, $panel, $send_to ) {
		$messageContents.data( 'thread-id', data.thread_id );
		$panel.data( 'thread-id', data.thread_id );

		$messageContents.html( data.messages );
		$messagesToolBar.html( data.info );
		$( '.bp-message-send-to-holder' ).remove();
	} );
}( CB || {}, jQuery ) );
