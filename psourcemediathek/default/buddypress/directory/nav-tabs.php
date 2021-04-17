<?php
/**
 * BuddyPress Members directory nav tabs
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
<ul>
    <li class="selected" id="psmt-all">
        <a href="<?php echo get_permalink( buddypress()->pages->psourcemediathek->id ); ?>"><?php printf( __( 'Alle Galerien <span>%s</span>', 'psourcemediathek' ), psmt_get_total_gallery_count() ) ?></a>
    </li>

	<?php do_action( 'psmt_directory_types' ) ?>

</ul>