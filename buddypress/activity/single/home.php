<?php
/**
 * Single activity permalink page content
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
<div id="buddypress" class="bp-content-wrapper">
	<div class="activity no-ajax">
		<?php if ( bp_has_activities( 'display_comments=threaded&show_hidden=true&include=' . bp_current_action() ) ) : ?>

			<ul id="activity-stream" class="<?php cb_bp_activity_list_class( 'activiy-list-single' ); ?>">
				<?php while ( bp_activities() ) : bp_the_activity(); ?>
					<?php bp_get_template_part( 'activity/entry' ); ?>
				<?php endwhile; ?>
			</ul>

		<?php endif; ?>
	</div>
</div>
