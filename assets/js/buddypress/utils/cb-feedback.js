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
