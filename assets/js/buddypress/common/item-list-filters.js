( function( $ ) {
	/**
	 * @type {jqXHR}
	 */
	var currentRequest;
	$( document ).ready( function() {
		// Search.
		$( document ).on( 'submit', '.bp-dir-search-form', function() {
			var $form = $( this ),
				searchTerm = $form.find( '.search-input' ).val();
			if ( ! searchTerm.length ) {
				//return false;// no need to do anything.
			}

			var $nav = $form.parents( '.bp-search' ).siblings( '.bp-nav' ),
				$navTabs = $nav.find( '.item-list-tabs' ),
				object = $nav.data( 'object' ),
				filter = $nav.find( '.bp-nav-filters select' ).val(),
				scope = getScope( $nav.find( '.item-list-tabs li.selected' ) );

			var extras = CB.ItemUtils.getObjectPreference( object, 'extras' );
			$navTabs.addClass( 'loading' );
			if ( currentRequest ) {
				currentRequest.abort();
			}

			currentRequest = CB.Request.getItems( object, scope, filter, searchTerm, 1, extras ).done( function( response ) {
				$navTabs.removeClass( 'loading' );
				var $itemListContainer = $( 'div.' + object );
				$itemListContainer.fadeOut( 100, function() {
					$( this ).html( response );
					$itemListContainer.trigger( 'updated:item:list', [ object, $itemListContainer ] );
					$( this ).fadeIn( 100 );
				} );

				$( '.item-list-tabs li.selected' ).removeClass( 'loading' );
			} );
			return false;
		} );

		// Group members Search.
		$( document ).on( 'submit', '#search-members-form', function() {
			var $form = $( this ),
				searchTerm = $form.find( '#members_search' ).val(),
				template = '';

			if ( ! searchTerm.length ) {
				// return false;// no need to do anything.
			}

			var $nav = $form.parents( '.bp-nav' ),
				$navTabs = $nav.find( '.item-list-tabs' ),
				object = $nav.data( 'object' ),
				filter = $nav.find( '.bp-nav-filters select' ).val(),
				scope = 'groups';
			// On the Groups Members page, we specify a template
			if ( 'members' === object && 'groups' === scope ) {
				object = 'group_members';
				template = 'groups/single/members/members-loop';
			}

			var extras = CB.ItemUtils.getObjectPreference( object, 'extras' );
			$navTabs.addClass( 'loading' );
			if ( currentRequest ) {
				currentRequest.abort();
			}

			currentRequest = CB.Request.getItems( object, scope, filter, searchTerm, 1, extras, template ).done( function( response ) {
				$navTabs.removeClass( 'loading' );
				var $itemListContainer = $( 'div.' + object );
				$itemListContainer.fadeOut( 100, function() {
					$( this ).html( response );
					$itemListContainer.trigger( 'updated:item:list', [ object, $itemListContainer ] );
					$( this ).fadeIn( 100 );
				} );

				$( '.item-list-tabs li.selected' ).removeClass( 'loading' );
			} );
			return false;
		} );

		// Tabs and Filters
		// When a navigation tab is clicked - e.g. | All Groups | My Groups |
		$( document ).on( 'click', '.item-list-tabs a', function( event ) {
			var $this = $( this ),
				$tab = $this.parent( 'li' ),
				$nav = $this.parents( '.bp-nav' ),
				$navTabs = $this.parents( '.item-list-tabs' ),
				object = $nav.data( 'object' );

			if ( $this.hasClass( 'no-ajax' ) || $tab.hasClass( 'no-ajax' ) || $navTabs.hasClass( 'no-ajax' ) ) {
				return;
			}

			var scope, filter, searchTerms;

			// do not handle activity tabs.
			if ( $tab.parents( '.activity-type-tabs' ).length ) {
				return;
			}

			if ( 'activity' === object ) {
				return;// false;
			}

			// scope is the last part.
			scope = getScope( $tab );
			filter = $nav.find( '.bp-nav-filters select' ).val();
			searchTerms = $( '#' + object + '-search' ).val();

			var extras = CB.ItemUtils.getObjectPreference( object, 'extras' );
			CB.DOM.ItemTabs.init( object, scope, filter );

			if ( 'friends' === object || 'group_members' === object ) {
				object = 'members';
			}

			if ( currentRequest ) {
				currentRequest.abort();
			}

			currentRequest = CB.Request.getItems( object, scope, filter, searchTerms, 1, extras ).done( function( response ) {
				var $itemListContainer = $( 'div.' + object );
				$itemListContainer.fadeOut( 100, function() {
					$( this ).html( response );
					$itemListContainer.trigger( 'updated:item:list', [ object, $itemListContainer ] );
					$( this ).fadeIn( 100 );
				} );
				$( '.item-list-tabs li.selected' ).removeClass( 'loading' );
			} );

			return false;
		} );

		// When the filter select box is changed re-query
		$( document ).on( 'change', '.bp-nav-filters select', function() {
			if ( $( this ).parent().hasClass( 'activity-filter' ) ) {
				return;
			}

			var $this = $( this ),
				$nav = $this.parents( '.bp-nav' ),
				$navTabs = $nav.find( '.item-list-tabs' ),
				object = $nav.data( 'object' ),
				filter = $this.val(),
				scope = getScope( $nav.find( '.item-list-tabs li.selected' ) );
			var search_terms, template,
				$gm_search;
			search_terms = false;
			template = null;

			if ( $navTabs.hasClass( 'activity-type-tabs' ) || $nav.hasClass( 'activity-type-tabs' ) ) {
				return;
			}
			$navTabs.addClass( 'loading' );
			if ( $( '.bp-dir-search .search-input' ).length ) {
				search_terms = $( '.bp-dir-search .search-input' ).val();
			}

			// The Group Members page has a different selector for its
			// search terms box
			$gm_search = $( '.groups-members-search input' );
			if ( $gm_search.length ) {
				search_terms = $gm_search.val();
				object = 'members';
				scope = 'groups';
			}

			// On the Groups Members page, we specify a template
			if ( 'members' === object && 'groups' === scope ) {
				object = 'group_members';
				template = 'groups/single/members/members-loop';
			}

			if ( 'friends' === object ) {
				object = 'members';
			}

			if ( currentRequest ) {
				currentRequest.abort();
			}

			var extras = CB.ItemUtils.getObjectPreference( object, 'extras' );

			currentRequest = CB.Request.getItems( object, scope, filter, search_terms, 1, extras, template ).done( function( response ) {
				$navTabs.removeClass( 'loading' );
				var $itemListContainer = $( 'div.' + object );
				$itemListContainer.fadeOut( 100, function() {
					$( this ).html( response );
					$itemListContainer.trigger( 'updated:item:list', [ object, $itemListContainer ] );
					$( this ).fadeIn( 100 );
				} );

				$( '.item-list-tabs li.selected' ).removeClass( 'loading' );
			} );

			return false;
		} );
	} );

	// On item list update, reflow the grid.
	$( document ).on( 'updated:item:list', function( event, object ) {
		CB.DOM.ItemList.updateItemListLayout( 'body' );
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
