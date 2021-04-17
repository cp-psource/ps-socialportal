<?php
/**
 * BuddyPress - Member - Profile - View
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
<?php do_action( 'bp_before_profile_loop_content' ); ?>
<div class="bp-view-profile-container <?php cb_bp_view_profile_style();?>">
<?php if ( bp_has_profile() ) : ?>

	<?php while ( bp_profile_groups() ) : bp_the_profile_group(); ?>

		<?php if ( bp_profile_group_has_fields() ) : ?>

			<?php	do_action( 'bp_before_profile_field_content' ); ?>

			<div class="bp-widget <?php bp_the_profile_group_slug(); ?>">

				<h4><?php bp_the_profile_group_name(); ?></h4>

				<div class="profile-fields">

					<?php while ( bp_profile_fields() ) : bp_the_profile_field(); ?>

						<?php if ( bp_field_has_data() ) : ?>

							<div<?php bp_field_css_class( 'row' ); ?>>

								<div class="label col-lg-3 col-md-6 col-xs-12"><?php bp_the_profile_field_name(); ?></div>

								<div class="data col-lg-9 col-md-6 col-xs-12"><?php bp_the_profile_field_value(); ?></div>

							</div>

						<?php endif; ?>

						<?php
						/**
						 * Fires after the display of a field table row for profile data.
						 */
						do_action( 'bp_profile_field_item' );
						?>

					<?php endwhile; ?>

				</div>
			</div>

			<?php do_action( 'bp_after_profile_field_content' ); ?>

		<?php endif; ?>

	<?php endwhile; ?>

	<?php do_action( 'bp_profile_field_buttons' ); ?>

<?php endif; ?>

</div><!-- end of view-profile-container -->
<?php
do_action( 'bp_after_profile_loop_content' );
