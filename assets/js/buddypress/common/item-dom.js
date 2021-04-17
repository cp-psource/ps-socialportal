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
