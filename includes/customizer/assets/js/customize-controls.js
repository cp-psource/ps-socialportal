( function( $, api ) {
	/*api.section('cb_styling-site-wp-login', function (section) {
        var lastPreviewUrl = '';
        section.expanded.bind(function (isExpanding) {

            // Value of isExpanding will = true if you're entering the section, false if you're leaving it.
            if (isExpanding) {
                lastPreviewUrl = api.previewer.previewUrl();
                api.previewer.previewUrl.set(CBCustomizerData.loginURL);
            } else if (lastPreviewUrl) {
                api.previewer.previewUrl.set(lastPreviewUrl);
                lastPreviewUrl = '';
            }

        })
    });*/

	/* === Checkbox Multiple Control === */
	api.controlConstructor['cb-checkbox-multiple'] = api.Control.extend( {
		ready: function() {
			var control = this;

			$( 'input:checkbox', control.container ).change(
				function() {
					// Get all of the checkbox values.
					var checkedValues = $( 'input[type="checkbox"]:checked', control.container ).map(
						function() {
							return this.value;
						}
					).get();

					// Set the value.
					if ( null === checkedValues ) {
						control.setting.set( '' );
					} else {
						control.setting.set( checkedValues );
					}
				}
			);
		}
	} );

	api.controlConstructor['cb-background-position'] = api.Control.extend( {
		ready: function() {
			var control = this,
				container = control.container,
				updating = false,
				label = '',
				input = container.find( '.cb-control-background-position' );
			var $positionButton = input.find( 'label' ),
				$caption = container.find( '.background-position-caption' );

			input.buttonset( {
				create: function( event ) {
					$positionButton.on( 'click', function( evt ) {
						var $selected = $( this ).prev( 'input[type="radio"][name="_customize-radio-header-background-position"]' );
						label = $( this ).data( 'label' );
						$caption.text( label );

						$selected.prop( 'checked', true );
						if ( ! updating ) {
							updating = true;
							control.setting.set( $selected.val() );
							updating = false;
						}
						return false;
					} );
				}
			} );

			control.setting.bind( function( value ) {
				if ( updating ) {
					return;
				}

				updating = true;
				input.find( 'input[type="radio"]' ).each( function() {
					var $this = $( this );
					if ( $this.val() == value ) {
						$this.prop( 'checked', true );
						label = $this.next( 'label' ).data( 'label' );
						$caption.text( label );
					}
				} );
				input.buttonset( 'refresh' );
				updating = false;
			} );
		}
	} );

	api.controlConstructor['cb-radio'] = api.Control.extend( {
		ready: function() {
			var control = this,
				container = control.container,
				updating = false,
				input = container.find( '.cb-control-buttonset' ),
				$positionButton = input.find( 'label' );

			input.buttonset( {
				create: function( event ) {
					$positionButton.on( 'click', function( evt ) {
						var $selected = $( this ).prev( 'input[type="radio"]' );

						$selected.prop( 'checked', true );
						if ( ! updating ) {
							updating = true;
							control.setting.set( $selected.val() );
							updating = false;
						}
						return false;
					} );
				}
			} );

			control.setting.bind( function( value ) {
				if ( updating ) {
					return;
				}

				updating = true;
				input.find( 'input[type="radio"]' ).each( function() {
					var $this = $( this );
					if ( $this.val() == value ) {
						$this.prop( 'checked', true );
					}
				} );
				input.buttonset( 'refresh' );
				updating = false;
			} );
		}
	} );

	/* === Palette Control === */
	api.controlConstructor['cb-palette'] = api.Control.extend( {
		ready: function() {
			var control = this;

			// Adds a `.selected` class to the label of checked inputs.
			$( 'input:radio:checked', control.container ).parent( 'label' ).addClass( 'selected' );

			$( 'input:radio', control.container ).change(
				function() {
					control.setting.set( $( this ).val() );
				}
			);
		}
	} );

	/* === Radio Image Control === */
	api.controlConstructor['cb-radio-image'] = api.Control.extend( {
		ready: function() {
			var control = this;

			$( 'input:radio', control.container ).change(
				function() {
					control.setting.set( $( this ).val() );
				}
			);
		}
	} );

	/* === Select Group Control === */
	api.controlConstructor['cb-select-group'] = api.Control.extend( {
		ready: function() {
			var control = this;

			$( 'select', control.container ).change(
				function() {
					control.setting.set( $( this ).val() );
				}
			);
		}
	} );

	/*== Multi Select Control ==*/
	api.controlConstructor['cb-select-multiple'] = api.Control.extend( {
		ready: function() {
			var control = this;

			$( 'select', control.container ).change(
				function() {
					var value = $( this ).val();

					if ( null === value ) {
						control.setting.set( '' );
					} else {
						control.setting.set( value );
					}
				}
			);
		}
	} );

	var customControls = {
		cache: {},

		init: function() {
			// Populate cache.
			//this.cache.$buttonset = $('.cb-control-buttonset, .cb-control-image');
			this.cache.$range = $( '.cb-control-range' );

			// Initialize ranges.
			if ( this.cache.$range.length > 0 ) {
				this.range();
			}
		},

		//
		range: function() {
			this.cache.$range.each( function() {
				var $input = $( this ),
					updating = false,
					$slider = $input.parent().find( '.cb-range-slider' ),
					value = parseFloat( $input.val() ),
					min = parseFloat( $input.attr( 'min' ) ),
					max = parseFloat( $input.attr( 'max' ) ),
					step = parseFloat( $input.attr( 'step' ) );
				$slider.slider( {
					value: value,
					min: min,
					max: max,
					step: step,
					slide: function( e, ui ) {
                        var device;
						if ( $input.hasClass( 'cb-responsive-control' ) ) {
							 device = api.previewedDevice.get();
							device = device || 'desktop';
							$input.data( device, ui.value );
						}
						$input.val( ui.value ).keyup().trigger( 'change' );
					}
				} );

				if ( ! updating ) {
					updating = true;
					$input.val( $slider.slider( 'value' ) );
					updating = false;
				}

				$input.bind( 'change', function() {
					if ( updating ) {
						return;
					}
					updating = true;
					$slider.slider( 'value', $( this ).val() );
					updating = false;
				} );
			} );
		},

		reInitRange: function( device ) {
			device = device || 'desktop';

			this.cache.$range.each( function() {
				var $input = $( this );
				if ( ! $input.hasClass( 'cb-responsive-control' ) ) {
					return;
				}

				var $slider = $input.parent().find( '.cb-range-slider' );

				$slider.slider( 'value', parseFloat( $input.data( device ) ) );
				$input.val( parseFloat( $input.data( device ) ) );
			} );
		}
	};

	// Initialize fonts.
	$( document ).ready( function() {
		// fontChoices.init();
		customControls.init();
		// On device change, re initialize.
		api.previewedDevice.bind( function( val ) {
			customControls.reInitRange( val );
		} );

		// handle device switch control.
		$( document ).on( 'click', '.customize-control .devices button', function() {
			var device = $( this ).data( 'device' );
			api.previewedDevice.set( device );
		} );

		// add custom buttons.
		addCustomizeCustomActions();
	} );

	api.bind( 'ready', function() {
		api.previewedDevice.bind( function( val ) {
			updateDeviceActiveState( val );
		} );
	} );

	/**
	 * Add custom actions in the customizer panel header.
	 */
	function addCustomizeCustomActions() {
		var $container, $button, link;
		// Add Community+ Documentation Links
		if ( 'undefined' !== typeof CBCustomizerData ) {
			link = $( '<a class="cb-customize-doc"></a>' )
				.attr( 'href', CBCustomizerData.docURL )
				.attr( 'target', '_blank' )
				.text( CBCustomizerData.docLabel );
			$( '.preview-notice' ).append( link );
			// Remove accordion click event
			$( '.cb-customize-doc' ).on( 'click', function( e ) {
				e.stopPropagation();
			} );
		}

		// Settings Reset.
		$container = $( '#customize-header-actions' );
		$button = $( '<input type="submit" name="zoom-reset" id="zoom-reset" class="button-secondary button">' )
			.attr( 'value', CBCustomizerData.reset )
			.css( {
				float: 'right',
				'margin-right': '10px',
				'margin-top': '9px'
			} );

		$button.on( 'click', function( event ) {
		    var data;
			event.preventDefault();
			// ask confirmation.
			if ( ! confirm( CBCustomizerData.confirm ) ) {
				return;
			}

			data = {
				wp_customize: 'on',
				action: 'customizer_reset',
				nonce: CBCustomizerData.nonce.reset
			};

			$button.attr( 'disabled', 'disabled' );

			$.post( CBCustomizerData.ajaxURL, data, function() {
				wp.customize.state( 'saved' ).set( true );
				window.location.href = CBCustomizerData.customizeURL;
			} );
		} );

		$container.append( $button );
	}

	// update device active state for the responsive controls, device icons(buttons).
	// It marks current device selected.
	function updateDeviceActiveState( device ) {
		$( '.devices button' ).removeClass( 'active' ).filter( '.preview-' + device ).addClass( 'active' );
	}
}( jQuery, wp.customize ) );
