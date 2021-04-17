( function( $ ) {
	// Fire events on font loaded.
	if ( document.fonts && document.fonts.ready ) {
		document.fonts.ready.then( function() {
			$( document ).trigger( 'cb:fonts:loaded' );
		} );
	}

	$( document ).ready( function() {
		// enable greedy nav.
		var $siteHeaderRowMain = $( '#site-header-row-main' ),
			$mainMeu = $( '#main-menu' ),
			$hBottomMenu = $( '#header-bottom-menu' ),
			$qMenuTop = $( '#quick-menu-1' ),
			$container,
			stickyDistance = 50,
			// Customizer partial refresh, rebind panels.
			isCustomize = 'undefined' !== typeof wp && wp.customize && wp.customize.selectiveRefresh;

		$( 'body' ).addClass( 'page-ready' );

		// enable off-canvas panels.
		enablePanelSliders();

		// Main menu, enable greedy nav.
		if ( $mainMeu.length ) {
			$mainMeu.greedyNav( {
				LinksSelector: '.nav-list',
				buttonLabel: '<i class="fa fa-ellipsis-h" aria-hidden="true"></i>'
			} );
		}

		// Header bottom menu, enable greedy nav.
		if ( $hBottomMenu.length ) {
			$hBottomMenu.greedyNav( {
				LinksSelector: '.nav-list',
				buttonLabel: '<i class="fa fa-ellipsis-h" aria-hidden="true"></i>'
			} );
		}

		// Quick menu, enable greedy nav.
		if ( $qMenuTop.length ) {
			$qMenuTop.greedyNav( {
				LinksSelector: '.nav-list',
				buttonLabel: '<i class="fa fa-ellipsis-h" aria-hidden="true"></i>'
			} );
		}

		// Masonry Posts.
		$container = $( '.posts-display-masonry' );

		$container.imagesLoaded( function() {
			$container.masonry( {
				columnWidth: '.post-display-type-masonry',
				itemSelector: '.post-display-type-masonry'
			} );
		} );

		// Init the panel scrollbars.
		initPanelScrollbars();

		// enable liquid image for thumbnails
		if ( CommunityBuilder.featuredImageFitContainer ) {
			$( '.has-post-thumbnail a.post-thumbnail' ).imgLiquid();
			$( '.has-post-thumbnail div.post-thumbnail' ).imgLiquid();
		}

		if ( CommunityBuilder.enableTextareaAutogrow && typeof autosize !== 'undefined' ) {
			// Make all textarea autogrowable.
			$( document ).on( 'focus', 'textarea', function() {
				autosize( this );
			} );
		}

		// panel menu.
		if ( typeof $.treeNav !== 'undefined' ) {
			$.treeNav( '.panel-menu' );
		}

		// setup scroll to top.
		$.scrollUp( {
			scrollName: 'scrollUp', // Element ID
			scrollDistance: 300, // Distance from top/bottom before showing element (px)
			scrollFrom: 'top', // 'top' or 'bottom'
			scrollSpeed: 300, // Speed back to top (ms)
			easingType: 'linear', // Scroll to top easing (see http://easings.net/)
			animation: 'fade', // Fade, slide, none
			animationInSpeed: 200, // Animation in speed (ms)
			animationOutSpeed: 200, // Animation out speed (ms)
			scrollText: '<i class="fa fa-chevron-circle-up"></i>', // Text for element, can contain HTML
			scrollTitle: false, // Set a custom <a> title if required. Defaults to scrollText
			scrollImg: false, // Set true to use image
			activeOverlay: false, // Set CSS color to display scrollUp active point, e.g '#00FFFF'
			zIndex: 2147483647 // hahaha, that is some z index. Not required to have this value but nothing wrong to have it Z-Index for the overlay
		} );

		// sticky header.
		if ( true || CommunityBuilder.enableStickyHeader ) {
			stickyDistance = 50;

			$( window ).scroll( $.cbDebounce( function() {
				if ( $( window ).scrollTop() > stickyDistance ) {
					$siteHeaderRowMain.addClass( 'sticky-header' );
				} else {
					$siteHeaderRowMain.removeClass( 'sticky-header' );
				}
			}, 10 ) );
		}

		if ( isCustomize ) {
			// On site header update, reattach event handlers.
			wp.customize.selectiveRefresh.bind( 'partial-content-rendered', function( sidebarPartial ) {
				if ( sidebarPartial.partial && sidebarPartial.partial.id === 'site_header' ) {
					enablePanelSliders();
				}
			} );
		}
		// for bbpress.
		$( '.bbp-template-notice' ).each( function() {
			$( this ).prepend( '<div class="bbp-template-notice-icon"><span class="icon"></span>' );
		} );
	} );// end of domready.

	/**
	 * Initialize panel scrollbars.
	 */
	function initPanelScrollbars() {
		var panelLeft, panelRight;

		if ( typeof SimpleScrollbar === 'undefined' ) {
			return;
		}

		// Enable the Nice scrollbar in the left/right panel.
		panelLeft = document.querySelector( '#panel-left' );
		panelRight = document.querySelector( '#panel-right' );

		if ( panelLeft !== null ) {
			SimpleScrollbar.initEl( panelLeft );
		}

		if ( panelRight !== null ) {
			SimpleScrollbar.initEl( panelRight );
		}
	}

	/**
	 * Enable Panel sliders.
	 */
	function enablePanelSliders() {
		// unbind any click handler.
		$( '#panel-left-toggle, #panel-right-toggle' ).off( 'click' );
		// Left panel toggles
		$( '#panel-left-toggle' ).panelslider();

		// Right panel
		$( '#panel-right-toggle' ).panelslider( {
			bodyClass: 'ps-active-right',
			clickClose: true
		} );
	}
}( jQuery ) );
