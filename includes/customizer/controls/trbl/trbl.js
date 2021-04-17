/**
 * TRBL Control.
 */
( function( $ ) {
	wp.customize.controlConstructor['cb-trbl'] = wp.customize.Control.extend( {

		ready: function() {
			'use strict';

			var control = this,
				value = {},
				updating = false,
				elementSelector;

			// Make sure everything we're going to need exists.
			_.each( control.params.default, function( defaultParamValue, key ) {
				// value[top], value[left] ...etc.
				if ( false !== defaultParamValue ) {
					value[key] = defaultParamValue;
					if ( undefined !== control.setting._value[key] ) {
						value[key] = control.setting._value[key];
					}
				}
			} );

			_.each( control.setting._value, function( subValue, key ) {
				if ( undefined === value[key] ) {
					value[key] = subValue;
				}
			} );

			// Save the value.
			this.container.on( 'change keyup paste', 'input.cb-trbl-input', function() {
				if ( updating ) {
					return;
				}

				updating = true;
				// Update value on change.
				control.updateValues();

				updating = false;
			} );

			elementSelector = this.container.find( '.cb-trbl-items' );

			// on settings change, update controls to reflect the state.
			control.setting.bind( function( value ) {
				// Bail if the update came from the control itself.
				if ( updating ) {
					return;
				}

				updating = true;

				// width.
				if ( elementSelector.length && value && value != elementSelector.val() ) {
					//  borderWidth.val(value['border-width']);
				}

				updating = false;
			} );
		},

		/**
		 * Updates the spacing values
		 */
		updateValues: function() {
			'use strict';

			var control = this,
				newValue = {
					top: 0,
					right: 0,
					bottom: 0,
					left: 0
				};

			control.container.find( 'input.cb-trbl-input' ).each( function() {
				var input = $( this ),
					item = input.data( 'id' );

				newValue[item] = input.val();
			} );

			control.saveValue( newValue );
		},

		/**
		 * Saves the value.
		 *
		 * @param value
		 */
		saveValue: function( value ) {
			'use strict';

			var control = this,
				newValue = {};

			_.each( value, function( newSubValue, i ) {
				newValue[i] = newSubValue;
			} );

			control.setting.set( newValue );
		}
	} );

	$( document ).ready( function() {
		$( 'html' ).addClass( 'cb-trbl-control-ready' );
		// Connected button
		$( document ).on( 'click', '.cb-trbl-connected-icon', function() {
			// Toggle Classes(toggleClass() may be a better function here).
			$( this ).parents( '.cb-trbl-input-wrapper' ).removeClass( 'cb-trbl-connected' ).addClass( 'cb-trbl-disconnected' );
		} );

		// Disconnected button
		$( document ).on( 'click', '.cb-trbl-disconnected-icon', function() {
			// Add connected class
			$( this ).parents( '.cb-trbl-input-wrapper' ).removeClass( 'cb-trbl-disconnected' ).addClass( 'cb-trbl-connected' );
		} );

		// Values connected inputs
		$( document ).on( 'input', '.cb-trbl-input-wrapper .cb-trbl-input', function() {
			// if not connected currently, return.
			if ( ! $( this ).parents( '.cb-trbl-connected' ).length ) {
				return false;
			}

			var currentFieldValue = $( this ).val();

			$( this ).parents( '.cb-trbl-connected' ).find( '.cb-trbl-input' ).each( function( key, value ) {
				$( this ).val( currentFieldValue ).change();
			} );
			return false;
		} );
	} );
}( jQuery ) );
