<?php
/**
 * BuddyPress - Users Plugins Template
 *
 * 3rd-party plugins should use this template to easily add template
 * support to their plugins for the members component.
 */

// Do not allow direct access over web.
defined( 'ABSPATH' ) || exit;

/**
 * Fires at the start of the member plugin template.
 */
do_action( 'bp_before_member_plugin_template' );


if ( cb_bp_show_item_horizontal_sub_nav() ) {
	bp_get_template_part( 'members/single/plugins/nav' );
}
?>
<h3 class="bp-plugin-title"><?php
	/**
	 * Fires inside the member plugin template <h3> tag.
    */
	do_action( 'bp_template_title' );
	?>
</h3>

<div class="bp-plugins-container bp-<?php echo esc_attr( bp_current_component() ); ?>-container rounded-box">

<?php
/**
 * Fires and displays the member plugin template content.
 */
do_action( 'bp_template_content' );
?>

<?php
/**
 * Fires at the end of the member plugin template.
 */
do_action( 'bp_after_member_plugin_template' );
?>

</div>
