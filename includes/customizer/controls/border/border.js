/**
 * Border Control
 */
( function( $ ) {
	wp.customize.controlConstructor['cb-border'] = wp.customize.Control.extend( {

		ready: function() {
			'use strict';

			var control = this,
				borderValue,
				value = {},
				picker,
				updating = false,
				borderStyle,
				borderWidth,
				$html = $( 'html' );

			// Make sure everything we're going to need exists.
			_.each( control.params.default, function( defaultParamValue, param ) {
				if ( false !== defaultParamValue ) {
					value[param] = defaultParamValue;
					if ( undefined !== control.setting._value[param] ) {
						value[param] = control.setting._value[param];
					}
				}
			} );

			_.each( control.setting._value, function( subValue, param ) {
				if ( undefined === value[param] ) {
					value[param] = subValue;
				}
			} );

			// Save the value.
			this.container.on( 'change keyup paste', 'input.cb-border-input', function() {
				if ( updating ) {
					return;
				}

				updating = true;
				borderValue = $( this ).val();

				// Update value on change.
				control.updateWithBorderWidth( value );

				updating = false;
			} );

			// border style.
			this.container.on( 'change', '.border-style select', function() {
				if ( updating ) {
					return;
				}
				updating = true;
				// Add the value to the array and set the setting's value.
				value['border-style'] = jQuery( this ).val();
				//value['changed'] = 1;
				control.saveValue( value );
				updating = false;
			} );

			borderWidth = this.container.find( '.border-width input' );
			borderStyle = this.container.find( '.border-style select' );

			picker = this.container.find( '.cb-color-control' );

			// Change color.
			picker.wpColorPicker( {
				change: function() {
					if ( updating || ! $html.hasClass( 'cb-border-control-ready' ) ) {
						return;
					}

					//setTimeout( function() {
					updating = true;
					// Add the value to the array and set the setting's value
					value['border-color'] = picker.val();
					control.saveValue( value );
					updating = false;
					//}, 100 );
				}
			} );
			// Collapse color picker when hitting Esc instead of collapsing the current section.
			control.container.on( 'keydown', function( event ) {
				var pickerContainer;
				if ( 27 !== event.which ) { // Esc.
					return;
				}
				pickerContainer = control.container.find( '.wp-picker-container' );
				if ( pickerContainer.hasClass( 'wp-picker-active' ) ) {
					picker.wpColorPicker( 'close' );
					control.container.find( '.wp-color-result' ).focus();
					event.stopPropagation(); // Prevent section from being collapsed.
				}
			} );

			// on settings change, update controls to reflect the state.
			control.setting.bind( function( value ) {
				// Bail if the update came from the control itself.
				if ( updating ) {
					return;
				}

				updating = true;

				// width.
				//  if (borderWidth.length && value['border-width'] && value['border-width'] != borderWidth.val()) {
				// borderWidth.val(value['border-width']);
				//  }

				// style.
				if ( borderStyle.length && value['border-style'] && value['border-style'] != borderStyle.val() ) {
					borderStyle.val( value['border-style'] );
				}

				// color.
				if ( picker.length && value['border-color'] && value['border-color'] != picker.val() ) {
					picker.val( value['border-color'] );
					picker.wpColorPicker( 'color', value['border-color'] );
				}

				updating = false;
			} );
		},

		/**
		 * Updates the  values
		 *
		 * @param value
		 */
		updateWithBorderWidth: function( value ) {
			'use strict';

			var control = this,
				newValue = {
					top: 0,
					right: 0,
					bottom: 0,
					left: 0
				};

			control.container.find( 'input.cb-border-input' ).each( function() {
				var input = $( this ),
					item = input.data( 'id' );

				newValue[item] = input.val();
			} );

			value['border-width'] = newValue;

			control.saveValue( value );
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
		$( 'html' ).addClass( 'cb-border-control-ready' );
		// Connected button
		$( document ).on( 'click', '.cb-border-connected-icon', function() {
			// Remove connected class
			$( this ).parents( '.cb-border-input-wrapper' ).removeClass( 'cb-border-connected' ).addClass( 'cb-border-disconnected' );
		} );

		// Disconnected button
		$( document ).on( 'click', '.cb-border-disconnected-icon', function() {
			// Add connected class
			$( this ).parents( '.cb-border-input-wrapper' ).removeClass( 'cb-border-disconnected' ).addClass( 'cb-border-connected' );
		} );

		// Values connected inputs
		$( document ).on( 'input', '.cb-border-input-wrapper .cb-border-input', function() {
			// if not connected currently, return.
			if ( ! $( this ).parents( '.cb-border-connected' ).length ) {
				return false;
			}

			var currentFieldValue = $( this ).val();

			$( this ).parents( '.cb-border-connected' ).find( '.cb-border-input' ).each( function( key, value ) {
				$( this ).val( currentFieldValue ).change();
			} );
			return false;
		} );
	} );
}( jQuery ) );
