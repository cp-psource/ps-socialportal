<?php
/**
 * Activity filters.
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
<label for="activity-filter-by"><?php _e( 'Zeige:', 'social-portal' ); ?></label>

<select id="activity-filter-by">
	<option value="-1"><?php _e( '&mdash; Alles &mdash;', 'social-portal' ); ?></option>
	<?php bp_activity_show_filters(); ?>
	<?php

	/**
	 * Fires inside the select input for activity filter by options.
	 */
	do_action( 'bp_activity_filter_options' );
	?>
</select>