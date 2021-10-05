( function( $ ) {
	$( document ).ready( function() {
		var transitionSpeed = 500;
		// toggle visibility
		// Current visibility or the visibility config icon clicked.
		$( document ).on( 'click', '.visibility-toggle-link, .current-visibility-level', function( event ) {
			event.preventDefault();
			var toggle = $( this ),
				editField = toggle.parents( '.editfield' ),
				toggleTitle = editField.find( '.field-visibility-settings-toggle' ),
				visibilityOptions = editField.find( '.field-visibility-settings' );

			if ( toggleTitle.hasClass( 'field-visibility-settings-notoggle' ).length ) {
				return;
			}

			toggle.attr( 'aria-expanded', 'true' );
			toggleTitle.hide( transitionSpeed ).addClass( 'field-visibility-settings-hide' );
			visibilityOptions.slideDown( transitionSpeed ).addClass( 'field-visibility-settings-open' );
		} );

		$( document ).on( 'click', '.field-visibility-settings-close', function( event ) {
			var button = $( this ),
				editField = button.parents( '.editfield' ),
				toggleTitle = editField.find( '.field-visibility-settings-toggle' ),
				visibilityOptions = editField.find( '.field-visibility-settings' ),
				visibilitySettingText = visibilityOptions.find( 'input:checked' ).parent().text();
			toggleTitle.find( '.visibility-toggle-link, .current-visibility-level' ).attr( 'aria-expanded', 'false' );
			event.preventDefault();

			visibilityOptions.slideUp( transitionSpeed ).removeClass( 'field-visibility-settings-open' );
			toggleTitle.find( '.current-visibility-level' ).text( visibilitySettingText ).end()
				.show( transitionSpeed ).removeClass( 'field-visibility-settings-hide' );
		} );

		$( document ).on( 'click', '.field-visibility-settings .radio input', function() {
			var radio = $( this ),
				editField = radio.parents( '.editfield' ),
				toggleTitle = editField.find( '.field-visibility-settings-toggle' ),
				visibilityOptions = editField.find( '.field-visibility-settings' ),
				visibilitySettingsText = visibilityOptions.find( 'input:checked' ).parent().text();

			toggleTitle.find( '.visibility-toggle-link, .current-visibility-level' ).attr( 'aria-expanded', 'false' );

			visibilityOptions.slideUp( transitionSpeed ).removeClass( 'field-visibility-settings-open' );

			toggleTitle.find( '.current-visibility-level' ).text( visibilitySettingsText ).end()
				.show( transitionSpeed ).removeClass( 'field-visibility-settings-hide' );
		} );

		// warn for unsaved changes.
		$( '#profile-edit-form input:not(:submit), #profile-edit-form textarea, #profile-edit-form select, #signup_form input:not(:submit), #signup_form textarea, #signup_form select' ).change( function() {
			var shouldconfirm = true;

			$( '#profile-edit-form input:submit, #signup_form input:submit' ).on( 'click', function() {
				shouldconfirm = false;
			} );

			window.onbeforeunload = function( e ) {
				if ( shouldconfirm ) {
					return CPSFSettings.unsaved_changes;
				}
			};
		} );
	} );
}( jQuery ) );
