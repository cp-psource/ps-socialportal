<?php
/**
 * BuddyPress - Group Admin - Requests loop
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
<?php if ( bp_group_has_membership_requests( bp_ajax_querystring( 'membership_requests' ) ) ) : ?>

	<div id="pag-top" class="pagination">

		<div class="pag-count" id="group-mem-requests-count-top">
			<?php bp_group_requests_pagination_count(); ?>
		</div>

		<div class="pagination-links" id="group-mem-requests-pag-top">
			<?php bp_group_requests_pagination_links(); ?>
		</div>

	</div>

	<ul id="request-list" class="<?php cb_bp_item_list_class( 'row' ); ?>">

		<?php while ( bp_group_membership_requests() ) : cb_bp_group_the_membership_request(); ?>
			<?php cb_bp_get_item_entry_template( 'members/entry/member-entry' ); ?>
		<?php endwhile; ?>

	</ul>

	<div id="pag-bottom" class="pagination">

		<div class="pag-count" id="group-mem-requests-count-bottom">
			<?php bp_group_requests_pagination_count(); ?>
		</div>

		<div class="pagination-links" id="group-mem-requests-pag-bottom">
			<?php bp_group_requests_pagination_links(); ?>
		</div>

	</div>

<?php else : ?>

	<div id="message" class="info">
		<p><?php _e( 'Es gibt keine ausstehenden Mitgliedschaftsanträge.', 'social-portal' ); ?></p>
	</div>

<?php
endif;
