( function( $ ) {
	window.CB = {
		DOM: {}, // common/cb-dom.js
		//Utils:{},// common/item-utils.js
		Feedback: {}, //utils/cb-feedback.js
		Loader: {}, // utils/cb-loader.js
		Request: {},
		Message: {},
		Activity: {}

	};
}( jQuery ) );

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

( function( $ ) {
	CB.Request = {

		/* Filter the current content list (groups/members/blogs/topics) */
		getItems: function( object, scope, filter, search_terms, page, extras, template ) {
			if ( 'activity' === object ) {
				return;//CB.Activity.Requests.get({ scope:scope, filter: filter });
			}

			if ( null === scope ) {
				scope = 'all';
			}
			var utils = CB.ItemUtils;
			/* Save the settings we want to remain persistent */
			utils.setObjectPreference( object, 'scope', scope );
			utils.setObjectPreference( object, 'filter', filter );
			utils.setObjectPreference( object, 'extras', extras );

			if ( 'friends' === object || 'group_members' === object ) {
				object = 'members';
			}

			//if (bp_ajax_request) {
			// bp_ajax_request.abort();
			//}

			// Get directory preferences (called "cookie" for legacy reasons).
			var cookies = {};
			cookies['bp-' + object + '-filter'] = utils.getObjectPreference( object, 'filter' );
			cookies['bp-' + object + '-scope'] = utils.getObjectPreference( object, 'scope' );

			var cookie = encodeURIComponent( $.param( cookies ) );

			return $.post( ajaxurl, {
				action: object + '_filter',
				cookie: cookie,
				object: object,
				filter: filter,
				search_terms: search_terms,
				scope: scope,
				page: page,
				extras: extras,
				template: template
			} );
		},
		updateSitewideNotice: function( id, nonce ) {
			return $.post( ajaxurl, {
				action: 'messages_close_notice',
				notice_id: id,
				nonce: nonce
			} );
		}
	};
}( jQuery ) );

( function( $ ) {
	window.CB = typeof CB === 'undefined' ? {} : CB;

	var DOM = CB.DOM || {};

	/**
	 * Set tab state for the object[member, groups etc].
	 *
	 * @param object
	 */
	CB.DOM = $.extend( DOM, {

		// Clear BP cookies on logout
		logout: function() {
			var isSecure = 'https:' === window.location.protocol;
			var activityCookies = [ 'bp-activity-scope', 'bp-activity-filter', 'bp-activity-oldestpage' ];
			$.each( activityCookies, function( index, cookieName ) {
				$.removeCookie( cookieName, {
					path: '/',
					secure: isSecure
				} );
			} );

			var objects = [ 'members', 'groups', 'blogs', 'forums' ];
			$.each( objects, function( i, object ) {
				$.removeCookie( 'bp-' + object + '-scope', {
					path: '/',
					secure: isSecure
				} );
				$.removeCookie( 'bp-' + object + '-filter', {
					path: '/',
					secure: isSecure
				} );
				$.removeCookie( 'bp-' + object + '-extras', {
					path: '/',
					secure: isSecure
				} );
			} );

			$( 'body' ).trigger( 'cb:user:loggedout' );
		}

	} );

	// Notices manipulation.
	CB.DOM.Notices = {
		prepareNoticeMarkup: function( message, type ) {
			type = type || 'info';
			var notice = '<div id="site-feedback-message" class="site-feedback-message ' + type + '">';
			notice += '<div class="inner">';
			notice += '<div class="bp-template-notice ' + type + '">';
			notice += '<p>' + message + '<i class="fa fa-times-circle-o cb-close-notice" aria-hidden="true"></i>' + '</p>';
			notice += '</div></div></div>';
			return notice;
		},
		append: function( $context, message, type ) {
			if ( ! $context.length ) {
				return;
			}
			$context.prepend( this.prepareNoticeMarkup( message, type ) );
		},
		prepend: function( $context, message, type ) {
			if ( ! $context.length ) {
				return;
			}
			$context.prepend( this.prepareNoticeMarkup( message, type ) );
		},

		hideAll: function() {
			$( '.site-feedback-message' ).remove();
		},
		hide: function( $context ) {
			if ( ! $context.length ) {
				return;
			}
			$context.find( '.site-feedback-message' ).remove();
		}
	};
}( jQuery ) );

( function( $ ) {
	window.CB = typeof CB === 'undefined' ? {} : CB;

} );

( function( $ ) {
	var directoryPreferences = {};
	CB.ItemUtils = {
		/**
		 * get current scope for the object.
		 *
		 * @param object
		 * @return {*}
		 */
		getCurrentScope: function( object ) {
			return this.getObjectPreference( object, 'scope' );
		},

		/**
		 * Get current filter for the object.
		 *
		 * @param object
		 * @return {*}
		 */
		getCurrentFilter: function( object ) {
			return this.getObjectPreference( object, 'filter' );
		},

		/**
		 * Get Object preference.
		 *
		 * @param object
		 * @param pref
		 * @return {*}
		 */
		getObjectPreference: function( object, pref ) {
			// Rebuild if needed.
			if ( ! directoryPreferences.hasOwnProperty( object ) ) {
				directoryPreferences[object] = this.rebuildObjectPreferences();
			}

			if ( CBBPSettings.storeFilterSettings ) {
				directoryPreferences[object][pref] = $.cookie( 'bp-' + object + '-' + pref );
			}

			return directoryPreferences[object][pref];
		},

		/**
		 * Sets the user's current preference for a directory option.
		 *
		 * @param object
		 * @param pref
		 * @param value
		 */
		setObjectPreference: function( object, pref, value ) {
			var defaultPrefs = {
				filter: '',
				scope: '',
				extras: ''
			};

			if ( ! directoryPreferences.hasOwnProperty( object ) ) {
				var newPreferences = {};
				for ( var prefName in defaultPrefs ) {
					if ( defaultPrefs.hasOwnProperty( prefName ) ) {
						newPreferences[prefName] = defaultPrefs[prefName];
					}
				}
				directoryPreferences[object] = newPreferences;
			}

			if ( CBBPSettings.storeFilterSettings ) {
				$.cookie( 'bp-' + object + '-' + pref, value, {
					path: '/',
					secure: ( 'https:' === window.location.protocol )
				} );
			}

			directoryPreferences[object][pref] = value;
		},

		/**
		 * Rebuild and get new preferences.
		 */
		rebuildObjectPreferences: function() {
			var defaultPrefs = {
					filter: '',
					scope: '',
					extras: ''
				},
				newPreferences = {};

			for ( var prefName in defaultPrefs ) {
				if ( defaultPrefs.hasOwnProperty( prefName ) ) {
					newPreferences[prefName] = defaultPrefs[prefName];
				}
			}

			return newPreferences;
		},

		/**
		 * Get scope from element id attribute.
		 *
		 * @param id
		 * @return {string}
		 */
		getScopeFromElementID: function( id ) {
			if ( 'undefined' === typeof id ) {
				return '';
			}
			var parts = id.split( '-' );
			return parts.length > 0 ? parts[parts.length - 1] : '';
		}
	};
}( jQuery ) );

( function( $ ) {
	var ItemList = CB.DOM.ItemList || {};
	CB.DOM.ItemList = $.extend( ItemList, {
		/**
		 * Update item list based on grid type.
		 *
		 * @return {undefined}
		 * @param target
		 */
		updateItemListLayout: function( target ) {
			var type = CBBPSettings.itemListDisplayType;
			if ( type !== 'grid' ) {
				return;
			}

			var gridType = CBBPSettings.itemListGridType;//bp_item_list_display_type;
			if ( 'equalheight' === gridType ) {
				this.makeEqualHeightItems( target );
			} else if ( 'masonry' === gridType ) {
				this.makeMasonryGrid( target );
			}
		},

		makeMasonryGrid: function( target ) {
			var $container = $( target ).find( '.item-list-grid-masonry' );

			$container.imagesLoaded( function() {
				$container.masonry( {
					columnWidth: '.item-entry-type-masonry',
					itemSelector: '.item-entry-type-masonry'
				} );
			} );
		},

		/**
		 * Make the list equal heights
		 *
		 * @param container
		 */
		makeEqualHeightItems: function( container ) {
			var $container = $( container );
			$container.imagesLoaded( function() {
				var tallest = 0,
					items = [],
					current_height = 0;

				$container.find( '.item-list-grid-equalheight>li' ).each( function() {
					var $el = $( this );
					$el.height( 'auto' );

					items.push( $el );
					current_height = $el.height();

					if ( current_height > tallest ) {
						tallest = current_height;
					}
				} );

				for ( var i = 0; i < items.length; i++ ) {
					items[i].height( tallest );
				}

				items = [];//reset
			} );
		}

	} );
}( jQuery ) );

( function( $ ) {
	CB.DOM.ItemTabs = {
		setState: function( object ) {
			var utils = CB.ItemUtils,
				scope = utils.getCurrentScope( object ),
				filter = utils.getCurrentFilter( object );

			if ( undefined !== filter && $( '#' + object + '-order-select select' ).length ) {
				$( '#' + object + '-order-select select option[value="' + filter + '"]' ).prop( 'selected', true );
			}

			if ( undefined !== scope && $( '.' + object + '-type-tabs' ).length ) {
				$( '.' + object + '-type-tabs li' ).removeClass( 'selected' );

				$( '#' + object + '-' + scope + ', #object-nav li.current' ).addClass( 'selected' );
			}
		},
		// set scope etc.
		init: function( object, scope, filter ) {
			/* Set the correct selected nav and filter */
			$( '.item-list-tabs li' ).removeClass( 'selected' );
			$( '#' + object + '-' + scope + ', #object-nav li.current' ).addClass( 'selected' );
			$( '.item-list-tabs li.selected' ).addClass( 'loading' );
			$( '.item-list-tabs select option[value="' + filter + '"]' ).prop( 'selected', true );
		}
	};
}( jQuery ) );

( function( $ ) {
	$( document ).ready( function() {
		var domHelper = CB.DOM.ItemTabs;
		// Loop and setup tab state.
		// Set tab state for each object.
		var objects = [ 'activity', 'members', 'groups', 'blogs', 'group_members' ];
		$.each( objects, function( index, object ) {
			domHelper.setState( object );
		} );

		// Dir search.
		$( document ).on( 'click', '.dir-search-anchor', function() {
			var $dir_search = $( '.bp-dir-search' );

			$dir_search.toggleClass( 'search-visible' );
			if ( $dir_search.hasClass( 'search-visible' ) ) {
				$dir_search.slideDown( 500 );
			} else {
				$dir_search.slideUp( 500 );
			}

			return false;
		} );
	} );
}( jQuery ) );

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
				//currentContext = $itemListContainer.data('context') || CBBPSettings.currentContext,
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

( function( $ ) {
	CB.Activity = CB.Activity || {};

	CB.Activity.Requests = {
		// Get filtered Activity with scope and filter.
		get: function( args ) {
			var utils = CB.ItemUtils;

			args = $.extend( {
				scope: null,
				filter: null
			},
			args
			);

			// Save the type and filter
			utils.setObjectPreference( 'activity', 'scope', args.scope );
			utils.setObjectPreference( 'activity', 'filter', args.filter );

			return $.post( ajaxurl, {
				action: 'activity_filter',
				_wpnonce_activity_filter: $( '#_wpnonce_activity_filter' ).val(),
				scope: args.scope,
				filter: args.filter
			} );
		},
		// Get old activities(pages).
		getOld: function( args ) {
			var utils = CB.ItemUtils;
			args = $.extend( {
				action: 'activity_get_older_updates',
				page: 1,
				search_terms: '',
				excluded: '',
				scope: utils.getObjectPreference( 'activity', 'scope' ),
				filter: utils.getObjectPreference( 'activity', 'filter' )
			},
			args
			);

			args.exclude_just_posted = $.isArray( args.excluded ) ? args.excluded.join( ',' ) : args.excluded;
			delete ( args.excluded );

			return $.post( ajaxurl, args );
		},
		// Get single activity.
		getSingle: function( activityID ) {
			return $.post( ajaxurl, {
				action: 'activity_get_single',
				activity_id: activityID
			} );
		},

		// post new activity update.
		postUpdate: function( args ) {
			if ( ! args ) {
				args = {};
			}

			args = $.extend( {
				action: 'post_update',
				content: '',
				object: '',
				item_id: 0,
				since: '',
				_wpnonce: '',
				_bp_as_nonce: ''
			}, args );

			return $.post( ajaxurl, args );
		},

		// delete activity.
		deleteActivity: function( args ) {
			args = $.extend( {
				action: 'activity_delete',
				id: 0,
				_wpnonce: ''
			}, args );
			return $.post( ajaxurl, args );
		},

		// Mark favorite.
		favorite: function( args ) {
			args = $.extend( {
				action: 'activity_mark_fav',
				id: 0,
				_wpnonce: ''
			}, args );

			return $.post( ajaxurl, args );
		},

		// unfavorite activity.
		unfavorite: function( args ) {
			args = $.extend( {
				action: 'activity_mark_unfav',
				id: 0,
				_wpnonce: ''
			}, args );

			return $.post( ajaxurl, args );
		},

		// mark activity spam.
		spam: function( args ) {
			args = $.extend( {
				action: 'activity_mark_spam',
				id: 0,
				_wpnonce: ''
			}, args );
			return $.post( ajaxurl, args );
		},

		// Post activity comment.
		postComment: function( args ) {
			if ( ! args ) {
				args = {};
			}

			args = $.extend( {
				action: 'activity_post_comment',
				comment_id: 0,
				activity_id: 0,
				content: '',
				_wpnonce: '',
				nonce2: ''
			}, args );
			args['_bp_as_nonce_' + args.comment_id] = args.nonce2;
			delete args.nonce2;

			return $.post( ajaxurl, args );
		},

		// delete comments
		deleteComment: function( args ) {
			args = $.extend( {
				action: 'activity_delete_comment',
				id: 0,
				_wpnonce: ''
			}, args );
			return $.post( ajaxurl, args );
		},
		// spam comments
		spamComment: function( args ) {
			args = $.extend( {
				action: 'activity_mark_comment_spam',
				id: 0,
				_wpnonce: ''
			}, args );

			return $.post( ajaxurl, args );
		}
	};
}( jQuery ) );

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
			//CBBPSettings.maxVisibleComments;
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
							$( this ).before( '<li class="show-all-comments"><a href="#' + parentActivity.attr( 'id' ) + '/show-all/">' + CBBPSettings.showXComments.replace( '%d', commentCount ) + '</a></li>' );
						}
					}
				} );
			} );
		}
	};

	CB.Activity.DOM = activityDOM;
}( jQuery ) );

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

( function( CB, $ ) {
	CB.Friends = CB.Friends || {};
	CB.Friends.Requests = {
		// request friendship.
		requestFriendship: function( args ) {
			args = $.extend( {
				action: 'friends_request_friendship',
				id: 0,
				_wpnonce: ''
			},
			args
			);
			return $.post( ajaxurl, args );
		},
		// cancel friendship request.
		cancelRequest: function( args ) {
			args = $.extend( {
				action: 'friends_cancel_friendship_request',
				id: 0,
				_wpnonce: ''
			},
			args
			);
			return $.post( ajaxurl, args );
		},
		// cancel friendship.
		cancelFriendship: function( args ) {
			args = $.extend( {
				action: 'friends_cancel_friendship',
				id: 0,
				_wpnonce: ''
			},
			args
			);
			return $.post( ajaxurl, args );
		},

		// accept friendship.
		acceptFriendship: function( args ) {
			args = $.extend( {
				action: 'friends_accept_friendship',
				id: 0,
				_wpnonce: ''
			},
			args
			);
			return $.post( ajaxurl, args );
		},
		// reject request.
		rejectRequest: function( args ) {
			args = $.extend( {
				action: 'friends_reject_friendship',
				id: 0,
				_wpnonce: ''
			},
			args
			);
			return $.post( ajaxurl, args );
		}

	};
}( CB || {}, jQuery ) );

( function( $ ) {
	/* Add / Remove friendship buttons */
	$( document ).on( 'click', '.friendship-button a', function() {
		var
			button = $( this ),
			fid = button.attr( 'id' ),
			nonce = bp_get_query_var( '_wpnonce', button.attr( 'href' ) ),
			buttonWrapper = button.parent( '.generic-button' );

		fid = fid.split( '-' );
		fid = fid[fid.length - 1];

		if ( button.hasClass( 'not_friends' ) ) {
			button.trigger( 'click:request:friendship', [ button, buttonWrapper, fid, nonce ] );
		} else if ( button.hasClass( 'pending_friend' ) ) {
			button.trigger( 'click:cancel:friendship:request', [ button, buttonWrapper, fid, nonce ] );
		} else if ( button.hasClass( 'is_friend' ) ) {
			button.trigger( 'click:cancel:friendship', [ button, buttonWrapper, fid, nonce ] );
		} else if ( button.hasClass( 'accept_friendship' ) ) {
			button.trigger( 'click:accept:friendship', [ button, buttonWrapper, fid, nonce ] );
		} else if ( button.hasClass( 'reject_friendship' ) ) {
			button.trigger( 'click:reject:friendship', [ button, buttonWrapper, fid, nonce ] );
		}

		return false;
	} );
}( jQuery ) );

( function( $ ) {
	var requests = CB.Friends.Requests;

	$( document ).on( 'click:request:friendship', function( evt, button, buttonWrapper, fid, nonce ) {
		buttonWrapper.addClass( 'loading' );
		requests.requestFriendship( { id: fid, _wpnonce: nonce } ).then( function( response ) {
			processRequest( response, buttonWrapper );
		} );
	} );

	$( document ).on( 'click:cancel:friendship:request', function( evt, button, buttonWrapper, id, nonce ) {
		buttonWrapper.addClass( 'loading' );
		requests.cancelRequest( { id: id, _wpnonce: nonce } ).then( function( response ) {
			processRequest( response, buttonWrapper );
		} );
	} );

	$( document ).on( 'click:cancel:friendship', function( evt, button, buttonWrapper, id, nonce ) {
		buttonWrapper.addClass( 'loading' );
		requests.cancelFriendship( { id: id, _wpnonce: nonce } ).then( function( response ) {
			processRequest( response, buttonWrapper );
		} );
	} );

	// On accept button click.
	$( document ).on( 'click:accept:friendship', function( evt, button, buttonWrapper, id, nonce ) {
		buttonWrapper.addClass( 'loading' );
		buttonWrapper.siblings( '.reject_friendship' ).hide();
		requests.acceptFriendship( { id: id, _wpnonce: nonce } ).then( function( response ) {
			processAcceptReject( response, buttonWrapper );
		} );
	} );

	// On Reject button click.
	$( document ).on( 'click:reject:friendship', function( evt, button, buttonWrapper, id, nonce ) {
		buttonWrapper.addClass( 'loading' );
		buttonWrapper.siblings( '.accept_friendship' ).hide();
		requests.rejectRequest( { id: id, _wpnonce: nonce } ).then( function( response ) {
			processAcceptReject( response, buttonWrapper );
		} );
	} );

	// process response for add, remove, cancel friendship/requests.
	function processRequest( response, buttonWrapper ) {
		buttonWrapper.removeClass( 'loading' );
		if ( ! response.success ) {
			buttonWrapper.parents( '.list-item' ).append( response.data.message );
			return;
		}

		// if we are here, te request succeeded.
		buttonWrapper.fadeOut( 100, function() {
			buttonWrapper.trigger( 'webui:refresh' );
			buttonWrapper.replaceWith( response.data.button );
		} );
	}

	// process response for add, remove, cancel friendship/requests.
	function processAcceptReject( response, buttonWrapper ) {
		buttonWrapper.removeClass( 'loading' );
		if ( ! response.success ) {
			buttonWrapper.parents( '.list-item' ).append( response.data.message );
			return;
		}

		// if we are here, te request succeeded.
		buttonWrapper.fadeOut( 100, function() {
			buttonWrapper.trigger( 'webui:refresh' );
			buttonWrapper.replaceWith( response.data.message );
		} );
	}
}( jQuery ) );

( function( $ ) {
	$( document ).ready( function() {
		var transitionSpeed = 500;
		// toggle visibility
		// Current visibility or the visibility config icon clicked.
		$( document ).on( 'click', '.visibility-toggle-link, .current-visibility-level', function( event ) {
			event.preventDefault();
			var toggle = $( this ),
				editField = toggle.parents( '.editfield' ),
				toggleTitle = editField.find( '.field-visibility-settings-toggle' ),
				visibilityOptions = editField.find( '.field-visibility-settings' );

			if ( toggleTitle.hasClass( 'field-visibility-settings-notoggle' ).length ) {
				return;
			}

			toggle.attr( 'aria-expanded', 'true' );
			toggleTitle.hide( transitionSpeed ).addClass( 'field-visibility-settings-hide' );
			visibilityOptions.slideDown( transitionSpeed ).addClass( 'field-visibility-settings-open' );
		} );

		$( document ).on( 'click', '.field-visibility-settings-close', function( event ) {
			var button = $( this ),
				editField = button.parents( '.editfield' ),
				toggleTitle = editField.find( '.field-visibility-settings-toggle' ),
				visibilityOptions = editField.find( '.field-visibility-settings' ),
				visibilitySettingText = visibilityOptions.find( 'input:checked' ).parent().text();
			toggleTitle.find( '.visibility-toggle-link, .current-visibility-level' ).attr( 'aria-expanded', 'false' );
			event.preventDefault();

			visibilityOptions.slideUp( transitionSpeed ).removeClass( 'field-visibility-settings-open' );
			toggleTitle.find( '.current-visibility-level' ).text( visibilitySettingText ).end()
				.show( transitionSpeed ).removeClass( 'field-visibility-settings-hide' );
		} );

		$( document ).on( 'click', '.field-visibility-settings .radio input', function() {
			var radio = $( this ),
				editField = radio.parents( '.editfield' ),
				toggleTitle = editField.find( '.field-visibility-settings-toggle' ),
				visibilityOptions = editField.find( '.field-visibility-settings' ),
				visibilitySettingsText = visibilityOptions.find( 'input:checked' ).parent().text();

			toggleTitle.find( '.visibility-toggle-link, .current-visibility-level' ).attr( 'aria-expanded', 'false' );

			visibilityOptions.slideUp( transitionSpeed ).removeClass( 'field-visibility-settings-open' );

			toggleTitle.find( '.current-visibility-level' ).text( visibilitySettingsText ).end()
				.show( transitionSpeed ).removeClass( 'field-visibility-settings-hide' );
		} );

		// warn for unsaved changes.
		$( '#profile-edit-form input:not(:submit), #profile-edit-form textarea, #profile-edit-form select, #signup_form input:not(:submit), #signup_form textarea, #signup_form select' ).change( function() {
			var shouldconfirm = true;

			$( '#profile-edit-form input:submit, #signup_form input:submit' ).on( 'click', function() {
				shouldconfirm = false;
			} );

			window.onbeforeunload = function( e ) {
				if ( shouldconfirm ) {
					return CBBPSettings.unsaved_changes;
				}
			};
		} );
	} );
}( jQuery ) );

( function( $ ) {
	window.CB = typeof CB === 'undefined' ? {} : CB;

	CB.Notifications = {};

	CB.Notifications.Requests = {

		get: function( args ) {
			args = $.extend( {
				action: 'cb_notifications_get',
				scope: 'all',
				page: 0,
				offset: 0,
				max: 0,
				_wpnonce: ''
			}, args );

			return $.post( ajaxurl, args );
		},
		getSingle: function( args ) {
			args = $.extend( {
				action: 'cb_notifications_get_single',
				id: 0,
				_wpnonce: ''
			}, args );
			return $.post( ajaxurl, args );
		},
		deleteOne: function( args ) {
			args = $.extend( {
				action: 'cb_notifications_delete',
				id: 0,
				_wpnonce: ''
			}, args );
			return $.post( ajaxurl, args );
		},
		deleteAll: function( args ) {
			args = $.extend( {
				action: 'cb_notifications_delete_bulk',
				ids: [],
				_wpnonce: ''
			}, args );
			return $.post( ajaxurl, args );
		},
		markRead: function( args ) {
			args = $.extend( {
				action: 'cb_notifications_mark_read',
				id: 0,
				_wpnonce: ''
			}, args );

			return $.post( ajaxurl, args );
		},
		markUnread: function( args ) {
			args = $.extend( {
				action: 'cb_notifications_mark_unread',
				id: 0,
				_wpnonce: ''
			}, args );

			return $.post( ajaxurl, args );
		},
		markAllRead: function( args ) {
			args = $.extend( {
				action: 'cb_notifications_mark_read_bulk',
				ids: [],
				_wpnonce: ''
			}, args );

			return $.post( ajaxurl, args );
		},
		markAllUnread: function( args ) {
			args = $.extend( {
				action: 'cb_notifications_mark_unread_bulk',
				ids: [],
				_wpnonce: ''
			}, args );

			return $.post( ajaxurl, args );
		}
	};
}( jQuery ) );

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

( function( $ ) {
	window.CB = window.CB || {};
	CB.Messages = CB.Messages || {};

	CB.Messages.Requests = {
		loadThreads: function( args ) {
			args = $.extend( {
				action: 'cb_messages_get_threads',
				page: 0,
				search_terms: '',
				type: 'all',
				_wpnonce: ''
			}, args );
			return $.post( ajaxurl, args );
		},
		loadThread: function( args ) {
			args = $.extend( {
				action: 'cb_messages_get_thread',
				id: 0,
				_wpnonce: ''
			}, args );
			return $.post( ajaxurl, args );
		},
		postReply: function( args ) {
			args = $.extend( {
				action: 'cb_messages_add_reply',
				thread_id: 0,
				content: '',
				_wpnonce: ''
			}, args );

			return $.post( ajaxurl, args );
		},
		postNewMessage: function( args ) {
			args = $.extend( {
				action: 'cb_messages_new_message',
				send_to: [],
				content: '',
				_wpnonce: ''
			}, args );

			return $.post( ajaxurl, args );
		},
		deleteThread: function( args ) {
			args = $.extend( {
				action: 'cb_messages_delete_thread',
				id: 0,
				_wpnonce: ''
			}, args );

			return $.post( ajaxurl, args );
		}
	};
}( jQuery ) );

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

( function( $ ) {
	$( document ).ready( function() {
		/** Invite Friends Interface ****************************************/

		/* Select a user from the list of friends and add them to the invite list */
		$( '#send-invite-form' ).on( 'click', '#invite-list input', function() {
			// invites-loop template contains a div with the .invite class
			// We use the existence of this div to check for old- vs new-
			// style templates.
			var invitesNewTemplate = $( '#send-invite-form > .invite' ).length,
				friendID, friendAction;

			$( '.ajax-loader' ).toggle();

			// Dim the form until the response arrives
			if ( invitesNewTemplate ) {
				$( this ).parents( 'ul' ).find( 'input' ).prop( 'disabled', true );
			}

			friendID = $( this ).val();

			if ( $( this ).prop( 'checked' ) === true ) {
				friendAction = 'invite';
			} else {
				friendAction = 'uninvite';
			}

			if ( ! invitesNewTemplate ) {
				$( '.item-list-tabs li.selected' ).addClass( 'loading' );
			}

			$.post( ajaxurl, {
				action: 'groups_invite_user',
				friend_action: friendAction,
				_wpnonce: $( '#_wpnonce_invite_uninvite_user' ).val(),
				friend_id: friendID,
				group_id: $( '#group_id' ).val()
			},
			function( response ) {
				if ( $( '#message' ) ) {
					$( '#message' ).hide();
				}

				var object = 'invite',
					filter = 'bp-invite-filter',
					scope = 'bp-invite-scope';

				if ( invitesNewTemplate ) {
					CB.Request.getItems( object, scope, filter, false, 1, '' ).done( function( response ) {
						var $itemListContainer = $( 'div.' + object );
						$itemListContainer.fadeOut( 100, function() {
							$( this ).html( response );
							$itemListContainer.trigger( 'updated:item:list', [ object, $itemListContainer ] );
							$( this ).fadeIn( 100 );
						} );
						$( '.item-list-tabs li.selected' ).removeClass( 'loading' );
					} );
				} else {
					// Old-style templates manipulate only the
					// single invitation element
					$( '.ajax-loader' ).toggle();

					if ( friendAction === 'invite' ) {
						$( '#friend-list' ).append( response );
					} else if ( friendAction === 'uninvite' ) {
						$( '#friend-list li#uid-' + friendID ).remove();
					}

					$( '.item-list-tabs li.selected' ).removeClass( 'loading' );
				}
			} );
		} );

		/* Remove a user from the list of users to invite to a group */
		$( '#send-invite-form' ).on( 'click', 'a.remove', function() {
			// invites-loop template contains a div with the .invite class
			// We use the existence of this div to check for old- vs new-
			// style templates.
			var invitesNewTemplate = $( '#send-invite-form > .invite' ).length,
				friendID = $( this ).attr( 'id' );

			$( '.ajax-loader' ).toggle();

			friendID = friendID.split( '-' );
			friendID = friendID[1];

			$.post( ajaxurl, {
				action: 'groups_invite_user',
				friend_action: 'uninvite',
				_wpnonce: $( '#_wpnonce_invite_uninvite_user' ).val(),
				friend_id: friendID,
				group_id: $( '#group_id' ).val()
			},
			function( response ) {
				if ( invitesNewTemplate ) {
					// With new-style templates, we refresh the
					// entire list
					var object = 'invite',
						filter = 'bp-invite-filter',
						scope = 'bp-invite-scope';
					CB.Request.getItems( object, scope, filter, false, 1, '' ).done( function( response ) {
						var $itemListContainer = $( 'div.' + object );
						$itemListContainer.fadeOut( 100, function() {
							$( this ).html( response );
							$itemListContainer.trigger( 'updated:item:list', [ object, $itemListContainer ] );
							$( this ).fadeIn( 100 );
						} );
						$( '.item-list-tabs li.selected' ).removeClass( 'loading' );
					} );
				} else {
					// Old-style templates manipulate only the
					// single invitation element
					$( '.ajax-loader' ).toggle();
					$( '#friend-list #uid-' + friendID ).remove();
					$( '#invite-list #f-' + friendID ).prop( 'checked', false );
				}
			} );

			return false;
		} );
	} );
}( jQuery ) );

( function( $, CB ) {
	var Feedback = CB.Feedback || {};
	CB.Feedback = $.extend( Feedback, {
		show: function( message, type, context ) {

		},
		// Prepare markup for plain text notices.
		prepareText: function( text, type ) {
			return '<div class="bp-feedback bp-feedback-type-' + type + '"><p>' + text + '</p></div>';
		},
		success: function( message, context ) {
			return this.show( message, 'success', context );
		},
		error: function( message, context ) {
			return this.show( message, 'error', context );
		},
		notice: function( message, context ) {
			return this.show( message, 'notice', context );
		}
	} );
}( jQuery, CB || {} ) );

( function( $, CB ) {
	var Loader = CB.Loader | {};
	CB.Loader = $.extend( Loader, {
		show: function( context ) {
		},
		hide: function( context ) {

		},
		showGlobal: function() {

		},
		hideGlobal: function() {

		}
	} );
}( jQuery, CB || {} ) );



/* Returns a querystring of BP cookies (cookies beginning with 'bp-') */
function bp_get_cookies() {
	var allCookies = document.cookie.split( ';' ), // get all cookies and split into an array
		bpCookies = {},
		cookiePrefix = 'bp-',
		i, cookie, delimiter, name, value;

	// loop through cookies
	for ( i = 0; i < allCookies.length; i++ ) {
		cookie = allCookies[i];
		delimiter = cookie.indexOf( '=' );
		name = jQuery.trim( unescape( cookie.slice( 0, delimiter ) ) );
		value = unescape( cookie.slice( delimiter + 1 ) );

		// if BP cookie, store it
		if ( name.indexOf( cookiePrefix ) === 0 ) {
			bpCookies[name] = value;
		}
	}

	// returns BP cookies as querystring
	return encodeURIComponent( jQuery.param( bpCookies ) );
}

/**
 * Get a querystring parameter from a URL.
 *
 * @param {string} Query string parameter name.
 * @param param
 * @param url
 * @param {string} URL to parse. Defaults to current URL.
 */
function bp_get_query_var( param, url ) {
	var qs = {};

	// Use current URL if no URL passed.
	if ( typeof url === 'undefined' ) {
		url = location.search.substr( 1 ).split( '&' );
	} else {
		url = url.split( '?' );
		url = url.length > 1 ? url[1].split( '&' ) : [];
	}

	// Parse querystring into object props.
	// http://stackoverflow.com/a/21152762
	url.forEach( function( item ) {
		qs[item.split( '=' )[0]] = item.split( '=' )[1] && decodeURIComponent( item.split( '=' )[1] );
	} );

	if ( qs.hasOwnProperty( param ) && qs[param] != null ) {
		return qs[param];
	}
	return false;
}

/**
 * Deselects any select options or input options for the specified field element.
 *
 * @param {string} container HTML ID of the field
 */
function clear( container ) {
	var radioButtons, options, i;
	container = document.getElementById( container );
	if ( ! container ) {
		return;
	}

	radioButtons = container.getElementsByTagName( 'INPUT' );
	options = container.getElementsByTagName( 'OPTION' );
	i = 0;

	if ( radioButtons ) {
		for ( i = 0; i < radioButtons.length; i++ ) {
			radioButtons[i].checked = '';
		}
	}

	if ( options ) {
		for ( i = 0; i < options.length; i++ ) {
			options[i].selected = false;
		}
	}
}
