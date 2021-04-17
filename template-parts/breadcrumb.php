<?php
/**
 * Breadcrumb template.
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
<?php if ( cb_is_breadcrumb_enabled() ) : ?>
	<?php do_action( 'cb_before_site_breadcrumb' ); ?>

	<div id="site-breadcrumb" class="site-breadcrumb">
		<div class="inner clearfix">
			<?php cb_breadcrumb(); ?>
		</div>
	</div>

	<?php do_action( 'cb_after_site_breadcrumb' ); ?>
<?php endif; ?>
