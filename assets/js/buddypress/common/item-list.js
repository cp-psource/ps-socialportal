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
