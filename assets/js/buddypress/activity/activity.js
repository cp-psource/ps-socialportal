( function( CB, $ ) {
	var newestActivities = '',
		activityOldestPage = 1,
		activityLastRecorded = 0,
		currentRequest;

	// Initialize or hooks for post/comment forms submission.
	CB.Activity.PostFilter = $.Callbacks();
	CB.Activity.CommentFilter = $.Callbacks();

	// on dom ready.
	$( document ).ready( function() {
		var requests = CB.Activity.Requests,
			dom = CB.Activity.DOM;

		// on page load, check for @mention(public message) and update form status.
		$( document ).on( 'loaded:activity:post:form', function( evt, $form ) {
			var $editor = $form.find( '.bp-activity-post-editor' ),
				$postInOptions = $form.find( '.bp-activity-post-in-options' ),
				$memberNicename;
			// Hide actions.
			$form.find( '.bp-activity-post-form-actions' ).hide();

			if ( $editor.length && bp_get_querystring( 'r' ) ) {
				$memberNicename = $editor.html();
				$memberNicename = $memberNicename.replace( /<br>|<div>/gi, '' ).replace( /<\/div>/gi, '' );
				$postInOptions.slideDown();

				$.scrollTo( $form, 500, {
					offset: -125,
					easing: 'swing'
				} );

				//$textarea.html('');
				// There may be more than 1 editor.
				$editor.each( function() {
					if ( $( this ).hasClass( 'emojionearea' ) ) {
						return;
					}

					$( this ).focus().html( $memberNicename );
				} );
			} else {
				$postInOptions.hide();
			}
		} );

		// we are binding multiple handlers to easily allow toggle individual feature off from a plugin or child theme.
		$( document ).on( 'focus:activity:post:content.showPostOptions', function( evt, $editor, $form ) {
			$form.find( '.bp-activity-post-in-options' ).show();
			$form.find( '.bp-activity-post-form-actions' ).slideDown();
		} );

		// updateFormState.
		$( document ).on( 'focus:activity:post:content.updateFormState', function( evt, $editor, $form ) {
			if ( ! $form.length ) {
				return;
			}

			var $editorContainer = $form.find( '.bp-activity-post-editor-container' );

			$editorContainer.addClass( 'active' );
			$form.addClass( 'active' );

			if ( $form.hasClass( 'submitted' ) ) {
				$form.removeClass( 'submitted' );
			}
		} );

		// Reset activity filters & Tabs on content area focus.
		$( document ).on( 'focus:activity:post:content.resetFilters', function( evt, $editor, $form ) {
			var $allActivityTab = $( '#activity-all' ),
				$filter = $( '#activity-filter-select select' );

			// Return to the 'All Members' tab and 'Everything' filter,
			// to avoid inconsistencies with the heartbeat integration
			if ( $allActivityTab.length ) {
				if ( ! $allActivityTab.hasClass( 'selected' ) ) {
					// reset to All tabs and simulate click.
					$filter.val( '-1' );
					$allActivityTab.find( 'a' ).trigger( 'click' );
				} else if ( '-1' !== $filter.val() ) {
					// reset filters.
					$filter.val( '-1' );
					$filter.trigger( 'change' );
				}
			}
		} );

		// on focusout content area..
		$( document ).on( 'focusout:activity:post:content.updateFormState', function( evt, $editor, $form ) {
			if ( ! $form.length ) {
				return;
			}

			var $editorContainer = $form.find( '.bp-activity-post-editor-container' );

			$editorContainer.removeClass( 'active' );
			$form.removeClass( 'active' );
		} );

		// On cancel posting.
		$( document ).on( 'click:activity:post:cancel', function( evt, $button, $form ) {
			$form.find( '.bp-activity-post-in-options' ).hide();
			$form.find( '.bp-activity-post-form-actions' ).slideUp();
			// should we reset the contents too?
		} );

		// on activity publish clicking.
		$( document ).on( 'click:activity:publish', function( evt, $button, $form ) {
			var lastDateRecorded = 0,
				inputs = {},
				postData,
				object = '',
				itemID,
				$postBox,
				content,
				$firstRow,
				$activityRow,
				timestamp = null;

			// If you need to send extra data with the request
			// Please hook to CB.Activity.PostFilter.

			$form.trigger( 'before:publish:activity:request', [ $form ] );

			// Default POST values
			itemID = $form.find( '.bp-activity-post-object-id' ).val();
			$postBox = $form.find( '.bp-activity-post-editor' ).first();
			// Transform emoji image into emoji unicode
			$postBox.find( 'img.emojioneemoji' ).replaceWith( function() {
				return this.dataset.emojiChar;
			} );
			content = $postBox.html();
			content = content.replace( /<br>|<div>/gi, '\n' ).replace( /<\/div>/gi, '' );

			$firstRow = $( '.activity-list li' ).first();

			$activityRow = $firstRow;

			// Checks if at least one activity exists
			if ( $firstRow.length ) {
				if ( $activityRow.hasClass( 'load-newest' ) ) {
					$activityRow = $firstRow.next();
				}
				timestamp = $activityRow.prop( 'class' ).match( /date-recorded-([0-9]+)/ );
			}

			if ( timestamp ) {
				lastDateRecorded = timestamp[1];
			}

			/* Set object for non-profile posts */
			if ( itemID > 0 ) {
				object = $form.find( '.bp-activity-post-object' ).val();
			}

			postData = $.extend( {
				content: content,
				object: object,
				item_id: itemID,
				since: lastDateRecorded,
				_wpnonce: $( '#_wpnonce_post_update' ).val(),
				_bp_as_nonce: $( '#_bp_as_nonce' ).val() || ''
			}, inputs );

			// Let the hooked functions modify request data.
			CB.Activity.PostFilter.fire( postData );
			// Post.
			requests.postUpdate( postData ).done( function( response ) {
				$form.find( 'textarea,input' ).each( function() {
					$( this ).prop( 'disabled', false );
				} );

				// Check for errors and append if found.
				if ( ! response.success ) {
					$form.removeClass( 'submitted loading' );
					CB.DOM.Notices.prepend( $form, response.data.message, 'error' );
					$form.find( '.error' ).hide().fadeIn( 200 );
					$form.trigger( 'error:publish:activity', [ response.data, $form ] );
				} else {
					// should we use success:publish:activity instead?
					$form.trigger( 'published:activity', [ response.data, $form ] );
				}
			} );
		} );

		// disable the post buttons before posting.
		$( document ).on( 'before:publish:activity:request.updateFormState', function( evt, $form ) {
			$form.find( 'textarea,input' ).each( function() {
				$( this ).prop( 'disabled', true );
			} );

			// Remove any errors
			$( 'div.error' ).remove();
			$form.find( '.bp-activity-post-submit' ).prop( 'disabled', true );
			$form.addClass( 'submitted' ).find( '.bp-activity-post-form-actions' ).addClass( 'loading' );
		} );

		// update form state after successfully publishing activity .
		$( document ).on( 'published:activity.updateFormState', function( evt, response, $form ) {
			var contentWrapper = $form.find( '.bp-activity-post-editor-container' ),
				button = $form.find( '.bp-activity-post-submit' );

			button.prop( 'disabled', false ).removeClass( 'loading' );
			$form.removeClass( 'submitted' ).find( '.bp-activity-post-form-actions' ).removeClass( 'loading' );
			contentWrapper.removeClass( 'active' );
		} );
		// update form state after error in  publishing activity .
		$( document ).on( 'error:publish:activity.updateFormState', function( evt, response, $form ) {
			var contentWrapper = $form.find( '.bp-activity-post-editor-container' ),
				button = $form.find( '.bp-activity-post-submit' );

			button.prop( 'disabled', false ).removeClass( 'loading' );
			$form.removeClass( 'submitted' ).find( '.bp-activity-post-form-actions' ).removeClass( 'loading' );
			//contentWrapper.removeClass( 'active' );
		} );

		// Add to activity stream on publish.
		// We are using a different callback to allow plugins detach and add their own implementations if needed.
		$( document ).on( 'published:activity.appendToList', function( evt, response, form ) {
			var lastDateRecorded = 0,
				$firstRow;

			// activity list not present.
			if ( ! $( '.activity-list' ).length ) {
				$( '.error' ).slideUp( 100 ).remove();
				$( '#message' ).slideUp( 100 ).remove();
				$( 'div.activity' ).append( '<ul id="activity-stream" class="activity-list item-list">' );
			}
			$firstRow = $( '.activity-list li' ).first();

			if ( $firstRow.hasClass( 'load-newest' ) ) {
				$firstRow.remove();
			}

			$( '#activity-stream' ).prepend( response.contents );

			if ( ! lastDateRecorded ) {
				$( '.activity-list li:first' ).addClass( 'new-update just-posted' );
			}

			$( 'li.new-update' ).hide().slideDown( 300 ).removeClass( 'new-update' );
			form.find( '.bp-activity-post-editor' ).each( function() {
				if ( ! $( this ).hasClass( 'emojionearea' ) ) {
					$( this ).html( '' );
				}
			} );
			// reset vars to get newest activities
			newestActivities = '';
			activityLastRecorded = 0;
		} );

		// Default implementations for the activity action. Use off() to remove and then add your own.
		// on activity tab click.
		$( document ).on( 'click:activity:tab', function( evt, target, scope, filter ) {
			dom.initActivityFilter( scope, filter );

			requests.get( { scope: scope, filter: filter } ).done( function( response ) {
				dom.updateFilteredActivities( response );
			} );

			return false;
		} );

		// On Activities filter change(selectbox).
		$( document ).on( 'change:activity:filter', function( evt, target, scope, filter ) {
			dom.initActivityFilter( scope, filter );

			if ( currentRequest ) {
				currentRequest.abort();
			}

			currentRequest = requests.get( { scope: scope, filter: filter } ).done( function( data ) {
				dom.updateFilteredActivities( data );
			} );
		} );

		$( document ).on( 'click:activity:favorite', function( evt, target, activity, activityID, nonce ) {
			if ( target.hasClass( 'loading' ) ) {
				return false;
			}

			target.addClass( 'loading' );

			requests.favorite( { id: activityID, _wpnonce: nonce } ).done( function( response ) {
				var $favTab = $( 'item-list-tabs ul #activity-favorites' );
				target.removeClass( 'loading' );
				if ( ! response.success ) {
					activity.prepend( response.message );
					return;
				}

				target.fadeOut( 200, function() {
					$( this ).html( response.data.label );
					$( this ).attr( 'title', response.data.title );
					$( this ).fadeIn( 200 );
				} );

				// if favourite tab exists, update count.
				if ( $favTab.length ) {
					$favTab.find( 'span' ).html( Number( $favTab.find( 'span' ).html() ) + 1 );
				}

				target.removeClass( 'fav' );
				target.addClass( 'unfav' );
			} );
		} );

		$( document ).on( 'click:activity:unfavorite', function( evt, target, activity, activityID, nonce ) {
			if ( target.hasClass( 'loading' ) ) {
				return false;
			}

			target.addClass( 'loading' );

			requests.unfavorite( { id: activityID, _wpnonce: nonce } ).done( function( response ) {
				var $favTab = $( '.item-list-tabs ul #activity-favorites' );
				target.removeClass( 'loading' );

				if ( ! response.success ) {
					activity.prepend( response.message );
					return;
				}

				target.fadeOut( 200, function() {
					$( this ).html( response.data.label );
					$( this ).attr( 'title', response.data.title );
					$( this ).fadeIn( 200 );
				} );
				target.removeClass( 'unfav' );
				target.addClass( 'fav' );
				if ( $favTab.length ) {
					$favTab.find( 'span' ).html( Number( $favTab.find( 'span' ).html() ) - 1 );
				}

				if ( ! Number( $favTab.find( 'span' ).html() ) ) {
					// load All activity tab.
					if ( $favTab.hasClass( 'selected' ) ) {
						requests.get( { scope: null, filter: null } ).done( function( response ) {
							dom.updateFilteredActivities( response );
						} );
					}

					$favTab.remove();
				}

				if ( 'activity-favorites' === $( '.item-list-tabs li.selected' ).attr( 'id' ) ) {
					activity.slideUp( 100 );
				}
			} );

			return false;
		} );

		$( document ).on( 'click:activity:delete', function( evt, target, activity, activityID, timestamp, nonce ) {
			if ( target.hasClass( 'loading' ) ) {
				return false;
			}

			target.addClass( 'loading' );

			requests.deleteActivity( { id: activityID, _wpnonce: nonce } ).done( function( response ) {
				if ( ! response.success ) {
					CB.DOM.Notices.prepend( activity, response.data.message, 'error' );
					activity.find( '.error' ).hide().fadeIn( 300 );
				} else {
					activity.slideUp( 300 );

					// reset vars to get newest activities
					if ( timestamp && activityLastRecorded === timestamp[1] ) {
						newestActivities = '';
						activityLastRecorded = 0;
					}
				}
			} );
		} );

		$( document ).on( 'click:activity:spam', function( evt, target, activity, activityID, timestamp, nonce ) {
			if ( target.hasClass( 'loading' ) ) {
				return false;
			}

			// Spam activity stream items.
			target.addClass( 'loading' );

			requests.spam( { id: activityID, _wpnonce: nonce } ).done( function( response ) {
				if ( ! response.success ) {
					CB.DOM.Notices.prepend( activity, response.data.message, 'error' );
					activity.find( '.error' ).hide().fadeIn( 300 );
					return;
				}
				activity.slideUp( 300 );
				// reset vars to get newest activities
				if ( timestamp && activityLastRecorded === timestamp[1] ) {
					newestActivities = '';
					activityLastRecorded = 0;
				}
			} );

			return false;
		} );

		// Activity "Read More" links
		$( document ).on( 'click:activity:readmore', function( event, target, activityID, type ) {
			var innerClass, $activityInner;

			innerClass = type === 'acomment' ? 'acomment-inner' : 'activity-inner';
			$activityInner = $( '#' + type + '-' + activityID + ' .' + innerClass + ':first' );
			$( target ).addClass( 'loading' );

			requests.getSingle( activityID ).done( function( response ) {
				if ( ! response.success ) {
					if ( response.data.redirect ) {
						window.location.href = response.data.redirect;
					}
					return;
				}
				$activityInner.slideUp( 300 ).html( response.data.contents ).slideDown( 300 );
			} );

			return false;
		} );

		// Load More.
		$( document ).on( 'click:activity:loadmore', function( evt, target ) {
			var loadMoreSearch,
				justPosted,
				oldestPage;

			// Load more updates at the end of the page
			if ( currentRequest ) {
				currentRequest.abort();
			}

			target.parent( 'li' ).addClass( 'loading' );

			justPosted = [];

			$( '.activity-list li.just-posted' ).each( function() {
				justPosted.push( $( this ).attr( 'id' ).replace( 'activity-', '' ) );
			} );

			loadMoreSearch = bp_get_querystring( 's' );
			oldestPage = activityOldestPage + 1;

			currentRequest = requests.getOld( { page: oldestPage, search_terms: loadMoreSearch, excluded: justPosted } ).done( function( response ) {
				target.parent( 'li' ).removeClass( 'loading' );
				activityOldestPage = oldestPage;
				target.parents( '.activity-list' ).append( response.data.contents );

				target.parent().hide();
			} );

			return false;
		} );

		$( document ).on( 'click:activity:loadnew', function( event, target ) {
			var activityHTML;
			// Load newest updates at the top of the list
			event.preventDefault();

			target.parent().hide();

			/**
			 * If a plugin is updating the recorded_date of an activity
			 * it will be loaded as a new one. We need to look in the
			 * stream and eventually remove similar ids to avoid "double".
			 */
			activityHTML = $.parseHTML( newestActivities );
			$.each( activityHTML, function( i, el ) {
				if ( 'LI' === el.nodeName && $( el ).hasClass( 'just-posted' ) ) {
					if ( $( '#' + $( el ).attr( 'id' ) ).length ) {
						$( '#' + $( el ).attr( 'id' ) ).remove();
					}
				}
			} );

			// Now the stream is cleaned, prepend newest
			target.parents( '.activity-list' ).prepend( newestActivities );

			// reset the newest activities now they're displayed
			newestActivities = '';
			return false;
		} );

		// Escape Key Press for cancelling comment forms
		$( document ).keydown( function( e ) {
			var element, keyCode;
			e = e || window.event;
			if ( e.target ) {
				element = e.target;
			} else if ( e.srcElement ) {
				element = e.srcElement;
			}

			if ( element.nodeType === 3 ) {
				element = element.parentNode;
			}

			if ( e.ctrlKey === true || e.altKey === true || e.metaKey === true ) {
				return;
			}

			keyCode = ( e.keyCode ) ? e.keyCode : e.which;

			if ( keyCode === 27 ) {
				if ( element.tagName === 'TEXTAREA' ) {
					if ( $( element ).hasClass( 'ac-input' ) ) {
						$( element ).parent().parent().parent().slideUp( 200 );
					}
				}
			}
		} );

		//========================= Copy & Paste for now from legacy. Improve later ==========//

		// Activity HeartBeat ************************************************

		// Set the interval and the namespace event
		if ( typeof wp !== 'undefined' && typeof wp.heartbeat !== 'undefined' && typeof CBBPSettings.pulse !== 'undefined' ) {
			wp.heartbeat.interval( Number( CBBPSettings.pulse ) );

			$.fn.extend( {
				'heartbeat-send': function() {
					return this.bind( 'heartbeat-send.buddypress' );
				}
			} );
		}

		// Set the last id to request after
		var firstItemRecorded = 0,
			lastRecordedSearch = '',
			timestamp = null;

		$( document ).on( 'heartbeat-send.buddypress', function( e, data ) {
			firstItemRecorded = 0;

			// First row is default latest activity id
			if ( $( '.activity-list li' ).first().prop( 'id' ) ) {
				// getting the timestamp
				timestamp = $( '.activity-list li' ).first().prop( 'class' ).match( /date-recorded-([0-9]+)/ );

				if ( timestamp ) {
					firstItemRecorded = timestamp[1];
				}
			}

			if ( 0 === activityLastRecorded || Number( firstItemRecorded ) > activityLastRecorded ) {
				activityLastRecorded = Number( firstItemRecorded );
			}

			data.bp_activity_last_recorded = activityLastRecorded;

			lastRecordedSearch = bp_get_querystring( 's' );

			if ( lastRecordedSearch ) {
				data.bp_activity_last_recorded_search_terms = lastRecordedSearch;
			}
		} );

		// Increment newest_activities and activity_last_recorded if data has been returned
		$( document ).on( 'heartbeat-tick.cb', function( e, data ) {
			// Only proceed if we have newest activities.
			if ( ! data.bp_activity_newest_activities ) {
				return;
			}

			newestActivities = data.bp_activity_newest_activities.activities + newestActivities;
			activityLastRecorded = Number( data.bp_activity_newest_activities.last_recorded );

			$( document ).trigger( 'heartbeat:activity:new', [ newestActivities, activityLastRecorded ] );
		} );

		$( document ).on( 'heartbeat:activity:new', function( evt, newestActivities, activityLastRecorded ) {
			if ( $( '.activity-list li' ).first().hasClass( 'load-newest' ) ) {
				return;
			}

			$( '.activity-list' ).prepend( '<li class="load-newest"><a href="#newest">' + CBBPSettings.newest + '</a></li>' );
		} );
	} ); // end of dom ready.
}( CB || {}, jQuery ) );
