<?php
/**
 * BuddyPress - Member - Settings
 *
 * @package    PS_SocialPortal
 * @subpackage BuddyPress
 * @copyright  Copyright (c) 2019, DerN3rd
 * @license    https://www.gnu.org/licenses/gpl.html GNU Public License
 * @author     DerN3rd
 */

// Do not allow direct access over web.
defined( 'ABSPATH' ) || exit;


if ( cb_bp_show_item_horizontal_sub_nav() ) {
	bp_get_template_part( 'members/single/settings/nav' );
}

switch ( bp_current_action() ) :
	case 'notifications':
		bp_get_template_part( 'members/single/settings/notifications' );
		break;
	case 'capabilities':
		bp_get_template_part( 'members/single/settings/capabilities' );
		break;
	case 'delete-account':
		bp_get_template_part( 'members/single/settings/delete-account' );
		break;
	case 'general':
		bp_get_template_part( 'members/single/settings/general' );
		break;
	case 'profile':
		bp_get_template_part( 'members/single/settings/profile' );
		break;
	case 'data':
		bp_get_template_part( 'members/single/settings/data' );
		break;
	default:
		bp_get_template_part( 'members/single/plugins' );
		break;
endswitch;
