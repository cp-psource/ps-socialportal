<?php
/**
 * BuddyPress Register page
 *
 * @package    PS_SocialPortal
 * @subpackage BuddyPress
 * @copyright  Copyright (c) 2019, DerN3rd
 * @license    https://www.gnu.org/licenses/gpl.html GNU Public License
 * @author     DerN3rd
 */

// Do not allow direct access over web.
defined( 'ABSPATH' ) || exit;
?>
<div id="buddypress">
	<?php if ( 'completed-confirmation' !== bp_get_current_signup_step() ) : ?>
		<?php bp_get_template_part( 'members/register/section-signup-intro' ); ?>
	<?php endif; ?>
	<?php

	/**
	 * Fires at the top of the BuddyPress member registration page template.
	 */
	do_action( 'bp_before_register_page' );
	?>

	<div id="register-page" class="bp-register-page">

		<form action="" name="signup_form" id="signup_form" class="standard-form register-form" method="post" enctype="multipart/form-data">

			<?php if ( 'registration-disabled' === bp_get_current_signup_step() ) : ?>
				<?php bp_get_template_part( 'members/register/section-registration-disabled' ); ?>
			<?php endif; // registration-disabled signup step. ?>

			<?php if ( 'request-details' === bp_get_current_signup_step() ) : ?>

				<?php bp_get_template_part( 'members/register/section-account' ); ?>

				<?php if ( bp_is_active( 'xprofile' ) ) : ?>
					<?php bp_get_template_part( 'members/register/section-profile' ); ?>
				<?php endif; ?>

				<?php if ( bp_get_blog_signup_allowed() ) : ?>
					<?php bp_get_template_part( 'members/register/section-blogs' ); ?>
				<?php endif; ?>

				<?php bp_get_template_part( 'members/register/section-signup-submit' ); ?>

				<?php wp_nonce_field( 'bp_new_signup' ); ?>

			<?php endif; // request-details signup step. ?>

			<?php
			/**
			 * Fires and displays any custom signup steps.
			 */
			do_action( 'bp_custom_signup_steps' );
			?>

			<?php if ( 'completed-confirmation' == bp_get_current_signup_step() ) : ?>
				<?php bp_get_template_part( 'members/register/section-signup-complete' ); ?>
			<?php endif; // completed-confirmation signup step. ?>

		</form>

	</div>

	<?php
	/**
	 * Fires at the bottom of the BuddyPress member registration page template.
	 */
	do_action( 'bp_after_register_page' );
	?>

</div><!-- #buddypress -->
