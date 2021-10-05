<?php

/**
 * Pagination for pages of topics (when viewing a forum)
 *
 * @package PSForum
 * @subpackage Theme
 */

?>

<?php do_action( 'psf_template_before_pagination_loop' ); ?>

<div class="pagination psf-pagination">
	<div class="pag-count psf-pagination-count">

		<?php psf_forum_pagination_count(); ?>

	</div>

	<div class="pagination-links psf-pagination-links">

		<?php psf_forum_pagination_links(); ?>

	</div>
</div>

<?php do_action( 'psf_template_after_pagination_loop' ); ?>
