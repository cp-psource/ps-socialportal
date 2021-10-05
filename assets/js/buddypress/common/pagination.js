( function( $ ) {
	$( document ).ready( function() {
		/* All pagination links run through this function */
		// temporary work around using pagination-links.
		$( document ).on( 'click', '.pagination .pagination-links a', function( event ) {
			var $this = $( this ),
				$pagination = $this.parents( '.pagination' );

			if ( $pagination.hasClass( 'no-ajax' ) ) {
				return; // normal link behaviour.
			}

			// do nothing.
			if ( $this.hasClass( 'current' ) ) {
				return false;
			}

			var $itemListContainer = $this.parents( '.item-list-container' ),
				$nav = $( '.bp-nav' ).last(),
				object = $itemListContainer.data( 'object' ),
				//currentContext = $itemListContainer.data('context') || CPSFSettings.currentContext,
				searchTerms = false,
				paginationID = $pagination.find( '.pagination-links' ).attr( 'id' ),
				template = null,
				$itemBody = $pagination.parent( '#item-body' ),
				pageNumber,
				$gmSearch,
				caller;
			if ( ! $itemListContainer.length && $itemBody.length ) {
				object = $itemBody.find( '.bp-search' ).data( '.data-bp-search' );
				$itemListContainer = $itemBody.find( 'div.' + object );
			}

			if ( ! object ) {
				object = 'members';
				$itemListContainer = $( '#site-page' ).find( 'div.' + object );
			}

			scope = getScope( $nav.find( '.item-list-tabs li.selected' ) );

			// Search terms
			if ( $( 'div.dir-search input' ).length ) {
				searchTerms = $( '.bp-dir-search .search-input' );

				if ( ! searchTerms.val() && bp_get_querystring( searchTerms.attr( 'name' ) ) ) {
					searchTerms = $( '.bp-dir-search .search-input' ).prop( 'placeholder' );
				} else {
					searchTerms = searchTerms.val();
				}
			}

			// Page number
			if ( $( $this ).hasClass( 'next' ) || $( $this ).hasClass( 'prev' ) ) {
				pageNumber = $pagination.find( 'span.current' ).text();
			} else {
				pageNumber = $( $this ).text();
			}

			// Remove any non-numeric characters from page number text (commas, etc.)
			pageNumber = Number( pageNumber.replace( /\D/g, '' ) );

			if ( $( $this ).hasClass( 'next' ) ) {
				pageNumber++;
			} else if ( $( $this ).hasClass( 'prev' ) ) {
				pageNumber--;
			}

			// The Group Members page has a different selector for
			// its search terms box
			$gmSearch = $( '.groups-members-search input' );
			if ( $gmSearch.length ) {
				searchTerms = $gmSearch.val();
				object = 'members';
			}

			// On the Groups Members page, we specify a template
			var currentContext = $itemListContainer.data( 'context' );// css_id[1]//@todo check for groups.
			if ( 'members' === object && 'groups' === currentContext ) {
				object = 'group_members';
				template = 'groups/single/members/members-loop';
			}

			// On the Admin > Requests page, we need to reset the object,
			// since "admin" isn't specific enough
			if ( 'admin' === object && $( 'body' ).hasClass( 'membership-requests' ) ) {
				object = 'requests';
			}

			if ( paginationID.indexOf( 'pag-bottom' ) !== -1 ) {
				caller = 'pag-bottom';
			} else {
				caller = null;
			}
			var utils = CB.ItemUtils;
			var scope = utils.getCurrentScope( object );
			var filter = utils.getCurrentFilter( object );
			var extras = utils.getObjectPreference( object, 'extras' );
			CB.Request.getItems( object, scope, filter, searchTerms, pageNumber, extras, template ).done( function( response ) {
				/* animate to top if called from bottom pagination */

				if ( caller === 'pag-bottom' && $( '#subnav' ).length ) {
					var top = $( '#subnav' ).parent();
					$( 'html,body' ).animate( { scrollTop: top.offset().top }, 'slow', function() {
						$itemListContainer.fadeOut( 100, function() {
							$( this ).html( response );
							$itemListContainer.trigger( 'updated:item:list', [ object, $itemListContainer ] );
							$( this ).fadeIn( 100 );
						} );
					} );
				} else {
					$itemListContainer.fadeOut( 100, function() {
						$( this ).html( response );
						$itemListContainer.trigger( 'updated:item:list', [ object, $itemListContainer ] );
						$( this ).fadeIn( 100 );
					} );
				}
				$( '.item-list-tabs li.selected' ).removeClass( 'loading' );
			} );

			return false;
		} );
	} );

	/**
	 * Get current scope from the given tab element.
	 *
	 * @param {jQuery} $tab - Tab(li) element.
	 *
	 * @return {string} scope.
	 */
	function getScope( $tab ) {
		if ( ! $tab.length ) {
			return '';
		}

		var cssID = $tab.attr( 'id' ).split( '-' );

		// scope is the last part.
		return cssID.length ? cssID[cssID.length - 1] : '';
	}
}( jQuery ) );
