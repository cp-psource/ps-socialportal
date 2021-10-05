<?php

/**
 * Pagination for pages of search results 
 *
 * @package PSForum
 * @subpackage Theme
 */

?>

<?php do_action( 'psf_template_before_pagination_loop' ); ?>

<div class="pagination psf-pagination">
	<div class="pag-count psf-pagination-count">

		<?php psf_search_pagination_count(); ?>

	</div>

	<div class="pagination-links gpsf-pagination-links">

		<?php psf_search_pagination_links(); ?>

	</div>
</div>

<?php do_action( 'psf_template_after_pagination_loop' ); ?>
