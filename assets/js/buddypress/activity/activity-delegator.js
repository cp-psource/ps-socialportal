( function( $ ) {
	/**
	 * Helps make the activity evenets driven.
	 *
	 * The code in this file simply triggers syntetic events for various user actions.
	 * The activity.js binds to these events and does the actual processing.
	 */

	$( document ).ready( function() {
		// form check for public message.
		var $form = $( '.bp-activity-post-form' );

		// On Activity form load.
		if ( $form.length ) {
			$form.trigger( 'loaded:activity:post:form', [ $form ] );
		}

		// On List tabs click.
		$( document ).on( 'click', '.activity-type-tabs a', function( event ) {
			var $selectedTab = $( this ).parent(),
				scope,
				filter;

			if ( $selectedTab.parents( '.item-list-tabs' ).hasClass( 'no-ajax' ) ) {
				return;
			}

			// Activity Stream Tabs
			scope = $selectedTab.attr( 'id' ).substr( 9, $selectedTab.attr( 'id' ).length );
			filter = $( '#activity-filter-select select' ).val();

			if ( scope === 'mentions' ) {
				$selectedTab.find( 'a strong' ).remove();
			}

			$( this ).trigger( 'click:activity:tab', [ $selectedTab, scope, filter ] );
			return false;
		} );

		// on Filter Change.
		$( document ).on( 'change', '#activity-filter-select select', function() {
			var $selectedTab = $( '.activity-type-tabs li.selected' ),
				filter = $( this ).val(),
				scope;

			if ( ! $selectedTab.length ) {
				scope = null;
			} else {
				scope = $selectedTab.attr( 'id' ).substr( 9, $selectedTab.attr( 'id' ).length );
			}

			$( this ).trigger( 'change:activity:filter', [ $selectedTab, scope, filter ] );

			return false;
		} );

		// Activity post form.
		// focus.
		$( document ).on( 'focus', '.bp-activity-post-editor', function() {
			var $target = $( this ),
				$form = $target.closest( '.bp-activity-post-form' );
			$target.trigger( 'focus:activity:post:content', [ $target, $form ] );
		} );

		// For the "What's New" form, do the following on focusout.
		$( document ).on( 'focusout', '.bp-activity-post-editor', function( e ) {
			var $target = $( this ),
				$form = $target.closest( '.bp-activity-post-form' );
			// Let child hover actions passthrough.
			// This allows click events to go through without focusout.
			setTimeout( function() {
				if ( ! $form.find( ':hover' ).length ) {
					// Do not slide up if textarea has content.
					if ( $target.html().length ) {
						return;
					}

					$target.trigger( 'focusout:activity:post:content', [ $target, $form ] );
				}
			}, 0 );
		} );

		// On activity post submit.
		$( document ).on( 'click', '.bp-activity-post-submit', function() {
			var $button = $( this ),
				$form = $button.closest( '.bp-activity-post-form' );

			$button.trigger( 'click:activity:publish', [ $button, $form ] );

			return false;
		} );
		// On activity post submit.
		$( document ).on( 'click', '.bp-activity-post-cancel', function() {
			var $button = $( this ),
				$form = $button.closest( '.bp-activity-post-form' );

			$button.trigger( 'click:activity:post:cancel', [ $button, $form ] );

			return false;
		} );

		// Favourite/unfavourite.
		$( document ).on( 'click', '.activity-item .fav, .activity-item .unfav', function() {
			var $target = $( this ),
				$activity,
				activityID,
				nonce,
				isFav;

			if ( $target.hasClass( 'loading' ) ) {
				return false;
			}

			isFav = $target.hasClass( 'fav' );

			$activity = $target.closest( '.activity-item' );
			activityID = getActivityID( $activity );
			nonce = bp_get_query_var( '_wpnonce', $target.attr( 'href' ) );

			if ( isFav ) {
				$target.trigger( 'click:activity:favorite', [ $target, $activity, activityID, nonce ] );
			} else {
				$target.trigger( 'click:activity:unfavorite', [ $target, $activity, activityID, nonce ] );
			}

			return false;
		} );

		// Delete activity.
		$( document ).on( 'click', '.delete-activity, .spam-activity', function() {
			// Delete activity stream items
			var $activity, id, nonce, timestamp,
				$target = $( this );

			$activity = $target.closest( '.activity-item' );
			id = getActivityID( $activity );

			nonce = bp_get_query_var( '_wpnonce', $target.attr( 'href' ) );
			timestamp = $activity.prop( 'class' ).match( /date-recorded-([0-9]+)/ );

			if ( $target.hasClass( 'delete-activity' ) ) {
				$target.trigger( 'click:activity:delete', [ $target, $activity, id, timestamp, nonce ] );
			} else if ( $target.hasClass( 'spam-activity' ) ) {
				$target.trigger( 'click:activity:spam', [ $target, $activity, id, timestamp, nonce ] );
			}
			return false;
		} );

		// Activity "Read More" links.
		$( document ).on( 'click', '.activity-read-more a', function( event ) {
			var $target = $( this ),
				linkID = $target.parent().attr( 'id' ).split( '-' ),
				activityID = linkID[3],
				type = linkID[0]; // activity or acomment

			$target.trigger( 'click:activity:readmore', [ $target, activityID, type ] );
			return false;
		} );

		// Load More.
		$( document ).on( 'click', '.load-more a', function() {
			var $target = $( this );
			$target.trigger( 'click:activity:loadmore', [ $target ] );
			return false;
		} );

		// On load newest(top)
		$( document ).on( 'click', '.load-newest a', function( event ) {
			var $target = $( this );
			// Load newest updates at the top of the list
			event.preventDefault();

			$target.parent().hide();
			$target.trigger( 'click:activity:loadnew', [ $target ] );
			return false;
		} );
	} );

	/**
	 * Get activity id.
	 *
	 * @param {jQuery} activity
	 * @return {number}
	 */
	function getActivityID( activity ) {
		return activity.data( 'id' ) ? activity.data( 'id' ) : activity.attr( 'id' ).substr( 9, activity.attr( 'id' ).length );
	}
}( jQuery ) );
