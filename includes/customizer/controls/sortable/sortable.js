/**
 * Sortable control.
 */

wp.customize.controlConstructor['cb-sortable'] = wp.customize.Control.extend( {
	ready: function() {
		'use strict';

		var control = this,
			updating = false;

		// Set the sortable container.
		control.sortableContainer = control.container.find( 'ul.sortable' ).first();

		// Init sortable.
		control.sortableContainer.sortable( {
			// Update value when we stop sorting.
			stop: function() {
				if ( updating ) {
					return;
				}
				updating = true;
				control.updateValue();
				updating = false;
			}
		} ).disableSelection().find( 'li' ).each( function() {
			// Enable/disable options when we Eye icon is clicked.
			jQuery( this ).find( 'i.visibility' ).click( function() {
				jQuery( this ).toggleClass( 'dashicons-visibility-faint' ).parents( 'li:eq(0)' ).toggleClass( 'cb-sortable-item-invisible' );
			} );
		} ).click( function() {
			// Update value on click.
			if ( ! updating ) {
				updating = true;
				control.updateValue();
				updating = false;
			}
		} );

		// on settings change, update control to reflect the state.
		control.setting.bind( function( value ) {
			// Bail if the update came from the control itself.
			if ( updating ) {
				return;
			}
			// how do we reflect the state.

			updating = true;
			control.sortableContainer.find( 'li' ).each( function() {
				var $li = jQuery( this );

				if ( -1 === jQuery.inArray( $li.data( 'value' ) ) ) {
					$li.addClass( 'cb-sortable-item-invisible' );
				} else {
					$li.removeClass( 'cb-sortable-item-invisible' );
				}
			} );
			updating = false;
		} );
	},

	/**
	 * Updates the sorting list
	 */
	updateValue: function() {
		'use strict';
		var control = this,
			newValue = [];

		this.sortableContainer.find( 'li' ).each( function() {
			if ( ! jQuery( this ).is( '.cb-sortable-item-invisible' ) ) {
				newValue.push( jQuery( this ).data( 'value' ) );
			}
		} );

		control.setting.set( newValue );
	}
} );
