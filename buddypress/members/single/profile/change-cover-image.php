<?php
/**
 * BuddyPress - Member - Profile - Change Cover image
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
<h4><?php _e( 'Titelbild ändern', 'social-portal' ); ?></h4>
<div class="bp-change-profile-cover rounded-box">
	<?php

	/**
	 * Fires before the display of profile cover image upload content.
	 */
	do_action( 'bp_before_profile_edit_cover_image' );
	?>

    <p><?php _e( 'Dein Titelbild wird verwendet, um die Kopfzeile Deines Profils anzupassen.', 'social-portal' ); ?></p>

	<?php bp_attachments_get_template_part( 'cover-images/index' ); ?>

	<?php

	/**
	 * Fires after the display of profile cover image upload content.
	 */
	do_action( 'bp_after_profile_edit_cover_image' );
	?>
</div>
