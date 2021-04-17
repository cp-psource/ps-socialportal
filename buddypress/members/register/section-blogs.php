<?php
/**
 * BuddyPress registration section - Blog
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
 * Fires before the display of member registration blog details fields.
 */
do_action( 'bp_before_blog_details_fields' );
?>

<?php /***** Blog Creation Details ******/ ?>

<div class="register-section" id="blog-details-section">

	<h4><?php _e( 'Blog Details', 'social-portal' ); ?></h4>

	<p><label for="signup_with_blog">
            <input type="checkbox" name="signup_with_blog" id="signup_with_blog" value="1"<?php if ( (int) bp_get_signup_with_blog_value() ) : ?> checked="checked"<?php endif; ?> /> <?php _e( 'Yes, I\'d like to create a new site', 'social-portal' ); ?>
		</label></p>

	<div id="blog-details"
	     <?php if ( (int) bp_get_signup_with_blog_value() ) : ?>class="show"<?php endif; ?>>

		<label for="signup_blog_url"><?php _e( 'Blog URL', 'social-portal' ); ?><?php _e( '(erforderlich)', 'social-portal' ); ?></label>
		<?php

		/**
		 * Fires and displays any member registration blog URL errors.
		 */
		do_action( 'bp_signup_blog_url_errors' );
		?>

		<?php if ( is_subdomain_install() ) : ?>
			http:// <input type="text" name="signup_blog_url" id="signup_blog_url" value="<?php bp_signup_blog_url_value(); ?>"/> .<?php bp_signup_subdomain_base(); ?>
		<?php else : ?>
			<?php echo home_url( '/' ); ?> <input type="text" name="signup_blog_url" id="signup_blog_url" value="<?php bp_signup_blog_url_value(); ?>"/>
		<?php endif; ?>

		<label for="signup_blog_title"><?php _e( 'Seiten Titel', 'social-portal' ); ?><?php _e( '(erforderlich)', 'social-portal' ); ?></label>
		<?php

		/**
		 * Fires and displays any member registration blog title errors.
		 */
		do_action( 'bp_signup_blog_title_errors' ); ?>
		<input type="text" name="signup_blog_title" id="signup_blog_title" value="<?php bp_signup_blog_title_value(); ?>"/>

		<span class="label"><?php _e( 'Ich möchte, dass meine Webseite in Suchmaschinen und in öffentlichen Listen in diesem Netzwerk angezeigt wird.', 'social-portal' ); ?></span>
		<?php

		/**
		 * Fires and displays any member registration blog privacy errors.
		 */
		do_action( 'bp_signup_blog_privacy_errors' ); ?>

		<label for="signup_blog_privacy_public">
            <input type="radio" name="signup_blog_privacy" id="signup_blog_privacy_public" value="public"<?php if ( 'public' == bp_get_signup_blog_privacy_value() || ! bp_get_signup_blog_privacy_value() ) : ?> checked="checked"<?php endif; ?> /> <?php _e( 'Ja', 'social-portal' ); ?>
		</label>
		<label for="signup_blog_privacy_private"><input type="radio" name="signup_blog_privacy" id="signup_blog_privacy_private" value="private"<?php if ( 'private' == bp_get_signup_blog_privacy_value() ) : ?> checked="checked"<?php endif; ?> /> <?php _e( 'Nein', 'social-portal' ); ?>
		</label>

		<?php

		/**
		 * Fires and displays any extra member registration blog details fields.
		 */
		do_action( 'bp_blog_details_fields' );
		?>

	</div>

</div><!-- #blog-details-section -->

<?php

/**
 * Fires after the display of member registration blog details fields.
 */
do_action( 'bp_after_blog_details_fields' );
