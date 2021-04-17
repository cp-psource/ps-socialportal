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

			elementSelector = this.container.find( '.cb-trbl-items' );

			// Save the value on input change.
			this.container.on( 'change keyup paste', 'input.cb-trbl-input', function() {
				if ( updating ) {
					return;
				}
				updating = true;

				var currentDevice = wp.customize.previewedDevice.get();
				currentDevice = currentDevice || 'desktop';
				$( this ).data( currentDevice, $( this ).val() );
				// Update value on change.
				control.updateValues();
				updating = false;
			} );

			// on settings change, update controls to reflect the state.
			control.setting.bind( function( value ) {
				// Bail if the update came from the control itself.
				if ( updating ) {
					return;
				}

				updating = true;
				var currentDevice = wp.customize.previewedDevice.get();
				currentDevice = currentDevice || 'desktop';

				// numeric value was given
				// update for current device.
				if ( _.isNumber( value ) ) {
					control.container.find( 'input.cb-trbl-input' ).each( function() {
						var $input = $( this );
						$input.data( currentDevice, value );
						$input.val( value );
					} );
					control.updateValues();
					updating = false;
					return;
				}

				// assume value to be object.
				var devices = { mobile: true, tablet: true, desktop: true },
					withDeviceType = false,
					deviceType = '';

				for ( deviceType in devices ) {
					if ( _.has( value, deviceType ) ) {
						withDeviceType = true;
						break;
					}
				}

				// if the value is not given with the device type, let us make that.
				if ( ! withDeviceType ) {
					var old = value;
					value = {};
					value[currentDevice] = old;
				}
				// update the state.
				control.container.find( 'input.cb-trbl-input' ).each( function() {
					var input = $( this ),
						item = input.data( 'id' );
					for ( deviceType in devices ) {
						if ( ! _.has( value, deviceType ) || ! value[deviceType][item] ) {
							continue;
						}

						input.data( deviceType, value[deviceType][item] );
						// currently selected device.
						if ( deviceType == currentDevice ) {
							input.val( value[deviceType][item] );
						}
					}
				} );

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
					mobile: {
						top: 0,
						right: 0,
						bottom: 0,
						left: 0
					},
					tablet: {
						top: 0,
						right: 0,
						bottom: 0,
						left: 0
					},
					desktop: {
						top: 0,
						right: 0,
						bottom: 0,
						left: 0
					}
				};

			control.container.find( 'input.cb-trbl-input' ).each( function() {
				var input = $( this ),
					item = input.data( 'id' );
				for ( var device in { mobile: true, tablet: true, desktop: true } ) {
					newValue[device][item] = input.data( device );
				}
				// newValue[item] = input.val();
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
		var $trbl_inputs = $( '.cb-trbl-input-wrapper .cb-trbl-input' );

		wp.customize.previewedDevice.bind( function( device ) {
			$trbl_inputs.each( function() {
				$( this ).val( $( this ).data( device ) );
			} );
		} );

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
			var device = wp.customize.previewedDevice.get();
			device = device || 'desktop';
			var currentFieldValue = $( this ).val();

			$( this ).parents( '.cb-trbl-connected' ).find( '.cb-trbl-input' ).each( function( key, value ) {
				$( this ).data( device, currentFieldValue );
				$( this ).val( currentFieldValue ).change();
			} );
			return false;
		} );
	} );
}( jQuery ) );
