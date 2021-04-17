( function( $ ) {
	$( window ).on( 'load', function() {
		$( 'html' ).addClass( 'cb-typography-control-ready' );
	} );

	wp.customize.controlConstructor.typography = wp.customize.Control.extend( {

		ready: function() {
			'use strict';

			var control = this,
				container = this.container,
				hasDefault = false,
				firstAvailable = false,
				activeItem,
				value = {},
				renderSubControl,
				picker,
				picker2,
				updating = false,
				$fontFamilyControl = container.find( '.font-family select' ),
				$variantControl = container.find( '.variant select' ),
				$subsetControl = container.find( '.subsets select' ),
				$textTransformControl = container.find( '.text-transform select' ),
				$fontSizeControl = container.find( '.font-size input' ),
				$lineHeightControl = container.find( '.line-height input' ),
				$letterSpacingControl = container.find( '.letter-spacing input' ),
				$textAlignControl = container.find( '.text-align input' ),
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
				if ( undefined === value[param] || 'undefined' === typeof value[param] ) {
					value[param] = subValue;
				}
			} );

			// Renders and refreshes selectize sub-controls.
			renderSubControl = function( fontFamily, sub, startValue ) {
				var $subSelectorControl = ( 'variant' === sub ) ? $variantControl : $subsetControl,
					isStandard = false,
					subList = {},
					subValue,
					subsetValues,
					subsetValuesArray,
					subSelectize;

				// Destroy the selectize instance.
				if ( undefined !== $subSelectorControl.selectize()[0] ) {
					$subSelectorControl.selectize()[0].selectize.destroy();
				}

				// Get all items in the sub-list for the active font-family.
				_.each( CBCustomizerData.allFonts, function( font, key_family ) {
					// Find the font-family we've selected in the global array of fonts.
					if ( fontFamily == font.id ) {
						// Check if this is a standard font or a google-font.
						if ( undefined !== font.isStandard && true === font.isStandard ) {
							isStandard = true;
						}

						if ( 'variant' === sub ) {
							subList = font.variants;
						} else if ( 'subsets' === sub ) {
							subList = font.subsets;
						}
					}
				} );

				_.each( subList, function( variant, index ) {
					if ( typeof variant === 'string' ) {
						subList[index] = { id: variant, label: variant };
					}
				} );

				if ( fontFamily === undefined ) {
					subList = [];
				}

				// This is a googlefont, or we're talking subsets.
				if ( false === isStandard || 'subsets' !== sub ) {
					// Determine the initial value we have to use
					if ( null === startValue ) {
						if ( 'variant' === sub ) { // The context here is variants
							// Loop the variants.
							_.each( subList, function( variant ) {
								var defaultValue;

								if ( undefined !== variant.id ) {
									activeItem = value.variant;
								} else {
									defaultValue = 'regular';

									if ( defaultValue === variant.id ) {
										hasDefault = true;
									} else if ( false === firstAvailable ) {
										firstAvailable = variant.id;
									}
								}
							} );
						} else if ( 'subsets' === sub ) { // The context here is subsets
							subsetValues = {};

							_.each( subList, function( subSet ) {
								if ( null !== value.subsets ) {
									_.each( value.subsets, function( item ) {
										if ( undefined !== subSet && item === subSet.id ) {
											subsetValues[item] = item;
										}
									} );
								}
							} );

							if ( 0 !== subsetValues.length ) {
								subsetValuesArray = jQuery.map( subsetValues, function( value, index ) {
									return [ value ];
								} );
								activeItem = subsetValuesArray;
							}
						}

						// If we have a valid setting, use it.
						// If not, check if the default value exists.
						// If not, then use the 1st available option.
						subValue = ( undefined !== activeItem ) ? activeItem : ( false !== hasDefault ) ? 'regular' : firstAvailable;
					} else {
						subValue = startValue;
					}

					// Create
					subSelectize = $subSelectorControl.selectize( {
						maxItems: ( 'variant' === sub ) ? 1 : null,
						valueField: 'id',
						labelField: 'label',
						searchField: [ 'label' ],
						options: subList,
						items: ( 'variant' === sub ) ? [ subValue ] : subValue,
						create: false,
						plugins: ( 'variant' === sub ) ? '' : [ 'remove_button' ],
						render: {
							item: function( item, escape ) {
								return '<div>' + escape( item.label ) + '</div>';
							},
							option: function( item, escape ) {
								return '<div>' + escape( item.label ) + '</div>';
							}
						}
					} ).data( 'selectize' );
				}

				if ( true === isStandard ) {
					// Hide unrelated fields on standard fonts.
					control.container.find( '.hide-on-standard-fonts' ).css( 'display', 'none' );
				} else if ( 2 > subList.length ) {
					// If only 1 option is available then there's no reason to show this.
					control.container.find( '.cb-' + sub + '-wrapper' ).css( 'display', 'none' );
				} else {
					control.container.find( '.cb-' + sub + '-wrapper' ).css( 'display', 'block' );
				}
			};

			// Render the font-family
			$fontFamilyControl.selectize( {
				options: CBCustomizerData.allFonts,
				items: [ control.setting._value['font-family'] ],
				persist: false,
				maxItems: 1,
				valueField: 'id',
				labelField: 'label',
				searchField: [ 'category', 'label', 'subsets' ],
				create: false,
				render: {
					item: function( item, escape ) {
						return '<div>' + escape( item.label ) + '</div>';
					},
					option: function( item, escape ) {
						return '<div>' + escape( item.label ) + '</div>';
					}
				}
			} );

			// Render the variants
			// Please note that when the value of font-family changes,
			// this will be destroyed and re-created.
			renderSubControl( value['font-family'], 'variant', value.variant );

			// Render the subsets
			// Please note that when the value of font-family changes,
			// this will be destroyed and re-created.
			renderSubControl( value['font-family'], 'subsets', value.subsets );

			this.container.on( 'change', '.font-family select', function() {
				if ( updating ) {
					return;
				}
				updating = true;
				// Add the value to the array and set the setting's value
				value['font-family'] = jQuery( this ).val();
				control.saveValue( value );
				updating = false;

				// Trigger changes to variants & subsets
				renderSubControl( jQuery( this ).val(), 'variant', null );
				renderSubControl( jQuery( this ).val(), 'subsets', null );

				// Refresh the preview
				wp.customize.previewer.refresh();
			} );

			this.container.on( 'change', '.variant select', function() {
				if ( updating ) {
					return;
				}
				updating = true;
				// Add the value to the array and set the setting's value
				value.variant = jQuery( this ).val();
				control.saveValue( value );
				updating = false;
				// Refresh the preview
				wp.customize.previewer.refresh();
			} );

			this.container.on( 'change', '.subsets select', function() {
				if ( updating ) {
					return;
				}
				updating = true;
				// Add the value to the array and set the setting's value.
				value.subsets = jQuery( this ).val();
				control.saveValue( value );
				updating = false;
				// Refresh the preview
				wp.customize.previewer.refresh();
			} );

			this.container.on( 'change keyup paste', '.font-size input', function() {
				if ( updating ) {
					return;
				}

				updating = true;
				var $el = $( this );
				// Add the value to the array and set the setting's value
				value['font-size'] = {
					mobile: $el.data( 'mobile' ),
					tablet: $el.data( 'tablet' ),
					desktop: $el.data( 'desktop' )
				};
				control.saveValue( value );
				updating = false;
			} );

			this.container.on( 'change keyup paste', '.line-height input', function() {
				if ( updating ) {
					return;
				}
				updating = true;
				// Add the value to the array and set the setting's value
				var $el = $( this );
				// Add the value to the array and set the setting's value
				value['line-height'] = {
					mobile: $el.data( 'mobile' ),
					tablet: $el.data( 'tablet' ),
					desktop: $el.data( 'desktop' )
				};
				control.saveValue( value );
				updating = false;
			} );

			this.container.on( 'change keyup paste', '.letter-spacing input', function() {
				if ( updating ) {
					return;
				}
				updating = true;
				// Add the value to the array and set the setting's value
				value['letter-spacing'] = jQuery( this ).val();
				control.saveValue( value );
				updating = false;
			} );

			this.container.on( 'change', '.text-align input', function() {
				if ( updating ) {
					return;
				}
				updating = true;
				// Add the value to the array and set the setting's value.
				value['text-align'] = jQuery( this ).val();
				control.saveValue( value );
				updating = false;
			} );

			// Text-transform
			$textTransformControl.selectize();
			this.container.on( 'change', '.text-transform select', function() {
				if ( updating ) {
					return;
				}
				updating = true;
				// Add the value to the array and set the setting's value.
				value['text-transform'] = jQuery( this ).val();
				control.saveValue( value );
				updating = false;
			} );

			picker = this.container.find( '.cb-color-control' );

			// Change color
			picker.wpColorPicker( {
				change: function() {
					if ( updating || ! $html.hasClass( 'cb-typography-control-ready' ) ) {
						return;
					}
					updating = true;

					// Add the value to the array and set the setting's value
					value.color = picker.val();
					control.saveValue( value );
					updating = false;
				}
			} );

			picker2 = this.container.find( '.cb-hover-color-control' );

			// Change color
			picker2.wpColorPicker( {
				change: function() {
					if ( updating || ! $html.hasClass( 'cb-typography-control-ready' ) ) {
						return;
					}
					updating = true;

					// Add the value to the array and set the setting's value
					value['hover-color'] = picker2.val();
					control.saveValue( value );
					updating = false;
				}
			} );

			// on settings change, update control to reflect the state.
			control.setting.bind( function( value ) {
				var respLineHeight,
					respFontSize,
					resetValues = false;

				// Bail if the update came from the control itself.
				if ( updating ) {
					return;
				}
				//console.log(value['font-size'], value['line-size']);

				updating = true;

				if ( $fontFamilyControl.length && value['font-family'] && value['font-family'] != $fontFamilyControl.val() ) {
					$fontFamilyControl.val( value['font-family'] );
					$fontFamilyControl[0].selectize.setValue( value['font-family'], true );
					// wp.customize.previewer.refresh();
				}

				if ( $subsetControl.length && typeof value.subsets !== 'undefined' && value.subsets != $subsetControl.val() ) {
					$subsetControl.val( value.subsets );
					$subsetControl[0].selectize.setValue( value.subsets, true );
				}

				if ( $variantControl.length && typeof value.variant !== 'undefined' && value.variant != $variantControl.val() ) {
					$variantControl.val( value.variant );
					$variantControl[0].selectize.setValue( value.variant, true );
				}

				if ( $fontSizeControl.length && value['font-size'] && ! _.isEqual( value['font-size'], getResponsiveElementVal( $fontSizeControl ) ) ) {
					setResponsiveElementValue( $fontSizeControl, value['font-size'] );
					respFontSize = prepareResponsivevalue( value['font-size'] );
					// this should be only needed if numeric value was given.
					if ( ! _.isEqual( value['font-size'], respFontSize ) ) {
						value['font-size'] = respFontSize;
						resetValues = true;
					}

					$fontSizeControl.trigger( 'change' );
				}

				if ( $lineHeightControl.length && value['line-height'] && ! _.isEqual( value['line-height'], getResponsiveElementVal( $lineHeightControl ) ) ) {
					setResponsiveElementValue( $lineHeightControl, value['line-height'] );
					respLineHeight = prepareResponsivevalue( value['line-height'] );

					if ( ! _.isEqual( value['line-height'], respLineHeight ) ) {
						value['line-height'] = respLineHeight;
						resetValues = true;
					}

					$lineHeightControl.trigger( 'change' );
				}

				if ( $textAlignControl.length && value['text-align'] && value['text-align'] != $textAlignControl.val() ) {
					$textAlignControl.val( value['text-align'] );
				}

				if ( $textTransformControl.length && value['text-transform'] && value['text-transform'] != $textTransformControl.val() ) {
					$textTransformControl.val( value['text-transform'] );
				}

				if ( $letterSpacingControl.length && value['letter-spacing'] && value['letter-spacing'] != $letterSpacingControl.val() ) {
					$letterSpacingControl.val( value['letter-spacing'] );
				}

				if ( resetValues ) {
					control.setting.set( value );
				}

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

			var control = this,
				newValue = {};

			_.each( value, function( newSubValue, i ) {
				newValue[i] = newSubValue;
			} );
			//console.log(newValue);
			control.setting.set( newValue );
		}
	} );

	function getResponsiveElementVal( $el ) {
		var data = {},
			devices = { mobile: true, tablet: true, desktop: true };
		for ( var device in devices ) {
			data[device] = $el.data( device );
		}

		return data;
	}

	function setResponsiveElementValue( $el, val ) {
		var currentDevice = wp.customize.previewedDevice.get(),
			device = '';

		currentDevice = currentDevice || 'desktop';
		val = prepareResponsivevalue( val );
		for ( device in val ) {
			$el.data( device, val[device] );
			if ( device == currentDevice ) {
				$el.val( val[device] );
				$el.trigger( 'change' );
			}
		}
	}

	/**
	 * Given a numeric value or an object, returns an object with divices as key.
	 *
	 * @param val
	 * @return {*}
	 */
	function prepareResponsivevalue( val ) {
		var data = {},
			devices = { mobile: true, tablet: true, desktop: true },
			device = '';

		if ( ! _.isObject( val ) ) {
			for ( device in devices ) {
				data[device] = val;
			}

			val = data;
		}

		return val;
	}
}( jQuery ) );
