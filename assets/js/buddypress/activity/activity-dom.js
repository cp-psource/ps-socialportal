( function( $ ) {
	var activityDOM = {

		initActivityFilter: function( scope, filter ) {
			$( '.item-list-tabs li' ).removeClass( 'selected loading' );
			/* Set the correct selected nav and filter */
			$( '#activity-' + scope + ', .item-list-tabs li.current' ).addClass( 'selected' );
			$( '#object-nav.item-list-tabs li.selected, div.activity-type-tabs li.selected' ).addClass( 'loading' );
			$( '#activity-filter-select select option[value="' + filter + '"]' ).prop( 'selected', true );

			/* Reload the activity stream based on the selection */
			$( '.widget_bp_activity_widget h2 span.ajax-loader' ).show();
		},
		updateFilteredActivities: function( response ) {
			$( '.widget_bp_activity_widget h2 span.ajax-loader' ).hide();

			$( 'div.activity' ).fadeOut( 100, function() {
				$( this ).html( response.data.contents );
				$( this ).fadeIn( 100 );

				/* Selectively hide comments */
				activityDOM.hideComments();
			} );

			/* Update the feed link */
			if ( undefined !== response.data.feed_url ) {
				$( '.directory #subnav li.feed a, .home-page #subnav li.feed a' ).attr( 'href', response.data.feed_url );
			}

			$( '.item-list-tabs li.selected' ).removeClass( 'loading' );
		},
		// Hide long lists of activity comments, only show the latest 'n' root comments.
		hideComments: function() {
			var commentsDivs = $( 'div.activity-comments' ),
				parentActivity, commentList, commentDiv, commentCount,
				maxVisibleComments = 5;

			if ( ! commentsDivs.length ) {
				return false;
			}
			//CPSFSettings.maxVisibleComments;
			commentsDivs.each( function() {
				if ( $( this ).children( 'ul' ).children( 'li' ).length < maxVisibleComments ) {
					return;
				}

				commentDiv = $( this );
				parentActivity = commentDiv.parents( '#activity-stream > li' );
				commentList = $( this ).children( 'ul' ).children( 'li' );
				commentCount = 0;

				if ( $( '#' + parentActivity.attr( 'id' ) + ' li' ).length ) {
					commentCount = $( '#' + parentActivity.attr( 'id' ) + ' li' ).length;
				}

				commentList.each( function( i ) {
					/* Show the latest 5 root comments */
					if ( i < commentList.length - 5 ) {
						$( this ).hide();

						if ( ! i ) {
							$( this ).before( '<li class="show-all-comments"><a href="#' + parentActivity.attr( 'id' ) + '/show-all/">' + CPSFSettings.showXComments.replace( '%d', commentCount ) + '</a></li>' );
						}
					}
				} );
			} );
		}
	};

	CB.Activity.DOM = activityDOM;
}( jQuery ) );
