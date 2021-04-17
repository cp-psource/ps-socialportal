<?php
/**
 * BuddyPress - Member - Profile - View(when xprofile is disabled)
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
 * Fires before the display of member profile loop content.
 */
do_action( 'bp_before_profile_loop_content' );

$ud = get_userdata( bp_displayed_user_id() );

/**
 * Fires before the display of member profile field content.
 */
do_action( 'bp_before_profile_field_content' );
?>

	<div class="bp-widget wp-profile">
		<h4><?php
			/* translators: %s: Displayed user name */
            bp_is_my_profile() ? _e( 'Mein Profil', 'social-portal' ) : printf( __( "%s's Profil", 'social-portal' ), bp_get_displayed_user_fullname() ); ?></h4>

		<table class="wp-profile-fields">

			<?php if ( $ud->display_name ) : ?>

				<tr id="wp_displayname">
					<td class="label"><?php _e( 'Name', 'social-portal' ); ?></td>
					<td class="data"><?php echo $ud->display_name; ?></td>
				</tr>

			<?php endif; ?>

			<?php if ( $ud->user_description ) : ?>

				<tr id="wp_desc">
					<td class="label"><?php _e( 'Über mich', 'social-portal' ); ?></td>
					<td class="data"><?php echo $ud->user_description; ?></td>
				</tr>

			<?php endif; ?>

			<?php if ( $ud->user_url ) : ?>

				<tr id="wp_website">
					<td class="label"><?php _e( 'Webseite', 'social-portal' ); ?></td>
					<td class="data"><?php echo make_clickable( $ud->user_url ); ?></td>
				</tr>

			<?php endif; ?>

			<?php if ( $ud->jabber ) : ?>

				<tr id="wp_jabber">
					<td class="label"><?php _e( 'Jabber', 'social-portal' ); ?></td>
					<td class="data"><?php echo $ud->jabber; ?></td>
				</tr>

			<?php endif; ?>

			<?php if ( $ud->aim ) : ?>

				<tr id="wp_aim">
					<td class="label"><?php _e( 'AOL Messenger', 'social-portal' ); ?></td>
					<td class="data"><?php echo $ud->aim; ?></td>
				</tr>

			<?php endif; ?>

			<?php if ( $ud->yim ) : ?>

				<tr id="wp_yim">
					<td class="label"><?php _e( 'Yahoo Messenger', 'social-portal' ); ?></td>
					<td class="data"><?php echo $ud->yim; ?></td>
				</tr>

			<?php endif; ?>

		</table>
	</div>

<?php

/**
 * Fires after the display of member profile field content.
 */
do_action( 'bp_after_profile_field_content' );

/**
 * Fires and displays the profile field buttons.
 */
do_action( 'bp_profile_field_buttons' );

/**
 * Fires after the display of member profile loop content.
 */
do_action( 'bp_after_profile_loop_content' );
