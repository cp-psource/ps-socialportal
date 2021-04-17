( function( $ ) {
	$( document ).ready( function() {
		// Activity Comments.
		$( document ).on( 'click', '.acomment-reply', function() {
			var $target = $( this ),
				id = $target.attr( 'id' ),
				ids = id.split( '-' ),
				activityID,
				commentID,
				type;

			activityID = ids[2];
			commentID = $target.attr( 'href' ).substr( 10, $target.attr( 'href' ).length );
			type = ids[1] === 'comment' ? 'comment' : 'reply';

			$( this ).trigger( 'click:activity:reply', [ $target, activityID, commentID, type ] );
			return false;
		} );

		// Activity comment form.
		// focus.
		$( document ).on( 'focus', '.ac-input', function() {
			var $target = $( this ),
				$form = $target.closest( '.ac-form' );
			$target.trigger( 'focus:activity:comment:content', [ $target, $form ] );
		} );

		// on focus out.
		$( document ).on( 'focusout', '.ac-input', function( e ) {
			var $target = $( this ),
				$form = $target.closest( '.ac-form' );
			// Let child hover actions passthrough.
			// This allows click events to go through without focusout.
			setTimeout( function() {
				if ( ! $form.find( ':hover' ).length ) {
					// Do not slide up if textarea has content.
					if ( $target.html().length ) {
						// return;
					}

					$target.trigger( 'focusout:activity:comment:content', [ $target, $form ] );
				}
			}, 0 );
		} );

		// Comment reply cancel.
		$( document ).on( 'click', '.ac-reply-cancel', function() {
			var target = $( this );
			target.trigger( 'click:activity:comment:reply:cancel', [ target ] );
			return false;
		} );

		$( document ).on( 'click', '.ac-form-submit', function() {
			var target = $( this ),
				$form,
				$formParent,
				formID,
				commentID,
				tempID,
				$input,
				akismetNonce,
				content,
				activityID;

			$form = target.parents( 'form' );
			$formParent = $form.parent();
			formID = $form.attr( 'id' ).split( '-' );
			activityID = formID[2];

			if ( ! $formParent.hasClass( 'activity-comments' ) ) {
				tempID = $formParent.attr( 'id' ).split( '-' );
				commentID = tempID[1];
			} else {
				commentID = formID[2];
			}

			$input = $( '#' + $form.attr( 'id' ) + ' .ac-input' ).first();
			// Transform emoji image into emoji unicode
			$input.find( 'img.emojioneemoji' ).replaceWith( function() {
				return this.dataset.emojiChar;
			} );

			content = $input.html().replace( /<br>|<div>/gi, '\n' ).replace( /<\/div>/gi, '' );
			$input.html( '' );
			target.trigger( 'click:activity:comment:submit', [ target, $form, activityID, commentID, content, $( '#_wpnonce_new_activity_comment' ).val(), akismetNonce ] );

			return false;
		} );

		$( document ).on( 'click', '.acomment-delete,.spam-activity-comment', function() {
			var $target = $( this ),
				linkURL = $target.attr( 'href' ),
				$comment = $target.closest( '.acomment-item' ),
				nonce = bp_get_query_var( '_wpnonce', linkURL ),
				commentID = bp_get_query_var( 'cid', linkURL );

			if ( $target.hasClass( 'acomment-delete' ) ) {
				$target.trigger( 'click:activity:comment:delete', [ $target, $comment, commentID, nonce ] );
			} else if ( $target.hasClass( '.spam-activity-comment' ) ) {
				// Spam an activity stream comment
				$( this ).trigger( 'click:activity:comment:spam', [ $target, $comment, commentID, nonce ] );
			}

			return false;
		} );

		$( document ).on( 'click', '.show-all-comments a', function() {
			var target = $( this );
			$( this ).trigger( 'click:activity:comment:showall', [ target ] );

			return false;
		} );
	} );
}( jQuery ) );
