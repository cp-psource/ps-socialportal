<?php
/**
 * Activity Loop.
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
 * Fires before the start of the activity loop.
 */
do_action( 'bp_before_activity_loop' );
?>

<?php if ( bp_has_activities( bp_ajax_querystring( 'activity' ) ) ) : ?>

	<?php if ( empty( $_POST['page'] ) ) : ?>

		<ul id="activity-stream" class="<?php cb_bp_activity_list_class(); ?>">

	<?php endif; ?>

	<?php while ( bp_activities() ) : bp_the_activity(); ?>

		<?php bp_get_template_part( 'activity/entry' ); ?>

	<?php endwhile; ?>

	<?php if ( bp_activity_has_more_items() ) : ?>

		<li class="load-more">
			<a href="<?php bp_activity_load_more_link() ?>" class="btn btn-primary btn-activity-load-more"> <i class="fa fa-refresh"></i> <?php _e( 'Mehr laden', 'social-portal' ); ?></a>
		</li>

	<?php endif; ?>

	<?php if ( empty( $_POST['page'] ) ) : ?>

		</ul>

	<?php endif; ?>

<?php else : ?>

	<div id="message" class="info">
		<p><?php _e( 'Entschuldigung, es wurde keine Aktivität gefunden. Bitte versuche es mit einem anderen Filter.', 'social-portal' ); ?></p>
	</div>

<?php endif; ?>

<?php

/**
 * Fires after the finish of the activity loop.
 */
do_action( 'bp_after_activity_loop' );
?>

<?php if ( empty( $_POST['page'] ) ) : ?>

	<form action="" name="activity-loop-form" id="activity-loop-form" method="post">

		<?php wp_nonce_field( 'activity_filter', '_wpnonce_activity_filter' ); ?>

	</form>

<?php
endif;
