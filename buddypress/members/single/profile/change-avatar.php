<?php
/**
 * BuddyPress - Member - Profile - Change Avatar
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
<h4><?php _e( 'Profilfoto ändern', 'social-portal' ); ?></h4>
<div class="bp-change-profile-photo rounded-box">
	<?php
	/**
	 * Fires before the display of profile avatar upload content.
	 */
	do_action( 'bp_before_profile_avatar_upload_content' );
	?>

	<?php if ( ! (int) bp_get_option( 'bp-disable-avatar-uploads' ) ) : ?>

		<p><?php _e( 'Dein Profilfoto wird in Deinem Profil und auf der gesamten Webseite verwendet. Wenn mit Deiner Konto-E-Mail ein <a href="http://gravatar.com">Gravatar</a> verknüpft ist, verwenden wir diesen oder Du kannst ein Bild von Deinem Computer hochladen.', 'social-portal' ); ?></p>

		<form action="" method="post" id="avatar-upload-form" class="standard-form" enctype="multipart/form-data">

			<?php if ( 'upload-image' == bp_get_avatar_admin_step() ) : ?>

				<?php wp_nonce_field( 'bp_avatar_upload' ); ?>

				<p><?php _e( 'Klicke unten, um ein Foto im JPG-, GIF- oder PNG-Format von Deinem Computer auszuwählen, und klicke dann auf "Bild hochladen", um fortzufahren.', 'social-portal' ); ?></p>

				<p id="avatar-upload">
					<input type="file" name="file" id="file"/>
					<input type="submit" name="upload" id="upload" value="<?php esc_attr_e( 'Upload Image', 'social-portal' ); ?>"/>
					<input type="hidden" name="action" id="action" value="bp_avatar_upload"/>
				</p>

				<?php if ( bp_get_user_has_avatar() ) : ?>
					<p><?php _e( "Wenn Du Dein aktuelles Profilfoto löschen möchtest, aber kein neues hochladen möchtest, klicke auf die Schaltfläche Profilfoto löschen.", 'social-portal' ); ?></p>
					<p><a class="button edit" href="<?php bp_avatar_delete_link(); ?>" title="<?php esc_attr_e( 'Profilfoto löschen', 'social-portal' ); ?>"><?php _e( 'Mein Profilfoto löschen', 'social-portal' ); ?></a>
					</p>
				<?php endif; ?>

			<?php endif; ?>

			<?php if ( 'crop-image' == bp_get_avatar_admin_step() ) : ?>

				<h5><?php _e( 'Beschneide Dein neues Profilfoto', 'social-portal' ); ?></h5>

				<img src="<?php bp_avatar_to_crop(); ?>" id="avatar-to-crop" class="avatar" alt="<?php esc_attr_e( 'Profilfoto zum Zuschneiden', 'social-portal' ); ?>"/>

				<div id="avatar-crop-pane">
					<img src="<?php bp_avatar_to_crop(); ?>" id="avatar-crop-preview" class="avatar" alt="<?php esc_attr_e( 'Profilfoto Vorschau', 'social-portal' ); ?>"/>
				</div>

				<input type="submit" name="avatar-crop-submit" id="avatar-crop-submit" value="<?php esc_attr_e( 'Bild zuschneiden', 'social-portal' ); ?>"/>

				<input type="hidden" name="image_src" id="image_src" value="<?php bp_avatar_to_crop_src(); ?>"/>
				<input type="hidden" id="x" name="x"/>
				<input type="hidden" id="y" name="y"/>
				<input type="hidden" id="w" name="w"/>
				<input type="hidden" id="h" name="h"/>

				<?php wp_nonce_field( 'bp_avatar_cropstore' ); ?>

			<?php endif; ?>

		</form>

		<?php
		/**
		 * Load the Avatar UI templates
		 */
		bp_avatar_get_templates();
		?>

	<?php else : ?>
		<p><?php _e( 'Dein Profilfoto wird in Deinem Profil und auf der gesamten Webseite verwendet. Um Dein Profilfoto zu ändern, erstelle bitte ein Konto bei <a href="http://gravatar.com">Gravatar</a> mit derselben E-Mail-Adresse, mit der Du Dich auf dieser Webseite registriert hast.', 'social-portal' ); ?></p>
	<?php endif; ?>

	<?php

	/**
	 * Fires after the display of profile avatar upload content.
	 */
	do_action( 'bp_after_profile_avatar_upload_content' );
	?>
</div>
