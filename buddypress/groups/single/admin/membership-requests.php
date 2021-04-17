<?php
/**
 * BuddyPress - Group Admin - Manager Requests
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
 * Fires before the display of group membership requests admin.
 */
do_action( 'bp_before_group_membership_requests_admin' );
?>

<div class="requests">
	<?php bp_get_template_part( 'groups/single/admin/requests-loop' ); ?>
</div>

<?php

/**
 * Fires after the display of group membership requests admin.
 */
do_action( 'bp_after_group_membership_requests_admin' );