<?php
/**
 * Blogs directory search
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
<div class="bp-search bp-dir-search bp-blogs-dir-search" data-bp-search="blogs">
	<form action="" method="get" class="bp-dir-search-form" id="blogs-dir-search-form" role="search">

		<label for="blogs-search" class="bp-screen-reader-text"><?php _e('Suche', 'social-portal') ;?></label>

		<?php $query_arg = bp_core_get_component_search_query_arg( 'blogs' );?>

		<input id="blogs-search"  class="search-input blogs-search-input" name="<?php echo esc_attr( $query_arg ); ?>" type="search"  placeholder="<?php echo esc_attr( bp_get_search_default_text('blogs')); ?>" />

		<button type="submit" id="blogs-search-submit' ); ?>" class="bp-search-submit blogs-search-submit" name="blogs_search_submit">
			<i class="fa fa-search" aria-hidden="true"></i>
			<span id="button-text" class="bp-screen-reader-text"><?php echo esc_html_x( 'Suche', 'button', 'social-portal' ); ?></span>
		</button>

	</form>
</div>
