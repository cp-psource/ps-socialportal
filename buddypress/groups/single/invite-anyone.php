<?php
/**
 * Template used when Invite Anyone Is enabled
 *
 * @package Invite Anyone
 * @since 0.8.5
 */
?>

<?php do_action( 'bp_before_group_send_invites_content' ) ?>

<?php if ( invite_anyone_access_test() && ! bp_is_group_create() ) : ?>
	<p><?php _e( 'Möchtest Du jemanden zu der Gruppe einladen, der noch kein Mitglied der Webseite ist?', 'social-portal' ) ?>
        <a href="<?php echo bp_loggedin_user_domain() . BP_INVITE_ANYONE_SLUG . '/invite-new-members/group-invites/' . bp_get_group_id() ?>"><?php _e( 'Sende Einladungen per E-Mail.', 'social-portal' ) ?></a>
	</p>
<?php endif; ?>

<?php if ( ! bp_get_new_group_id() ) : ?>
	<form action="<?php invite_anyone_group_invite_form_action() ?>" method="post" id="send-invite-form">
<?php endif; ?>

	<div class="left-menu">
		<p><?php _e( "Suche nach Mitgliedern, die eingeladen werden sollen:", 'social-portal' ) ?></p>

		<ul class="first acfb-holder">
			<li>
				<input type="text" name="send-to-input" class="send-to-input" id="send-to-input"/>
			</li>
		</ul>

		<?php wp_nonce_field( 'groups_invite_uninvite_user', '_wpnonce_invite_uninvite_user' ) ?>

		<?php if ( ! invite_anyone_is_large_network( 'users' ) ) : ?>
			<p><?php _e( 'Wähle Mitglieder aus dem Verzeichnis aus:', 'social-portal' ) ?></p>

			<div id="invite-anyone-member-list">
				<ul>
					<?php bp_new_group_invite_member_list() ?>
				</ul>
			</div>
		<?php endif ?>
	</div>

	<div class="main-column">

		<div id="message" class="info">
			<p><?php _e( 'Wähle Personen aus Deiner Freundesliste aus, die Du einladen möchtest.', 'social-portal' ); ?></p>
		</div>

		<?php do_action( 'bp_before_group_send_invites_list' ) ?>

		<?php /* The ID 'friend-list' is important for AJAX support. */ ?>
		<ul id="invite-anyone-invite-list"
			class="<?php cb_bp_item_list_class( 'group-invites-list row' ); ?>">
			<?php if ( bp_group_has_invites() ) : ?>

				<?php while ( bp_group_invites() ) : bp_group_the_invite(); ?>
					<?php cb_bp_get_item_entry_template( 'groups/single/invites/invite-entry' ); ?>
				<?php endwhile; ?>

			<?php endif; ?>
		</ul>

		<?php do_action( 'bp_after_group_send_invites_list' ); ?>

	</div>

	<div class="clear"></div>

<?php if ( ! bp_get_new_group_id() ) : ?>
	<div class="submit">
		<input type="submit" name="submit" id="submit" value="<?php _e( 'Sende Einladungen', 'social-portal' ); ?>"/>
	</div>
<?php endif; ?>

<?php wp_nonce_field( 'groups_send_invites', '_wpnonce_send_invites' ); ?>

	<!-- Don't leave out this sweet field -->
<?php
if ( ! bp_get_new_group_id() ) {
	?><input type="hidden" name="group_id" id="group_id" value="<?php bp_group_id() ?>" /><?php
} else {
	?><input type="hidden" name="group_id" id="group_id" value="<?php bp_new_group_id() ?>" /><?php
}
?>

<?php if ( ! bp_get_new_group_id() ) : ?>
	</form>
<?php endif; ?>

<?php
do_action( 'bp_after_group_send_invites_content' );
