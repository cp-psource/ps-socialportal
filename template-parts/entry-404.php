<?php
/**
 * 404- Not found entry.
 *
 * @package    PS_SocialPortal
 * @subpackage Template
 * @copyright  Copyright (c) 2018, DerN3rd
 * @license    https://www.gnu.org/licenses/gpl.html GNU Public License
 * @author     DerN3rd
 */

// Do not allow direct access over web.
defined( 'ABSPATH' ) || exit;

?>
<article class="article-error-404 clearfix" id="post-error-404">

	<?php do_action( 'cb_before_404_entry' ); ?>

	<?php if ( ! is_404() ) : ?>

		<header class="entry-header entry-header-404">
			<i class="fa fa-user-secret"></i>
		</header>

	<?php endif; ?>

	<div class="clearfix entry-content">

		<p><?php _e( 'Nichts gefunden! Wir schauen es uns an und werden es bald wieder haben.', 'social-portal' ); ?></p>

		<?php get_search_form(); ?>

	</div><!-- .entry-content -->

	<?php do_action( 'cb_after_404_entry' ); ?>

</article>
