( function( $, api ) {
	wp.customize.controlConstructor['range-responsive'] = wp.customize.Control.extend( {
		ready: function() {
			'use strict';

			var control = this,
				container = this.container,
				updating = false,
				val = {};

			if ( control.params.default ) {
				// val = control.params['default'];
			}

			container.on( 'change keyup paste', '.cb-responsive-range-control', function() {
				if ( updating ) {
					return;
				}

				updating = true;
				var $el = $( this );
				// Add the value to the array and set the setting's value
				val = {
					mobile: $el.data( 'mobile' ),
					tablet: $el.data( 'tablet' ),
					desktop: $el.data( 'desktop' )
				};
				control.saveValue( val );
				updating = false;
			} );

			control.setting.bind( function( value ) {
				// Bail if the update came from the control itself.
				if ( updating ) {
					return;
				}

				var device,
					currentDevice = api.previewedDevice.get(),
					$el = container.find( '.cb-responsive-range-control' ),
					devices = { mobile: true, tablet: true, desktop: true };

				currentDevice = currentDevice || 'desktop';

				updating = true;

				if ( ! _.isObject( value ) ) {
					var newVal = {};
					for ( device in devices ) {
						newVal[device] = value;
					}

					value = newVal;
					control.setting.set( value );
				}

				for ( device in value ) {
					$el.data( device, value[device] );
					if ( currentDevice == device ) {
						$el.val( value[device] );
						// for slider.
						$el.trigger( 'change' );
					}
				}

				// we need to update ui slider too.
				updating = false;
			} );
		},

		/**
		 * Saves the value.
		 *
		 * @param value
		 */
		saveValue: function( value ) {
			'use strict';

			var control = this;
			control.setting.set( value );
		}

	} );

	// Update value in the UI.
	$( document ).ready( function() {
		$( 'html' ).addClass( 'cb-range-responsive-control-ready' );
		var $inputs = $( '.cb-responsive-range-control' );

		wp.customize.previewedDevice.bind( function( device ) {
			$inputs.each( function() {
				$( this ).val( $( this ).data( device ) );
			} );
		} );
	} );
}( jQuery, wp.customize ) );
