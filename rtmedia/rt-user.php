<?php
do_action( 'bp_before_member_home_content' );
do_action( 'bp_before_member_body' );
do_action( 'bp_before_member_media' );
?>
<div class="<?php cb_bp_single_item_nav_css_class( array( 'component' => 'rtmedia', 'type' => 'sub' ) );?>" data-object="rtmedia">
	<div class="<?php cb_bp_single_item_tabs_css_class( array( 'component' => 'rtmedia', 'type' => 'sub', 'class'=>'no-ajax' ) );?>" id="subnav" role="navigation">
		<ul>
			<?php rtmedia_sub_nav(); ?>

			<?php do_action( 'rtmedia_sub_nav' ); ?>

		</ul>
	</div><!-- .item-list-tabs -->
</div><!-- end of bp-nav -->

<?php
rtmedia_load_template();

do_action( 'bp_after_member_media' );
do_action( 'bp_after_member_body' );
