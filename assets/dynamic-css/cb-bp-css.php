<?php
/**
 * BuddyPress specific generated css
 */
/**
 * Get the singleton instance of layout builder
 */
$builder = cb_get_css_builder(); // phpcs:ignore WPThemeReview.CoreFunctionality.PrefixAllGlobals.NonPrefixedVariableFound
cb_css_add_button_style( $builder, 'header-login-button', '.header-login-button', '.header-login-button:hover' );
cb_css_add_button_style( $builder, 'header-register-button', '.header-register-button', '.header-register-button:hover' );
// add directory height.
$cb_page_header_height = cb_get_modified_value( 'bp-dir-page-header-height' );
if ( $cb_page_header_height ) {
	cb_add_responsive_declarations( $builder, '.directory .page-header', 'min-height', $cb_page_header_height, 'px' );
}

$cb_page_header_height = cb_get_modified_value( 'bp-member-profile-page-header-height' );
if ( $cb_page_header_height ) {
	cb_add_responsive_declarations( $builder, '.bp-user .page-header', 'min-height', $cb_page_header_height, 'px' );
}

cb_css_add_font_style( $builder, 'bp-single-item-title', '.item-header .item-title' );
cb_css_add_link_style( $builder, 'bp-single-item-title', '.item-header .item-title a' );

$cb_item_avatar_size = cb_get_option( '.bp-item-list-avatar-size', 65);
$builder->add(
	array(
		'selectors'    => array( '.item-list-type-list .item-entry-style-regular .item-entry-header' ),
		'declarations' => array(
			'min-width' => $cb_item_avatar_size . 'px',
			// in case of fluid width, we use 90% of the screen width.
		),
//		'media'        => 'screen and (min-width: 992px)',
	)
);
