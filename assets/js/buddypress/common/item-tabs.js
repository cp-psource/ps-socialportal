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
