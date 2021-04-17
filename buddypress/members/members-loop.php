<?php
/**
 * BuddyPress - Members Loop
 *
 * Querystring is set via AJAX in _inc/ajax.php - bp_legacy_theme_object_filter()
 *
 * @package    PS_SocialPortal
 * @subpackage Core
 * @copyright  Copyright (c) 2019, DerN3rd
 * @license    https://www.gnu.org/licenses/gpl.html GNU Public License
 * @author     DerN3rd
 */

// Do not allow direct access over web.
defined( 'ABSPATH' ) || exit;

/**
 * Fires before the display of the members loop.
 */
do_action( 'bp_before_members_loop' );
?>

<?php if ( bp_get_current_member_type() ) : ?>
	<p class="current-member-type"><?php bp_current_member_type_message() ?></p>
<?php endif; ?>

<?php if ( bp_has_members( bp_ajax_querystring( 'members' ) ) ) : ?>
	<?php cb_bp_get_template_part( 'members/parts/members-pagination', 'top', 'members-loop', 'members' ); ?>
	<?php
	/**
	 * Fires before the display of the members list.
	 */
	do_action( 'bp_before_directory_members_list' );


	cb_bp_get_template_part( 'members/members-list', '', 'members-loop', 'members' );

	/**
	 * Fires after the display of the members list.
	 */
	do_action( 'bp_after_directory_members_list' );
	?>
	<?php cb_bp_get_template_part( 'members/parts/members-pagination', 'bottom', 'members-loop', 'members' ); ?>

	<?php bp_member_hidden_fields(); ?>

<?php else : ?>
	<?php bp_get_template_part( 'members/parts/members-not-found' ); ?>
<?php endif; ?>

<?php

/**
 * Fires after the display of the members loop.
 */
do_action( 'bp_after_members_loop' );
