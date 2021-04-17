/**
 * Alpha color picker control.
 */
( function( $ ) {
	$( window ).on( 'load', function() {
		$( 'html' ).addClass( 'cb-color-control-ready' );
	} );

	wp.customize.controlConstructor['cb-color'] = wp.customize.Control.extend( {

		ready: function() {
			'use strict';

			var control = this,
				updating = false,
				$picker = this.container.find( '.cb-color-picker-alpha' ),
				$html = $( 'html' );

			$picker.wpColorPicker( {
				/**
				 * @param {Event} event - standard jQuery event, produced by whichever
				 * control was changed.
				 * @param {Object} ui - standard jQuery UI object, with a color member
				 * containing a Color.js object.
				 */
				change: function( event, ui ) {
					updating = true;
					var color = ui.color.toString();

					if ( $html.hasClass( 'cb-color-control-ready' ) ) {
						control.setting.set( color );
					}

					updating = false;
				},

				/**
				 * @param {Event} event - standard jQuery event, produced by "Clear"
				 * button.
				 */
				clear: function( event ) {
					updating = true;
					var element = jQuery( event.target ).closest( '.wp-picker-input-wrap' ).find( '.wp-color-picker' )[0];
					var color = '';

					if ( element ) {
						// Add your code here
						control.setting.set( color );
					}

					updating = false;
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
			control.setting.bind( function( value ) {
				// Bail if the update came from the control itself.
				if ( updating ) {
					return;
				}
				$picker.val( value );
				$picker.wpColorPicker( 'color', value );
			} );
		}
	} );
}( jQuery ) );
