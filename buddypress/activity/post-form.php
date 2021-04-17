<?php
/**
 * BuddyPress Activity Post form.
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
<form id="whats-new-form" class="clearfix standard-form bp-activity-post-form" enctype="multipart/form-data" role="complementary" action="<?php bp_activity_post_form_action(); ?>" method="post"  >

	<?php
	/**
	 * Fires before the activity post form.
	 */
	do_action( 'bp_before_activity_post_form' );
	?>
	<!-- user avatar -->
	<div id="whats-new-avatar" class="bp-activity-post-user-avatar">
		<a href="<?php echo bp_loggedin_user_domain(); ?>">
			<?php
			bp_loggedin_user_avatar(
				bp_parse_args(
					array(
						'width'  => bp_core_avatar_thumb_width(),
						'height' => bp_core_avatar_thumb_height(),
					),
					'activity_post_user_avatar'
				)
			);
			?>

			<span class="bp-activity-post-user-name">
				<?php echo bp_get_user_firstname( bp_get_loggedin_user_fullname() ); ?>
			</span>
		</a>
	</div>

	<?php
	if ( bp_is_group() ) {
		/* translators: %1$s Group name, %2$s: User name */
		$greeting = sprintf( __( 'Was gibts neues in %1$s, %2$s?', 'social-portal' ), bp_get_group_name(), bp_get_user_firstname( bp_get_loggedin_user_fullname() ) );
	} else {
		/* translators: %s: User name */
		$greeting = sprintf( __( "Was gibts neues, %s?", 'social-portal' ), bp_get_user_firstname( bp_get_loggedin_user_fullname() ) );
	}
	$greeting = apply_filters( 'bp_activity_post_form_content_placeholder', $greeting );
	?>

	<?php
	/**
	 * Fires before the activity post form contents.
	 */
	do_action( 'bp_before_activity_post_contents' );
	?>
	<div id="whats-new-content" class="bp-activity-post-contents clearfix">

		<div id="whats-new-textarea" class="bp-activity-post-editor-container" >
			<div class="bp-suggestions bp-activity-post-editor" name="whats-new" id="whats-new" contenteditable="true" placeholder="<?php echo esc_attr( $greeting ); ?>"
					  <?php if ( bp_is_group() ) : ?>data-suggestions-group-id="<?php echo esc_attr( (int) bp_get_current_group_id() ); ?>" <?php endif; ?>
			><?php if ( isset( $_GET['r'] ) ) : ?>@<?php echo esc_textarea( $_GET['r'] ); ?><?php endif; ?></div>
		</div><!--end of post editable container -->
	</div>

	<div class="bp-activity-post-media">
		<?php do_action( 'bp_activity_post_media' ); ?>
	</div>
	<?php
	/**
	 * Fires after the activity post form contents.
	 */
	do_action( 'bp_after_activity_post_contents' );
	?>

	<div class="bp-post-options bp-activity-post-options" id="bp-activity-post-options">
		<?php
		/**
		 * Fires before the activity post form contents.
		 */
		do_action( 'bp_activity_post_options' );
		?>
		<div class="bp-activity-post-core-options" id="whats-new-options">
			<?php
            /**
			 * Fires at the end of the activity post form markup.
			 */
			do_action( 'bp_activity_post_form_options' );
			?>
			<?php if ( bp_is_active( 'groups' ) && ! bp_is_my_profile() && ! bp_is_group() ) : ?>

				<div class="bp-activity-post-in-options" id="whats-new-post-in-box">

					<label class="select-hide">
					   <span class="bp-activity-post-in-label">
						<?php _e( 'Poste in', 'social-portal' ); ?>:
					   </span>

						<select name="whats-new-post-in" class="bp-activity-post-object-id">
							<option selected="selected" value="0"><?php _e( 'Meinem Profil', 'social-portal' ); ?></option>

							<?php if ( bp_has_groups( 'user_id=' . bp_loggedin_user_id() . '&type=alphabetical&max=100&per_page=100&populate_extras=0&update_meta_cache=0' ) ) : ?>
								<?php while ( bp_groups() ) : bp_the_group(); ?>
									<option value="<?php bp_group_id(); ?>"><?php bp_group_name(); ?></option>

								<?php endwhile; ?>
							<?php endif; ?>
						</select>
					</label>

				</div>
				<input type="hidden" name="whats-new-post-object" class="bp-activity-post-object" value="groups"/>

			<?php elseif ( bp_is_group_home() ) : ?>

				<input type="hidden" class="bp-activity-post-object" name="whats-new-post-object" value="groups"/>
				<input type="hidden" class="bp-activity-post-object-id" name="whats-new-post-in" value="<?php bp_group_id(); ?>"/>

			<?php endif; ?>
			<?php
			/**
			 * Fires at the end of the activity post option markup.
			 */
			do_action( 'bp_after_activity_post_form_options' );
			?>
		</div><!-- .bp-activity-post-core-options -->

	</div><!-- .bp-activity-post-options -->


	<?php wp_nonce_field( 'post_update', '_wpnonce_post_update' ); ?>
	<?php
	/**
	 * Fires at the end of the activity post option markup.
	 */
	do_action( 'bp_before_activity_post_form_actions' );
	?>
	<div class="bp-activity-post-form-actions">
		<?php do_action( 'cb_before_activity_post_form_actions_content' ); ?>
		<div class="whats-new-cancel">
			<input type="button" name="whats-new-cancel" class="bp-activity-post-cancel" id="aw-whats-new-cancel" value="<?php echo esc_attr( __( 'Abbrechen', 'social-portal' ) ); ?>" >
		</div>
		<div id="whats-new-submit">
			<input type="submit" name="whats-new-submit" class="bp-activity-post-submit" id="aw-whats-new-submit" value="<?php esc_attr_e( 'Update veröffentlichen', 'social-portal' ); ?>"/>
		</div>
		<?php do_action( 'cb_after_activity_post_form_actions_content' ); ?>
	</div>

	<?php

	/**
	 * Fires after the activity post form.
	 */
	do_action( 'bp_after_activity_post_form' );
	?>
</form><!-- end .bp-activity-post-form -->
