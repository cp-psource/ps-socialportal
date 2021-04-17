<?php
/**
 * BuddyPress - Group - Members
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
<?php bp_get_template_part( 'groups/single/members/nav' ); ?>
<h2 class="bp-screen-reader-text"><?php
	/* translators: accessibility text */
	_e( 'Mitglieder', 'social-portal' );
	?></h2>

<div id="members-group-list" class="item-list-container members-list-container group-members-list-container dir-list group_members" data-object="members" data-context="groups">

	<?php bp_get_template_part( 'groups/single/members/members-loop' ); ?>

</div>
