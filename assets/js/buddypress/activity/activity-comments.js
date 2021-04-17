( function( $ ) {
	$( document ).ready( function() {
		var requests = CB.Activity.Requests,
			dom = CB.Activity.DOM;

		// Hide all activity comment forms
		$( 'form.ac-form' ).hide();

		// Hide excess comments.
		dom.hideComments();

		$( document ).on( 'click:activity:comment:showall', function( evt, $target ) {
			/* Showing hidden comments - pause for half a second */
			$target.parent().addClass( 'loading' );

			setTimeout( function() {
				$target.parent().parent().children( 'li' ).fadeIn( 200, function() {
					$target.parent().remove();
				} );
			}, 600 );
		} );

		// Comments.
		$( document ).on( 'click:activity:reply', function( evt, target, activityID, commentID, type ) {
			// Comment / comment reply links
			var $form = $( '#ac-form-' + activityID );

			$form.css( 'display', 'none' );
			$form.removeClass( 'root' );
			// hide all comment forms.
			$( '.ac-form' ).hide();

			// Hide any error messages
			$form.find( '.error' ).hide();

			if ( type !== 'comment' ) {
				$( '#acomment-' + commentID ).append( $form );
			} else {
				$( '#activity-' + activityID + ' .activity-comments' ).append( $form );
			}

			if ( $form.parent().hasClass( 'activity-comments' ) ) {
				$form.addClass( 'root' );
			}

			$form.slideDown( 200 );
			$.scrollTo( $form, 500, {
				offset: -100,
				easing: 'swing'
			} );
			$form.find( '.ac-input' ).focus();
			$form.trigger( 'activity:reply:form:visible', [ $form, activityID ] );
			return false;
		} );

		// Comment reply cancel, hide form.
		$( document ).on( 'click:activity:comment:reply:cancel', function( evt, $target ) {
			// Canceling an activity comment
			$target.closest( '.ac-form' ).slideUp( 200 );
			return false;
		} );

		$( document ).on( 'click:activity:comment:submit', function( evt, target, form, activityID, commentID, content, nonce, akNonce ) {
			var commentData = {
				activity_id: activityID,
				comment_id: commentID,
				content: content,
				_wpnonce: nonce,
				nonce2: akNonce
			};
			// Hide any error messages.
			form.find( '.error' ).hide();
			target.addClass( 'loading' ).prop( 'disabled', true );
			form.addClass( 'loading' );//.prop('disabled', true);

			CB.Activity.CommentFilter.fire( commentData );

			requests.postComment( commentData ).done( function( response ) {
				var $activityComments, theComment, $activity, newCount, $showAllAnchor;
				target.removeClass( 'loading' );
				form.removeClass( 'loading' );

				// Check for errors and append if found.
				if ( ! response.success ) {
					CB.DOM.Notices.append( form, response.data.message, 'error' );
					form.find( '.site-feedback-message' ).hide().fadeIn( 200 );
					return;
				}

				$activityComments = form.parent();
				form.fadeOut( 200, function() {
					if ( 0 === $activityComments.children( 'ul' ).length ) {
						if ( $activityComments.hasClass( 'activity-comments' ) ) {
							$activityComments.prepend( '<ul></ul>' );
						} else {
							$activityComments.append( '<ul></ul>' );
						}
					}

					/* Preceding whitespace breaks output with jQuery 1.9.0 */
					theComment = $.trim( response.data.content );

					$activityComments.children( 'ul' ).append( $( theComment ).hide().fadeIn( 200 ) );
					form.find( '.ac-input' ).val( '' );
					$activityComments.parent().addClass( 'has-comments' );
				} );

				form.find( 'textarea' ).val( '' );

				// Increase the "Reply (X)" button count
				$activity = $( '#activity-' + activityID );
				newCount = Number( $activity.find( '.acomment-reply span' ).html() ) + 1;
				$activity.find( '.acomment-reply span' ).html( newCount );

				// Increment the 'Show all x comments' string, if present
				$showAllAnchor = $activityComments.parents( '.activity-comments' ).find( '.show-all-comments a' );
				if ( $showAllAnchor ) {
					$showAllAnchor.html( CBBPSettings.showXComments.replace( '%d', newCount ) );
				}

				target.prop( 'disabled', false );
			} );

			return false;
		} );

		$( document ).on( 'click:activity:comment:delete', function( evt, target, comment, commentID, nonce ) {
			var $form, $activity;
			if ( target.hasClass( 'loading' ) ) {
				return;
			}

			$form = comment.parents( '.activity-comments' ).children( 'form' );
			$activity = comment.closest( '.activity-item' );

			target.addClass( 'loading' );

			// Remove any error messages
			$activity.find( '.activity-comments ul .error' ).remove();

			// Reset the form position
			comment.parents( '.activity-comments' ).append( $form );

			requests.deleteComment( { id: commentID, _wpnonce: nonce } ).done( function( response ) {
				var children, childCount, countSpan, newCount, showAllAnchor;
				//Check for errors and append if found.
				if ( ! response.success ) {
					CB.DOM.Notices.prepend( comment, response.data.message, 'error' );
					comment.find( '.error' ).hide().fadeIn( 200 );
					return;
				}
				// if we are here, the request succeeded.
				children = $( '#acomment-' + response.data.id + ' ul' ).children( 'li' );
				childCount = 0;

				$( children ).each( function() {
					if ( ! $( this ).is( ':hidden' ) ) {
						childCount++;
					}
				} );
				comment.fadeOut( 200, function() {
					comment.remove();
				} );

				// Decrease the "Reply (X)" button count
				countSpan = $activity.find( '.acomment-reply span' );
				newCount = countSpan.html() - ( 1 + childCount );
				countSpan.html( newCount );

				// Change the 'Show all x comments' text
				showAllAnchor = comment.parents( '.activity-comments' ).find( '.show-all-comments a' );
				if ( showAllAnchor ) {
					showAllAnchor.html( CBBPSettings.showXComments.replace( '%d', newCount ) );
				}

				//If that was the last comment for the item, remove the has-comments class to clean up the styling
				if ( 0 === newCount ) {
					$activity.removeClass( 'has-comments' );
				}
			} );

			return false;
		} );

		$( document ).on( 'click:activity:comment:spam', function( evt, target, comment, commentID, nonce ) {
			var activity;
			if ( target.hasClass( 'loading' ) ) {
				return;
			}

			target.addClass( 'loading' );

			activity = comment.closest( '.activity-item' );
			// Remove any error messages
			activity.find( '.error' ).remove();

			// Reset the form position
			comment.parents( '.activity-comments' ).append( comment.parents( '.activity-comments' ).children( 'form' ) );

			requests.spamComment( { id: commentID, _wpnonce: nonce } ).done( function( response ) {
				var children, childCount;
				// Check for errors and append if found.
				if ( ! response.success ) {
					CB.DOM.Notices.prepend( comment, response.data.message, 'error' );
					comment.find( '.error' ).hide().fadeIn( 200 );
					return;
				}

				children = $( '#' + comment.attr( 'id' ) + ' ul' ).children( 'li' );
				childCount = 0;

				$( children ).each( function() {
					if ( ! $( this ).is( ':hidden' ) ) {
						childCount++;
					}
				} );
				comment.fadeOut( 200 );

				// Decrease the "Reply (X)" button count
				$( '#' + activity.attr( 'id' ) + ' .acomment-reply span' ).html( $( '#' + activity.attr( 'id' ) + ' a.acomment-reply span' ).html() - ( 1 + childCount ) );
			} );
		} );
	} );
}( jQuery ) );
