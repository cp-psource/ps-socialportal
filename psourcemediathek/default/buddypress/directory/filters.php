<?php
/**
 * BuddyPress Members directory filters
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
<div class="dir-search-anchor">
	<a href="#"><i class="fa fa-search"></i></a>
</div>
<div class="bp-filter-order-by bp-psmt-filter-order-by">

	<label for="psmt-filter-by"><?php _e( 'Filtern nach:', 'social-portal' ); ?></label>
	<select id="psmt-filter-by">
		<option value=""><?php _e( 'Alle Galerien', 'psourcemediathek' ) ?></option>

		<?php $active_types = psmt_get_active_types(); ?>

		<?php foreach( $active_types as $type => $type_object ):?>
			<option value="<?php echo $type;?>"><?php echo $type_object->get_label();?> </option>
		<?php endforeach;?>

		<?php do_action( 'psmt_gallery_directory_order_options' ) ?>
	</select>

</div>