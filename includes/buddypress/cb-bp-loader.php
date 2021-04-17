<?php
/**
 * Friendship functions.
 *
 * @package    PS_SocialPortal
 * @subpackage BuddyPress
 * @copyright  Copyright (c) 2019, DerN3rd
 * @license    https://www.gnu.org/licenses/gpl.html GNU Public License
 * @author     DerN3rd
 */

// Do not allow direct access over web.
defined( 'ABSPATH' ) || exit;
// we may move it to CB_Plugin_Compat_Loader in future.
require CB_THEME_PATH . '/includes/buddypress/bootstrap/class-cb-bp-loader.php';
require CB_THEME_PATH . '/includes/buddypress/bootstrap/class-cb-bp-configurator.php';

// Init.
CB_BP_Loader::boot();
