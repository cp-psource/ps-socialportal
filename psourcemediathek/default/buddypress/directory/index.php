<?php
// Exit if the file is accessed directly over web.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<?php do_action( 'bp_before_directory_psourcemediathek_page' ); ?>

	<div id="buddypress" class="psmt-directory-contents">

		<?php do_action( 'bp_before_directory_psourcemediathek_items' ); ?>

		<?php psmt_get_template_part( 'buddypress/directory/search' ); ?>
		<?php psmt_get_template_part( 'buddypress/directory/nav' ); ?>


		<?php do_action( 'psmt_before_directory_gallery_tabs' ); ?>

		<form action="" method="post" id="psmt-directory-form" class="dir-form">

			<div id="psmt-dir-list" class="psmt psmt-dir-list dir-list">
				<?php
				psmt_get_template( 'gallery/loop-gallery.php' );
				?>
			</div><!-- #psmt-dir-list -->

			<?php do_action( 'psmt_directory_gallery_content' ); ?>

			<?php wp_nonce_field( 'directory_psmt', '_wpnonce-psmt-filter' ); ?>

			<?php do_action( 'psmt_after_directory_gallery_content' ); ?>

		</form><!-- #psmt-directory-form -->

		<?php do_action( 'psmt_after_directory_gallery' ); ?>

	</div><!-- #buddypress -->

<?php do_action( 'psmt_after_directory_gallery_page' ); ?>
<?php
