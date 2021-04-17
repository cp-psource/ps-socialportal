<?php
/**
 * Common sections customized css generator
 *
 * It generate css styles based on customize control settings(Site customize modifications ).
 * This file is loaded in
 *
 * @package PS_SocialPortal
 */

// Do not allow direct access over web.
defined( 'ABSPATH' ) || exit;

/**
 * Toc:-
 *
 * 1. General
 * 2. Layout
 * 3. Typography
 * 4. Color
 * 5. Background
 * 6. Widget & sidebar
 */

/**
 * Get the singleton instance of layout builder
 */
$builder = cb_get_css_builder(); // phpcs:ignore WPThemeReview.CoreFunctionality.PrefixAllGlobals.NonPrefixedVariableFound
// Layout:-
// We don't need to worry about page layout as adding css class will take care of them
// see the filter applied on body_class for details.
// Site Theme Layout.
$theme_style = cb_get_modified_value( 'layout-style' );
// Set width for fluid layouts.
// We only add custom css for fluid layout, the fixed layout is handled by default.
if ( 'fluid' === $theme_style ) {

	$builder->add(
		array(
			'selectors'    => array( '.inner' ),
			'declarations' => array(
				'max-width' => cb_get_option( 'theme-fluid-width' ) . '%',
				// in case of fluid width, we use 90% of the screen width.
			),
			'media'        => 'screen and (min-width: 992px)',
		)
	);
}

// Set the width for content/sidebar if modified.
$content_width = cb_get_modified_value( 'content-width' );
$has_sidebars  = cb_is_sidebar_enabled();
// Content/Sidebar width setup.
if ( ! empty( $content_width ) && $has_sidebars ) {
	// we do not allow float value here, only integers allowed.
	$content_width = absint( $content_width );
	// main column.
	$builder->add(
		array(
			'selectors'    => array( '.site-content' ),
			'declarations' => array(
				'width' => $content_width . '%',
			),
			'media'        => 'screen and (min-width: 992px)',
		)
	);

	// sidebar.
	$builder->add(
		array(
			'selectors'    => array( '.site-sidebar' ),
			'declarations' => array(
				'width' => ( 100 - $content_width ) . '%',
			),
			'media'        => 'screen and (min-width: 992px)',
		)
	);
}
// Panels.
// Is left panel enabled all time?
cb_add_visibility_style($builder, '#panel-left-toggle', cb_get_panel_visibility( 'left' ));

// Right panel.
cb_add_visibility_style($builder, '#panel-right-toggle', cb_get_panel_visibility( 'right' ) );

// --------------------------------------------------------------------
// 3. Typography
// --------------------------------------------------------------------

// Base font size, family, line height etc.
cb_css_add_font_style( $builder, 'base', 'body' );

// Headers h1-h6 typography.
for ( $i = 1; $i <= 6; $i ++ ) {
	$element = 'h' . $i; // h1-h6.
	cb_css_add_font_style( $builder, $element, $element );
}

// CSS for site title & tagline.
cb_css_add_font_style( $builder, 'site-title', '#site-title a' );
cb_css_add_font_style( $builder, 'site-tagline', '.site-description' );

// Top Quick Menu.
cb_css_add_font_style( $builder, 'quick-menu-1', '.quick-menu-1' );

cb_css_add_font_style( $builder, 'main-menu', '.main-menu' );
cb_css_add_font_style( $builder, 'sub-menu', '.main-menu li li' );

// Header bottom menu.
cb_css_add_font_style( $builder, 'header-bottom-menu', '.header-bottom-menu' );
cb_css_add_font_style( $builder, 'header-bottom-sub-menu', '.header-bottom-menu li li' );

// Page header fonts.
cb_css_add_font_style( $builder, 'page-header-content', '.page-header-entry' );
cb_css_add_font_style( $builder, 'page-header-title', '.page-header-title' );
cb_css_add_font_style( $builder, 'page-header-meta', '.page-header-meta' );

// widget title & content.
cb_css_add_font_style( $builder, 'widget', '.widget' );
cb_css_add_font_style( $builder, 'widget-title', '.widget-title' );

// footer.
cb_css_add_font_style( $builder, 'footer', '.site-copyright' );
// Footer widgets.
cb_css_add_font_style( $builder, 'footer-widget', '.site-footer .widget' );
cb_css_add_font_style( $builder, 'footer-widget-title', '.site-footer .widget-title' );
cb_css_add_font_style( $builder, 'site-copyright-font-settings', '.site-footer .site-copyright' );

// --------------------------------------------------------------------
// 4. Styling
// --------------------------------------------------------------------
// Body color & background.
$rules = array();

$bg_color = cb_get_modified_value( 'background-color' );
if ( $bg_color ) {
	$rules['background-color'] = $bg_color;
}

$primary_text_color = cb_get_modified_value( 'text-color' );
if ( $primary_text_color ) {
	$rules['color'] = $primary_text_color;
}
// Site Background.
$background_image = set_url_scheme( get_background_image() );

if ( $background_image ) {
	$background_image = esc_url( $background_image );
	// we have a background image set.
	$rules['background-image'] = "url({$background_image})";

	$repeat = cb_get_modified_value( 'background_repeat' );
	if ( $repeat ) {
		$rules['background-repeat'] = $repeat;
	}

	$position = cb_get_modified_value( 'background_position_x' );
	if ( $position ) {
		$rules['background-position'] = $position . ' center';
	}

	$attachment = cb_get_modified_value( 'background_attachment' );
	if ( $attachment ) {
		$rules['background-attachment'] = $attachment;

	}
}

if ( ! empty( $rules ) ) {
	$builder->add(
		array(
			'selectors'    => array( 'body' ),
			'declarations' => $rules,
		)
	);
}
unset( $rules );
// Link color & Hover.
cb_css_add_property_style( $builder, 'color', 'link-color', 'a' );
cb_css_add_property_style( $builder, 'color', 'link-hover-color', 'a:hover, a:focus' );

// Buttons global
// buttons bg.
$btn_selector       = '.button, input[type="submit"], .btn, .bp-login-widget-register-link a, button, .btn-secondary, .activity-item a.button, .ac-reply-content input[type="submit"], a.comment-reply-link, .sow-more-text a';
$btn_hover_selector = '.button:hover, input[type="submit"]:hover, .btn:hover, .bp-login-widget-register-link a:hover, button:hover, .btn-secondary:hover, .activity-item a.button:hover, a.comment-reply-link:hover, .sow-more-text a:hover, .button:focus, input[type="submit"]:focus, .btn:focus, .bp-login-widget-register-link a:focus, button:focus, .btn-secondary:focus, .activity-item a.button:focus, a.comment-reply-link:focus, .sow-more-text a:focus';

cb_css_add_button_style( $builder, 'button', $btn_selector, $btn_hover_selector );

// Logo.
cb_css_add_link_style( $builder, 'site-title', '#site-title a' );
cb_css_add_text_color_style( $builder, 'site-tagline', '.site-description' );
// site header.
cb_css_add_common_style( $builder, 'header', '.site-header' );
cb_css_add_link_style( $builder, 'header', '.site-header a' );
cb_css_add_border_style( $builder, 'header', '.site-header' );
cb_css_add_font_size_style( $builder, 'header-social-icon', '.site-header ul.social-links .fa' );

// Header top.
if ( cb_is_site_header_top_row_enabled() ) {
	cb_css_add_common_style( $builder, 'header-top', '.site-header-row-top' );
	cb_css_add_link_style( $builder, 'header-top', '.site-header-row-top a' );
	cb_css_add_border_style( $builder, 'header-top', '.site-header-row-top' );
}

// site header row main.
cb_css_add_common_style( $builder, 'header-main', '.site-header-row-main' );
cb_css_add_link_style( $builder, 'header-main', '.site-header-row-main a' );
cb_css_add_border_style( $builder, 'header-main', '.site-header-row-main' );
// site header row bottom.
if ( cb_is_site_header_bottom_row_enabled() ) {
	cb_css_add_common_style( $builder, 'header-bottom', '.site-header-row-bottom' );
	cb_css_add_link_style( $builder, 'header-bottom', '.site-header-row-bottom a' );
	cb_css_add_border_style( $builder, 'header-bottom', '.site-header-row-bottom' );
}

// Header panel toggles.
// panel toggle colors.
cb_css_add_property_style( $builder, 'color', 'panel-left-toggle-color', '#panel-left-toggle' );
cb_css_add_property_style( $builder, 'color', 'panel-right-toggle-color', '#panel-right-toggle' );

// header buttons.
// cb_css_add_button_style( $builder, 'header-buttons', 'header-links a.btn', 'header-links a.btn:hover' );
// main menu.
cb_css_add_common_style( $builder, 'main-menu', '.main-menu' );
// nav item. using ID for higher specificity.
cb_css_add_background_hover_style( $builder, 'main-menu-link', '#main-menu .menu-item-level-0 > a', '#main-menu .menu-item-level-0 > a:hover' );
cb_css_add_border_style( $builder, 'main-menu-link', '#main-menu .menu-item-level-0 > a' );
cb_css_add_border_style( $builder, 'main-menu-link-hover', '#main-menu .menu-item-level-0 > a:hover' );
// selected item.
cb_css_add_selected_menu_item_style( $builder, 'main-menu', array( '#main-menu .current-menu-item > a', '#main-menu .current-menu-parent > a' ) );

cb_css_add_background_hover_style( $builder, 'sub-menu-link', '#main-menu li:not(.menu-item-level-0) a', '#main-menu li:not(.menu-item-level-0) a:hover' );
cb_css_add_border_style( $builder, 'sub-menu-link', '#main-menu li:not(.menu-item-level-0) a' );
cb_css_add_border_style( $builder, 'sub-menu-link-hover', '#main-menu li:not(.menu-item-level-0) a:hover' );
cb_css_add_selected_menu_item_style( $builder, 'sub-menu', array( '#main-menu li .current-menu-item > a', '#main-menu li .current-menu-parent > a' ) );

// Quick Menu 1.
if ( cb_site_header_supports( 'quick-menu-1' ) ) {
	cb_css_add_common_style( $builder, 'quick-menu-1', '.quick-menu-1' );
	cb_css_add_background_hover_style( $builder, 'quick-menu-1-link', '#quick-menu-1 .menu-item-level-0 > a', '#quick-menu-1 .menu-item-level-0 > a:hover' );
	cb_css_add_border_style( $builder, 'quick-menu-1-link', '#quick-menu-1 .menu-item-level-0 > a' );
	cb_css_add_border_style( $builder, 'quick-menu-1-link-hover', '#quick-menu-1 .menu-item-level-0 > a:hover' );
	// selected item.
	cb_css_add_selected_menu_item_style(
		$builder,
		'quick-menu-1',
		array(
			'#quick-menu-1 .current-menu-item > a',
			'#quick-menu-1 .current-menu-parent > a',
		)
	);
}

// Header bottom Menu.
if ( cb_site_header_supports( 'header-bottom-menu' ) ) {
	cb_css_add_common_style( $builder, 'header-bottom-menu', '.header-bottom-menu' );
	cb_css_add_background_hover_style( $builder, 'header-bottom-menu-link', '#header-bottom-menu .menu-item-level-0 > a', '#header-bottom-menu .menu-item-level-0 > a:hover' );
	cb_css_add_border_style( $builder, 'header-bottom-menu-link', '#header-bottom-menu .menu-item-level-0 > a' );
	cb_css_add_border_style( $builder, 'header-bottom-menu-link-hover', '#header-bottom-menu .menu-item-level-0 > a:hover' );
	// selected item.
	cb_css_add_selected_menu_item_style(
		$builder,
		'header-bottom-menu',
		array(
			'#header-bottom-menu .current-menu-item > a',
			'#header-bottom-menu .current-menu-parent > a',
		)
	);

	cb_css_add_background_hover_style( $builder, 'header-bottom-sub-menu-link', '#header-bottom-menu li:not(.menu-item-level-0) a', '#header-bottom-menu li:not(.menu-item-level-0) a:hover' );
	cb_css_add_border_style( $builder, 'header-bottom-sub-menu-link', '#header-bottom-menu li:not(.menu-item-level-0) a' );
	cb_css_add_border_style( $builder, 'header-bottom-sub-menu-link-hover', '#header-bottom-menu li:not(.menu-item-level-0) a:hover' );
	cb_css_add_selected_menu_item_style(
		$builder,
		'header-bottom-sub-menu',
		array(
			'#header-bottom-menu li .current-menu-item > a',
			'#header-bottom-menu li .current-menu-parent > a',
		)
	);
}

// Site Page Header.
if ( cb_is_page_header_enabled() ) {
	$header_image_url = cb_get_page_header_image();
	$bg_color         = cb_get_modified_value( 'page-header-background-color' );

	$rules = array();

	if ( ! empty( $header_image_url ) ) {
		$header_image_url          = esc_url( $header_image_url );
		$rules['background-image'] = "url({$header_image_url})";
	}

	if ( ! empty( $bg_color ) ) {
		$rules['background-color'] = $bg_color;
	}

	if ( $rules ) {

		$builder->add(
			array(
				'selectors'    => array( '.page-header' ),
				'declarations' => $rules,
			)
		);
		// reset rules.
		unset( $rules );
	}

	cb_css_add_border_style( $builder, 'page-header', '.page-header' );

	// Global page header.
	$page_header_height = cb_get_modified_value( 'page-header-height' );
	if ( $page_header_height ) {
		cb_add_responsive_declarations( $builder, '.page-header', 'min-height', $page_header_height, 'px' );
	}

	// archive page header.
	$page_header_height = cb_get_modified_value( 'archive-page-header-height' );
	if ( cb_get_modified_value( 'archive-enable-custom-page-header-height', 0 ) ) {
		cb_add_responsive_declarations( $builder, 'body.archive .page-header', 'min-height', $page_header_height, 'px' );
	}

	// For singular post type, page header height.
	$cb_custom_post_types = cb_get_customizable_post_types();
	$cb_custom_post_types = array_merge( $cb_custom_post_types, array( 'page', 'product' ) );
	foreach ( $cb_custom_post_types as $cb_custom_post_type ) {
		// we are adding css and not overriding the '.page-header' to allow us cache the generated css in future.
		if ( cb_get_modified_value( $cb_custom_post_type . '-enable-custom-page-header-height', 0 ) ) {
			$page_header_height = cb_get_modified_value( $cb_custom_post_type . '-page-header-height', 0 );
			if ( ! $page_header_height ) {
				continue;
			}
			cb_add_responsive_declarations( $builder, '.single-type-' . $cb_custom_post_type . ' .page-header', 'min-height', $page_header_height, 'px' );
		}
	}

	// page header mask.
	cb_css_add_property_style( $builder, 'background-color', 'page-header-mask-color', '.page-header-mask-enabled .page-header-mask, .has-cover-image .page-header-mask, .bp-user .page-header-mask' );

	// page header text colors.
	cb_css_add_text_color_style( $builder, 'page-header-content', '.page-header-description' );
	cb_css_add_text_color_style( $builder, 'page-header-title', '.page-header-title' );
	cb_css_add_text_color_style( $builder, 'page-header-meta', '.page-header-meta' );
	cb_css_add_link_style( $builder, 'page-header-meta', '.page-header-meta a' );

}


// Container.
cb_css_add_common_style( $builder, 'container', '.site-container' );
cb_css_add_border_style( $builder, 'container', '.site-container' );
// content padding, bg, text, link, border.
cb_css_add_padding_style( $builder, 'content', '.site-content' );
cb_css_add_common_style( $builder, 'content', '.site-content' );
cb_css_add_link_style( $builder, 'content', '.site-content a' );
cb_css_add_border_style( $builder, 'content', '.site-content' );
// sidebar.
cb_css_add_padding_style( $builder, 'sidebar', '.site-sidebar' );
cb_css_add_common_style( $builder, 'sidebar', '.site-sidebar' );
cb_css_add_link_style( $builder, 'sidebar', '.site-sidebar a' );
cb_css_add_border_style( $builder, 'sidebar', '.site-sidebar' );

// widget.
cb_css_add_common_style( $builder, 'widget', '.widget' );
// using 'body' to make the target selection more specific than the parent's anchor(.site-sidebar a).
cb_css_add_link_style( $builder, 'widget', 'body .widget a' );
cb_css_add_border_style( $builder, 'widget', '.widget' );
cb_css_add_margin_style( $builder, 'widget', '.widget' );
cb_css_add_padding_style( $builder, 'widget', '.widget' );

cb_css_add_common_style( $builder, 'widget-title', '.widget-title' );
cb_css_add_link_style( $builder, 'widget-title', 'body .widget-title a' );

// sidebar widget.
// widget.
cb_css_add_common_style( $builder, 'sidebar-widget', '.site-sidebar .widget' );
cb_css_add_link_style( $builder, 'sidebar-widget', '.site-sidebar .widget a' );
cb_css_add_border_style( $builder, 'sidebar-widget', '.site-sidebar .widget' );
cb_css_add_margin_style( $builder, 'sidebar-widget', '.site-sidebar .widget' );
cb_css_add_padding_style( $builder, 'sidebar-widget', '.site-sidebar .widget' );

cb_css_add_common_style( $builder, 'sidebar-widget-title', '.site-sidebar .widget-title' );
cb_css_add_link_style( $builder, 'sidebar-widget-title', '.site-sidebar .widget-title a' );

// Panel Left.
if ( cb_is_panel_left_enabled() ) {
	cb_css_add_padding_style( $builder, 'panel-left', '.panel-sidebar-left' );
	cb_css_add_common_style( $builder, 'panel-left', '.panel-sidebar-left' );
	cb_css_add_link_style( $builder, 'panel-left', '.panel-sidebar-left a' );
	// panel left widgets.
	cb_css_add_common_style( $builder, 'panel-left-widget', '.panel-sidebar-left .widget' );
	cb_css_add_link_style( $builder, 'panel-left-widget', '.panel-sidebar-left .widget a' );
	cb_css_add_border_style( $builder, 'panel-left-widget', '.panel-sidebar-left .widget' );
	cb_css_add_margin_style( $builder, 'panel-left-widget', '.panel-sidebar-left .widget' );
	cb_css_add_padding_style( $builder, 'panel-left-widget', '.panel-sidebar-left .widget' );
	// panel left widget title.
	cb_css_add_common_style( $builder, 'panel-left-widget-title', '.panel-sidebar-left .widget-title' );
	cb_css_add_link_style( $builder, 'panel-left-widget-title', '.panel-sidebar-left .widget-title a' );

	// panel left menu.
	if ( has_nav_menu( 'panel-left-menu' ) ) {
		cb_css_add_background_hover_style( $builder, 'panel-left-menu-link', '#panel-left-menu .menu-item-level-0 > a', '#panel-left-menu .menu-item-level-0 > a:hover' );
		cb_css_add_border_style( $builder, 'panel-left-menu-link', '#panel-left-menu .menu-item-level-0 > a' );
		cb_css_add_border_style( $builder, 'panel-left-menu-link-hover', '#panel-left-menu .menu-item-level-0 > a:hover' );
		// selected item.
		cb_css_add_selected_menu_item_style(
			$builder,
			'panel-left-menu',
			array(
				'#panel-left-menu .current-menu-item.menu-item-level-0 > a',
				'#panel-left-menu .current-menu-parent.menu-item-level-0 > a',
			)
		);
		// sub menu.
		cb_css_add_background_hover_style( $builder, 'panel-left-sub-menu-link', '#panel-left-menu li:not(.menu-item-level-0) a', '#panel-left-menu li:not(.menu-item-level-0) a:hover' );
		cb_css_add_border_style( $builder, 'panel-left-sub-menu-link', '#panel-left-menu li:not(.menu-item-level-0) a' );
		cb_css_add_border_style( $builder, 'panel-left-sub-menu-link-hover', '#panel-left-menu li:not(.menu-item-level-0) a:hover' );
		cb_css_add_selected_menu_item_style(
			$builder,
			'panel-left-sub-menu',
			array(
				'#panel-left-menu li .current-menu-item > a',
				'#panel-left-menu li .current-menu-parent > a',
			)
		);
	}
}

// Panel right.
if ( cb_is_panel_right_enabled() ) {
	cb_css_add_padding_style( $builder, 'panel-right', '.panel-sidebar-right' );
	cb_css_add_common_style( $builder, 'panel-right', '.panel-sidebar-right' );
	cb_css_add_link_style( $builder, 'panel-right', '.panel-sidebar-right a' );
	// panel right widgets.
	cb_css_add_common_style( $builder, 'panel-right-widget', '.panel-sidebar-right .widget' );
	cb_css_add_link_style( $builder, 'panel-right-widget', '.panel-sidebar-right .widget a' );
	cb_css_add_border_style( $builder, 'panel-right-widget', '.panel-sidebar-right .widget' );
	cb_css_add_margin_style( $builder, 'panel-right-widget', '.panel-sidebar-right .widget' );
	cb_css_add_padding_style( $builder, 'panel-right-widget', '.panel-sidebar-right .widget' );
	// panel right widget title.
	cb_css_add_common_style( $builder, 'panel-right-widget-title', '.panel-sidebar-right .widget-title' );
	cb_css_add_link_style( $builder, 'panel-right-widget-title', '.panel-sidebar-right .widget-title a' );

	// panel right menu.
	if ( has_nav_menu( 'panel-right-menu' ) ) {
		cb_css_add_background_hover_style( $builder, 'panel-right-menu-link', '#panel-right-menu .menu-item-level-0 > a', '#panel-right-menu .menu-item-level-0 > a:hover' );
		cb_css_add_border_style( $builder, 'panel-right-menu-link', '#panel-right-menu .menu-item-level-0 > a' );
		cb_css_add_border_style( $builder, 'panel-right-menu-link-hover', '#panel-right-menu .menu-item-level-0 > a:hover' );
		// selected item.
		cb_css_add_selected_menu_item_style(
			$builder,
			'panel-right-menu',
			array(
				'#panel-right-menu .current-menu-item.menu-item-level-0 > a',
				'#panel-right-menu .current-menu-parent.menu-item-level-0 > a',
			)
		);
		// sub menu.
		cb_css_add_background_hover_style( $builder, 'panel-right-sub-menu-link', '#panel-right-menu li:not(.menu-item-level-0) a', '#panel-right-menu li:not(.menu-item-level-0) a:hover' );
		cb_css_add_border_style( $builder, 'panel-right-sub-menu-link', '#panel-right-menu li:not(.menu-item-level-0) a' );
		cb_css_add_border_style( $builder, 'panel-right-sub-menu-link-hover', '#panel-right-menu li:not(.menu-item-level-0) a:hover' );
		cb_css_add_selected_menu_item_style(
			$builder,
			'panel-right-sub-menu',
			array(
				'#panel-right-menu li .current-menu-item > a',
				'#panel-right-menu li .current-menu-parent > a',
			)
		);
	}
}

$is_footer_enabled = cb_is_site_footer_enabled();
// Footer.
if ( $is_footer_enabled ) {
	cb_css_add_common_style( $builder, 'footer', '.site-footer' );
	cb_css_add_link_style( $builder, 'footer', '.site-footer a' );
	cb_css_add_border_style( $builder, 'footer', '.site-footer' );
}

// Footer widgets.
if ( $is_footer_enabled && cb_is_site_footer_widget_area_enabled() ) {
	cb_css_add_common_style( $builder, 'footer-top', '.site-footer-top' );
	cb_css_add_link_style( $builder, 'footer-top', '.site-footer-top a' );
	cb_css_add_border_style( $builder, 'footer-top', '.site-footer-top' );

	cb_css_add_common_style( $builder, 'footer-top-widget', '.site-footer-top .widget' );
	cb_css_add_link_style( $builder, 'footer-top-widget', '.site-footer-top .widget a' );
	cb_css_add_border_style( $builder, 'footer-top-widget', '.site-footer-top .widget' );
	cb_css_add_margin_style( $builder, 'footer-top-widget', '.site-footer-top .widget' );
	cb_css_add_padding_style( $builder, 'footer-top-widget', '.site-footer-top .widget' );

	// widget title.
	cb_css_add_common_style( $builder, 'footer-top-widget-title', '.site-footer-top .widget-title' );
	cb_css_add_link_style( $builder, 'footer-top-widget-title', '.site-footer-top .widget-title a' );
}

// copyright.
if ( $is_footer_enabled ) {
	cb_css_add_common_style( $builder, 'site-copyright', '.site-copyright' );
	cb_css_add_link_style( $builder, 'site-copyright', '.site-copyright a' );
	cb_css_add_border_style( $builder, 'site-copyright', '.site-copyright' );
	// social icons.
	cb_css_add_font_size_style( $builder, 'footer-social-icon', '.site-footer ul.social-links .fa' );
}

// Item single entry.
cb_css_add_font_style( $builder, 'item-entry-title', '.item-single .entry-title' );
cb_css_add_link_style( $builder, 'item-entry-title', '.item-single .entry-title a' );

cb_css_add_font_style( $builder, 'item-entry-meta', '.item-single .entry-meta' );
cb_css_add_text_color_style( $builder, 'item-entry-meta', '.item-single .entry-meta-item' );
cb_css_add_text_color_style( $builder, 'item-entry-meta-separator', '.item-single .entry-meta-separator' );
cb_css_add_link_style( $builder, 'item-entry-meta', '.item-single .entry-meta a' );

cb_css_add_font_style( $builder, 'item-entry-content', '.entry-content' );
cb_css_add_text_color_style( $builder, 'item-entry-content', '.entry-content' );
cb_css_add_link_style( $builder, 'item-entry-content', '.entry-content a' );

// Item list entry.
cb_css_add_font_style( $builder, 'item-list-entry-title', '.item-list .entry-title' );
cb_css_add_link_style( $builder, 'item-list-entry-title', '.item-list .entry-title a' );

cb_css_add_font_style( $builder, 'item-list-entry-meta', '.item-list .entry-meta' );
cb_css_add_text_color_style( $builder, 'item-list-entry-meta', '.item-list .entry-meta-item' );
cb_css_add_text_color_style( $builder, 'item-list-entry-meta-separator', '.item-list .entry-meta-separator' );
cb_css_add_link_style( $builder, 'item-list-entry-meta', '.item-list .entry-meta a' );

cb_css_add_font_style( $builder, 'item-list-entry-content', '.entry-summary' );
cb_css_add_text_color_style( $builder, 'item-list-entry-content', '.entry-summary' );
cb_css_add_link_style( $builder, 'item-list-entry-content', '.entry-summary a' );
