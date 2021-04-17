<?php

/**
 * Pagination for pages of replies (when viewing a topic)
 *
 * @package bbPress
 * @subpackage Theme
 */

?>

<?php do_action( 'bbp_template_before_pagination_loop' ); ?>

<div class="pagination bbp-pagination">
	<div class="pag-count bbp-pagination-count">

		<?php bbp_topic_pagination_count(); ?>

	</div>

	<div class="pagination-links lbbp-pagination-links">

		<?php bbp_topic_pagination_links(); ?>

	</div>
</div>

<?php do_action( 'bbp_template_after_pagination_loop' ); ?>
