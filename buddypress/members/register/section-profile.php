<?php
/**
 * BuddyPress registration section - Profile
 *
 * @package    PS_SocialPortal
 * @subpackage BuddyPress
 * @copyright  Copyright (c) 2019, DerN3rd
 * @license    https://www.gnu.org/licenses/gpl.html GNU Public License
 * @author     DerN3rd
 */

// Do not allow direct access over web.
defined( 'ABSPATH' ) || exit;

/**
 * Fires before the display of member registration xprofile fields.
 */
do_action( 'bp_before_signup_profile_fields' );
?>

<div class="register-section register-section-profile" id="profile-details-section">

	<h4><?php _e( 'Profile Details', 'social-portal' ); ?></h4>
	<?php $fids = array(); ?>
	<?php /* Use the profile field loop to render input fields for the 'base' profile field group */ ?>
	<?php
	if ( bp_has_profile(
		array(
			'profile_group_id'  => cb_bp_get_registration_groups(),
			'fetch_field_data'  => false,
			'hide_empty_groups' => false,
		)
	) ) :
		while ( bp_profile_groups() ) :
			bp_the_profile_group();
			?>

			<?php
			while ( bp_profile_fields() ) :
				bp_the_profile_field();
				?>

				<div <?php bp_field_css_class( 'editfield' ); ?>>

					<?php
					$field_type = bp_xprofile_create_field_type( bp_get_the_profile_field_type() );
					$field_type->edit_field_html();

					/**
					 * Fires before the display of the visibility options for xprofile fields.
					 */
					do_action( 'bp_custom_profile_edit_fields_pre_visibility' );

					if ( bp_current_user_can( 'bp_xprofile_change_field_visibility' ) ) :
						?>
						<p class="field-visibility-settings-toggle" id="field-visibility-settings-toggle-<?php bp_the_profile_field_id() ?>">
							<?php

							printf(
							    /* translators: %s: field visibility */
								__( 'Dieses Feld kann gesehen werden von: %s', 'social-portal' ),
								'<span class="current-visibility-level">' . bp_get_the_profile_field_visibility_level_label() . '</span>'
							);
							?>
							<a href="#" class="visibility-toggle-link" title="<?php _e( 'Schließen', 'social-portal' ) ?>"><i class="fa fa-cog"></i>
							</a>
						</p>

						<div class="field-visibility-settings" id="field-visibility-settings-<?php bp_the_profile_field_id() ?>">

							<fieldset>

								<legend><?php _e( 'Wer kann dieses Feld sehen?', 'social-portal' ) ?>
									<a class="field-visibility-settings-close" href="#" title="<?php _e( 'Schließen', 'social-portal' ) ?>"><i class="fa fa-times-circle"></i></a></legend>
								<?php bp_profile_visibility_radio_buttons() ?>

							</fieldset>

						</div>
					<?php else : ?>
						<p class="field-visibility-settings-notoggle" id="field-visibility-settings-toggle-<?php bp_the_profile_field_id() ?>">
							<?php
							/* translators: %s: field visibility */
							printf(
								__( 'Dieses Feld kann gesehen werden von: %s', 'social-portal' ),
								'<span class="current-visibility-level">' . bp_get_the_profile_field_visibility_level_label() . '</span>'
							);
							?>
						</p>
					<?php endif ?>

					<?php

					/**
					 * Fires after the display of the visibility options for xprofile fields.
					 */
					do_action( 'bp_custom_profile_edit_fields' );
					?>

				</div>

			<?php endwhile; ?>

			<?php $fids[] = bp_get_the_profile_field_ids(); ?>

		<?php
		endwhile;
	endif;
	?>

	<input type="hidden" name="signup_profile_field_ids" id="signup_profile_field_ids" value="<?php echo join( ',', $fids ); ?>"/>

	<?php

	/**
	 * Fires and displays any extra member registration xprofile fields.
	 */
	do_action( 'bp_signup_profile_fields' );
	?>

</div><!-- #profile-details-section -->

<?php

/**
 * Fires after the display of member registration xprofile fields.
 */
do_action( 'bp_after_signup_profile_fields' );
