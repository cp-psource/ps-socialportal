<?php
/**
 * Template for BuddyPress docs plugin.
 *
 * @package PS_SocialPortal
 */

// Do not allow direct access over web.
defined( 'ABSPATH' ) || exit;

?>
<?php do_action( 'bp_docs_before_doc_header' ) ?>

<?php /* Subnavigation on user pages is handled by BP's core functions */ ?>
<?php if ( ! bp_is_user() ) : ?>
<div class="<?php cb_bp_dir_item_nav_css_class( array( 'component' => 'docs', 'type'=> 'primary' ) );?>" data-object="docs">
    <div class="<?php cb_bp_dir_item_tabs_css_class( array( 'component'=> 'docs', 'type'=> 'primary', 'class'=> 'no-ajax' ) ); ?>" role="navigation">
	    <?php bp_docs_tabs( current_user_can( 'bp_docs_create' ) ); ?>
    </div><!-- .item-list-tabs -->
</div>

<?php endif ?>

<?php do_action( 'bp_docs_before_doc_header_content' ); ?>

<?php if ( bp_docs_is_existing_doc() ) : ?>

	<div id="bp-docs-single-doc-header">
		<?php if ( ! bp_docs_is_theme_compat_active() ) : ?>
			<h2 class="doc-title"><?php bp_docs_the_breadcrumb() ?><?php if ( bp_docs_is_doc_trashed() ) : ?> <span class="bp-docs-trashed-doc-notice" title="<?php esc_html_e( 'Dieser Doc ist im Papierkorb', 'social-portal' ) ?>"><?php esc_html_e( 'Müll', 'social-portal' ); ?></span><?php endif ?></h2>
		<?php endif ?>

		<?php do_action( 'bp_docs_single_doc_header_fields' ) ?>
	</div>

	<div class="doc-tabs">
		<ul>
			<li <?php if ( bp_docs_is_doc_read() ) : ?> class="current"<?php endif ?>>
				<a href="<?php bp_docs_doc_link() ?>"><?php _e( 'Lesen', 'social-portal' ) ?></a>
			</li>

			<?php if ( current_user_can( 'bp_docs_edit' ) ) : ?>
				<li<?php if ( bp_docs_is_doc_edit() ) : ?> class="current"<?php endif ?>>
					<a href="<?php bp_docs_doc_edit_link() ?>"><?php _e( 'Bearbeiten', 'social-portal' ) ?></a>
				</li>
			<?php endif ?>

			<?php do_action( 'bp_docs_header_tabs' ); ?>
		</ul>
	</div>

<?php elseif ( bp_docs_is_doc_create() ) : ?>

	<h2><?php _e( 'Neues Doc', 'social-portal' ); ?></h2>

<?php endif ?>

<?php do_action( 'bp_docs_after_doc_header_content' ); ?>
