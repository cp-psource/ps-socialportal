( function( $, api ) {
	/* === Preset Control === */
	var lastPreset = '';
	api.controlConstructor['cb-preset'] = api.Control.extend( {
		ready: function() {
			var control = this,
				refreshCallback;

			lastPreset = control.setting._value;
			// Adds a `.selected` class to the label of checked inputs.
			$( 'input:radio:checked', control.container ).parent( 'label' ).addClass( 'selected' );

			$( 'input:radio', control.container ).change( function() {
				var $selected = $( this ),
					current_preset = $selected.val();
				// update.
				control.setting.set( current_preset );
				// Now find the js object.
				var id = $selected.data( 'preset-id' );

				if ( ! id || typeof _CBCustomizeControlPreset === 'undefined' ) {
					return;
				}

				var presetObject = _CBCustomizeControlPreset[id];

				if ( ! presetObject || ! presetObject.settings ) {
					return;
				}

				// Stop Previewer refresh due to settings change.
				refreshCallback = api.previewer.refresh;
				api.previewer.refresh = function() {
				}; // Dummy, no-ops.

				var dirty = {}; // Used to calculate and restore the diff between two presets.
				_.each( presetObject.settings, function( value, key ) {
					var targetControl = api.control( key );
					if ( targetControl === undefined ) {
						return;
					}
					// if we are here, control is defined. let us update the value.
					targetControl.setting.set( value );
					dirty[key] = 1;
				} );
				// we need to reset any setting from the old preset
				// which are not updated in our current preset
				// this helps us not mixup when a person switches multiple times
				var oldPresetObject = _CBCustomizeControlPreset[lastPreset];

				if ( oldPresetObject && oldPresetObject.settings ) {
					_.each( oldPresetObject.settings, function( value, key ) {
						// was the setting already updated? If dirty, then we don't need to worry about it.
						if ( dirty[key] !== undefined ) {
							return;
						}
						// if we are here, the new preset did not update this control from oldPreset.
						var targetControl = api.control( key );
						// control does not exist, no need to process.
						if ( targetControl === undefined ) {
							return;
						}
						// if we are here, control is defined. let us update the value.
						targetControl.setting.set( targetControl.params.value );
					} );
				}

				// save current as old preset.
				lastPreset = id;

				// Restore previewer refresh.
				api.previewer.refresh = refreshCallback;

				// Free memory.
				refreshCallback = null;
				dirty = null;

				// Reload previewer to reflect the change.
				api.previewer.refresh();
			}
			);
		}
	} );
}( jQuery, wp.customize ) );
