( function( $ ) {
	$( window ).on( 'load', function() {
		$( 'html' ).addClass( 'cb-background-control-ready' );
	} );

	wp.customize.controlConstructor['cb-background'] = wp.customize.Control.extend( {

		ready: function() {
			'use strict';

			var control = this,
				value = {}, // control.setting._value,
				$picker = control.container.find( '.cb-color-control' ),
				$bgRepeat = control.container.find( '.background-wrapper > .background-repeat select' ),
				$bgPosition = control.container.find( '.background-wrapper > .background-position select' ),
				$bgSize = control.container.find( '.background-wrapper > .background-size input' ),
				$bgAttachment = control.container.find( '.background-wrapper > .background-attachment input' ),
				updating = false,
				$html = $( 'html' );

			_.each( control.params.default, function( defaultParamValue, param ) {
				if ( false !== defaultParamValue ) {
					value[param] = defaultParamValue;
					if ( undefined !== control.setting._value[param] ) {
						value[param] = control.setting._value[param];
					}
				}
			} );

			_.each( control.setting._value, function( subValue, param ) {
				if ( undefined === value[param] || 'undefined' === typeof value[param] ) {
					value[param] = subValue;
				}
			} );

			// Hide unnecessary controls if the value doesn't have an image.
			if ( _.isUndefined( value['background-image'] ) || '' === value['background-image'] ) {
				control.container.find( '.background-wrapper > .background-repeat, .background-wrapper > .background-position, .background-wrapper > .background-size, .background-wrapper > .background-attachment' ).hide();
			}

			// Color.
			$picker.wpColorPicker( {
				change: function() {
					if ( updating || ! $html.hasClass( 'cb-background-control-ready' ) ) {
						return;
					}
					updating = true;
					value['background-color'] = $picker.val();
					control.saveValue( value );
					updating = false;
				},

				/**
				 * @param {Event} event - standard jQuery event, produced by "Clear"
				 * button.
				 */
				clear: function( event ) {
					var element;
					if ( updating ) {
						return;
					}
					updating = true;
					element = $( event.target ).closest( '.wp-picker-input-wrap' ).find( '.wp-color-picker' )[0];

					if ( element ) {
						value['background-color'] = '';
						control.saveValue( value );
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
					$picker.wpColorPicker( 'close' );
					control.container.find( '.wp-color-result' ).focus();
					event.stopPropagation(); // Prevent section from being collapsed.
				}
			} );

			// Background-Repeat.
			control.container.on( 'change', '.background-repeat select', function() {
				if ( updating ) {
					return;
				}
				updating = true;
				value['background-repeat'] = $( this ).val();
				control.saveValue( value );
				updating = false;
			} );

			// Background-Size.
			control.container.on( 'change click', '.background-size input', function() {
				if ( updating ) {
					return;
				}
				updating = true;
				value['background-size'] = $( this ).val();
				control.saveValue( value );
				updating = false;
			} );

			// Background-Position.
			control.container.on( 'change', '.background-position select', function() {
				if ( updating ) {
					return;
				}
				updating = true;
				value['background-position'] = $( this ).val();
				control.saveValue( value );
				updating = false;
			} );

			// Background-Attachment.
			control.container.on( 'change click', '.background-attachment input', function() {
				if ( updating ) {
					return;
				}
				updating = true;
				value['background-attachment'] = $( this ).val();
				control.saveValue( value );
				updating = false;
			} );

			// Background-Image.
			control.container.on( 'click', '.background-image-upload-button', function( e ) {
				var image = wp.media( { multiple: false } ).open().on( 'select', function() {
					// This will return the selected image from the Media Uploader, the result is an object.
					var uploadedImage = image.state().get( 'selection' ).first(),
						previewImage = uploadedImage.toJSON().sizes.full.url,
						imageUrl,
						imageID,
						imageWidth,
						imageHeight,
						preview,
						removeButton;

					if ( ! _.isUndefined( uploadedImage.toJSON().sizes.medium ) ) {
						previewImage = uploadedImage.toJSON().sizes.medium.url;
					} else if ( ! _.isUndefined( uploadedImage.toJSON().sizes.thumbnail ) ) {
						previewImage = uploadedImage.toJSON().sizes.thumbnail.url;
					}

					imageUrl = uploadedImage.toJSON().sizes.full.url;
					imageID = uploadedImage.toJSON().id;
					imageWidth = uploadedImage.toJSON().width;
					imageHeight = uploadedImage.toJSON().height;

					// Show extra controls if the value has an image.
					if ( '' !== imageUrl ) {
						control.container.find( '.background-wrapper > .background-repeat, .background-wrapper > .background-position, .background-wrapper > .background-size, .background-wrapper > .background-attachment' ).show();
					}
					value['background-image'] = imageUrl;
					control.saveValue( value );
					preview = control.container.find( '.placeholder, .thumbnail' );
					removeButton = control.container.find( '.background-image-upload-remove-button' );

					if ( preview.length ) {
						preview.removeClass().addClass( 'thumbnail thumbnail-image' ).html( '<img src="' + previewImage + '" alt="" />' );
					}
					if ( removeButton.length ) {
						removeButton.show();
					}
				} );

				e.preventDefault();
			} );

			control.container.on( 'click', '.background-image-upload-remove-button', function( e ) {
				var preview,
					removeButton;

				e.preventDefault();
				value['background-image'] = '';
				control.saveValue( value );

				preview = control.container.find( '.placeholder, .thumbnail' );
				removeButton = control.container.find( '.background-image-upload-remove-button' );

				// Hide unnecessary controls.
				control.container.find( '.background-wrapper > .background-repeat' ).hide();
				control.container.find( '.background-wrapper > .background-position' ).hide();
				control.container.find( '.background-wrapper > .background-size' ).hide();
				control.container.find( '.background-wrapper > .background-attachment' ).hide();

				control.container.find( '.more-settings' ).attr( 'data-direction', 'down' );
				control.container.find( '.more-settings' ).find( '.message' ).html( _CBCustomizeControlBackground.moreSettings );
				control.container.find( '.more-settings' ).find( '.icon' ).html( '↓' );

				if ( preview.length ) {
					preview.removeClass().addClass( 'placeholder' ).html( _CBCustomizeControlBackground.placeholder );
				}
				if ( removeButton.length ) {
					removeButton.hide();
				}
			} );

			control.container.on( 'click', '.more-settings', function( e ) {
				// Hide unnecessary controls.
				control.container.find( '.background-wrapper > .background-repeat' ).toggle();
				control.container.find( '.background-wrapper > .background-position' ).toggle();
				control.container.find( '.background-wrapper > .background-size' ).toggle();
				control.container.find( '.background-wrapper > .background-attachment' ).toggle();

				if ( 'down' === $( this ).attr( 'data-direction' ) ) {
					$( this ).attr( 'data-direction', 'up' );
					$( this ).find( '.message' ).html( _CBCustomizeControlBackground.lessSettings );
					$( this ).find( '.icon' ).html( '↑' );
				} else {
					$( this ).attr( 'data-direction', 'down' );
					$( this ).find( '.message' ).html( _CBCustomizeControlBackground.moreSettings );
					$( this ).find( '.icon' ).html( '↓' );
				}
			} );

			// on settings change, update control to reflect the state.
			control.setting.bind( function( value ) {
				// Bail if the update came from the control itself.
				if ( updating ) {
					return;
				}

				updating = true;

				if ( $bgRepeat.length && value['background-repeat'] && value['background-repeat'] != $bgRepeat.val() ) {
					$bgRepeat.val( value['background-repeat'] );
				}

				if ( $bgPosition.length && value['background-position'] && value['background-position'] != $bgPosition.val() ) {
					$bgPosition.val( value['background-position'] );
				}

				if ( $bgSize.length && value['background-size'] ) {
					// find current selected one
					var selectedBGSize = $bgSize.filter( ':checked' );

					// No item selected currently.
					if ( ! selectedBGSize.length ) {
						// if none selected, just check and forget.
						updateSelectedCheckbox( $bgSize, value['background-size'] );
					} else if ( selectedBGSize.val() !== value['background-size'] ) {
						// unselect current
						selectedBGSize.prop( 'checked', false );
						selectedBGSize.next( '.switch-label-on' ).toggleClass( 'switch-label-off switch-label-on' );
						// select the new
						updateSelectedCheckbox( $bgSize, value['background-size'] );
					}
				}

				if ( $bgAttachment.length && value['background-attachment'] ) {
					// find current selected one
					var selectedBGAttachment = $bgSize.filter( ':checked' );

					// No item selected currently.
					if ( ! selectedBGAttachment.length ) {
						// if none selected, just check and forget.
						updateSelectedCheckbox( $bgAttachment, value['background-attachment'] );
					} else if ( selectedBGAttachment.val() !== value['background-attachment'] ) {
						// unselect current
						selectedBGAttachment.prop( 'checked', false );
						selectedBGAttachment.next( '.switch-label-on' ).toggleClass( 'switch-label-off switch-label-on' );
						// select the new
						updateSelectedCheckbox( $bgAttachment, value['background-attachment'] );
					}
				}

				var preview = control.container.find( '.placeholder, .thumbnail' );

				if ( preview.length && value['background-image'] && '' !== value['background-image'] ) {
					preview.removeClass().addClass( 'thumbnail thumbnail-image' ).html( '<img src="' + value['background-image'] + '" alt="" />' );
				}

				updating = false;
			} );

			function updateSelectedCheckbox( $el, val ) {
				$el.filter( function() {
					var $this = $( this );
					if ( $this.val() == val ) {
						$this.prop( 'checked', true );
						$this.next( 'switch-label' ).addClass( 'switch-label-on' ).removeClass( 'switch-label-off' );
					}
				} );
			}
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
}( jQuery ) );
