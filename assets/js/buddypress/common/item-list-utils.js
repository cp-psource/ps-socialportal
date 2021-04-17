( function( $ ) {
	var directoryPreferences = {};
	CB.ItemUtils = {
		/**
		 * get current scope for the object.
		 *
		 * @param object
		 * @return {*}
		 */
		getCurrentScope: function( object ) {
			return this.getObjectPreference( object, 'scope' );
		},

		/**
		 * Get current filter for the object.
		 *
		 * @param object
		 * @return {*}
		 */
		getCurrentFilter: function( object ) {
			return this.getObjectPreference( object, 'filter' );
		},

		/**
		 * Get Object preference.
		 *
		 * @param object
		 * @param pref
		 * @return {*}
		 */
		getObjectPreference: function( object, pref ) {
			// Rebuild if needed.
			if ( ! directoryPreferences.hasOwnProperty( object ) ) {
				directoryPreferences[object] = this.rebuildObjectPreferences();
			}

			if ( CBBPSettings.storeFilterSettings ) {
				directoryPreferences[object][pref] = $.cookie( 'bp-' + object + '-' + pref );
			}

			return directoryPreferences[object][pref];
		},

		/**
		 * Sets the user's current preference for a directory option.
		 *
		 * @param object
		 * @param pref
		 * @param value
		 */
		setObjectPreference: function( object, pref, value ) {
			var defaultPrefs = {
				filter: '',
				scope: '',
				extras: ''
			};

			if ( ! directoryPreferences.hasOwnProperty( object ) ) {
				var newPreferences = {};
				for ( var prefName in defaultPrefs ) {
					if ( defaultPrefs.hasOwnProperty( prefName ) ) {
						newPreferences[prefName] = defaultPrefs[prefName];
					}
				}
				directoryPreferences[object] = newPreferences;
			}

			if ( CBBPSettings.storeFilterSettings ) {
				$.cookie( 'bp-' + object + '-' + pref, value, {
					path: '/',
					secure: ( 'https:' === window.location.protocol )
				} );
			}

			directoryPreferences[object][pref] = value;
		},

		/**
		 * Rebuild and get new preferences.
		 */
		rebuildObjectPreferences: function() {
			var defaultPrefs = {
					filter: '',
					scope: '',
					extras: ''
				},
				newPreferences = {};

			for ( var prefName in defaultPrefs ) {
				if ( defaultPrefs.hasOwnProperty( prefName ) ) {
					newPreferences[prefName] = defaultPrefs[prefName];
				}
			}

			return newPreferences;
		},

		/**
		 * Get scope from element id attribute.
		 *
		 * @param id
		 * @return {string}
		 */
		getScopeFromElementID: function( id ) {
			if ( 'undefined' === typeof id ) {
				return '';
			}
			var parts = id.split( '-' );
			return parts.length > 0 ? parts[parts.length - 1] : '';
		}
	};
}( jQuery ) );
