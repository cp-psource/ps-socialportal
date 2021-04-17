( function( $, api ) {
	// For Live Preview.
	var $head = $( 'head' ),
		btnSelector,
		btnHoverSelector,
		headers,
		// Media queries.
		mediaQueryDevices = {
			mobile: '@media screen and (max-width: 575px)',
			tablet: '@media screen and (min-width: 576px) and (max-width: 767px)',
			desktop: '@media screen and (min-width: 992px)'
		};

	/**
	 * Asynchronous updating
	 */

	// #Panel: General#
	// 1. Site Title live update.
	registerContentUpdateHandler( 'blogname', '#site-title a' );
	// 2. Site tagline update.
	registerContentUpdateHandler( 'blogdescription', '.site-description' );
	// Custom Text block 1(in the header).
	registerContentUpdateHandler( 'custom-text-block-1', '.custom-text-block-1' );

	// ### Panel: Layout ###
	// 1. Global Section.
	// 1.a Global - Layout width change handler.
	// Layout width for the fluid layout.
	registerLayoutWidthChangeHandler();
	// content width for the 2 col design.
	registerContentWidthChangeHandler();

	// 1.b Global - Panel left toggle icon visibility.
	registerVisibilityToggleUpdateHandler( 'panel-left-visibility', '#panel-left-toggle' );
	// 1.c Global - Panel right toggle icon visibility.
	registerVisibilityToggleUpdateHandler( 'panel-right-visibility', '#panel-right-toggle' );

	// 2. Header - Icon size
	// Header social icons.
	registerFontSizeUpdateHandler( 'header-social-icon-font-size', '.site-header ul.social-links .fa' );

	// 3. Footer
	// 3 a. Footer - site copyright & year.
	registerFooterTextUpdateHandler( 'footer-text' );

	// 3.b Footer - Social links font size.
	registerFontSizeUpdateHandler( 'footer-social-icon-font-size', '.site-footer ul.social-links .fa' );

	// ### Panel: Typography ###
	// 1. Typography - Global Settings Section.
	// Base Typography for Body.
	registerFontUpdateHandler( 'base', 'body' );

	// 2. Typography - Text Headers Section.
	headers = [ 'h1', 'h2', 'h3', 'h4', 'h5', 'h6' ];

	for ( var i = 0; i < headers.length; i++ ) {
		registerFontUpdateHandler( headers[i], 'article ' + headers[i] );
	}

	// 3. Typography - Site Title & Tagline section.
	// 3.a Site title typography.
	registerFontUpdateHandler( 'site-title', '#site-title a' );
	//3.b Site tagline Typography.
	registerFontUpdateHandler( 'site-tagline', '.site-description' );

	// 4. Typography - Main Menu typography.
	// 4.a Toplevel Menu Items typography.
	registerFontUpdateHandler( 'main-menu', '#nav' );
	// 4.b Submenu items typography.
	registerFontUpdateHandler( 'sub-menu', '#nav li li' );

	// 4.c quick menu -1
	registerFontUpdateHandler( 'quick-menu-1', '#quick-menu-1' );

	// 4.d header bottom menu
	registerFontUpdateHandler( 'header-bottom-menu', '#header-bottom-menu' );
	// 4.e header bottom sub menu items typography.
	registerFontUpdateHandler( 'header-bottom-sub-menu', '#header-bottom-menu li li' );

	// 5. Typography - Page Headers Typography.
	registerFontUpdateHandler( 'page-header-title', '.page-header .page-header-title' );
	registerFontUpdateHandler( 'page-header-content', '.page-header-content' );
	registerFontUpdateHandler( 'page-header-meta', '.page-header-meta' );

	// 6. Typography - Sidebars
	registerFontUpdateHandler( 'widget-title', '.site-sidebar .widget-title' );
	registerFontUpdateHandler( 'widget', '.site-sidebar .widget' );

	// 7. Typography - Footer
	// Footer Widgets
	registerFontUpdateHandler( 'footer-widget-title', '.site-footer .widget-title' );
	// Footer widget content
	registerFontUpdateHandler( 'footer-widget', '.site-footer .widget' );
	// Footer content.
	registerFontUpdateHandler( 'footer', '.site-footer' );
	registerFontUpdateHandler( 'site-copyright', '.site-footer .site-copyright' );

	// ### Panel: Styling ###
	// 1. Styling - Global.
	// 1.a - Global text color.
	registerTextColorUpdateHandler( 'text-color', 'body' );
	// 1.b - Global Link color & hover color.
	registerTextHoverColorUpdateHandler( 'link-color', 'link-hover-color', 'a', 'a:hover' );
	// 1.c - Global Buttons bg.
	btnSelector = '.button, input[type="submit"], .btn, .bp-login-widget-register-link a, button, .btn-secondary .activity-item a.button, .ac-reply-content input[type="submit"], a.comment-reply-link, .sow-more-text a';
	btnHoverSelector = '.button:hover, input[type="submit"]:hover, .btn:hover, .bp-login-widget-register-link a:hover, button:hover, .btn-secondary:hover, .activity-item a.button:hover, a.comment-reply-link:hover, .sow-more-text a:hover, .button:focus, input[type="submit"]:focus, .btn:focus, .bp-login-widget-register-link a:focus, button:focus, .btn-secondary:focus, .activity-item a.button:focus, a.comment-reply-link:focus, .sow-more-text a:focus';
	registerBackgroundHoverColorUpdateHandler( 'button-background-color', 'button-hover-background-color', btnSelector, btnHoverSelector );
	// 1.d - Global Button text color.
	registerTextHoverColorUpdateHandler( 'button-text-color', 'button-hover-text-color', btnSelector, btnHoverSelector );
	registerBorderStyleUpdateHandler( 'button-border', btnSelector );
	registerBorderStyleUpdateHandler( 'button-hover-border', btnHoverSelector );
	// 1.e - Top Login Button.
	registerBackgroundHoverColorUpdateHandler( 'header-login-button-background-color', 'header-login-button-hover-background-color', '.header-login-button', '.header-login-button:hover' );
	registerTextHoverColorUpdateHandler( 'header-login-button-text-color', 'header-login-button-hover-text-color', '.header-login-button', '.header-login-button:hover' );
	registerBorderStyleUpdateHandler( 'header-login-button-border', '.header-login-button' );
	registerBorderStyleUpdateHandler( 'header-login-button-hover-border', '.header-login-button:hover' );
	// 1.f - Top register Button.
	registerBackgroundHoverColorUpdateHandler( 'header-register-button-background-color', 'header-register-button-hover-background-color', '.header-register-button', '.header-register-button:hover' );
	registerTextHoverColorUpdateHandler( 'header-register-button-text-color', 'header-register-button-hover-text-color', '.header-register-button', '.header-register-button:hover' );
	registerBorderStyleUpdateHandler( 'header-register-button-border', '.header-register-button' );
	registerBorderStyleUpdateHandler( 'header-register-button-hover-border', '.header-register-button:hover' );
	// 2. Global Site Background
	// Site BG Color, the background image is handled by wp.
	registerBackgroundColorUpdateHandler( 'background-color', 'body' );

	// 3 Site title & Tagline
	// 3.a Site title color/hover color.
	registerLinkHoverColorUpdateHandler( 'site-title', '#site-title a' );
	// 3.b - Site tagline color.
	registerTextColorUpdateHandler( 'site-tagline-text-color', '.site-description' );

	// 4. Site header.
	// 4.a Site Header.
	registerBackgroundStyleUpdateHandler( 'header-background', '.site-header' );
	registerTextColorUpdateHandler( 'header-text-color', '.site-header' );
	registerLinkHoverColorUpdateHandler( 'header', '.site-header a' );
	registerBorderStyleUpdateHandler( 'header-border', '.site-header' );

	registerTextColorUpdateHandler( 'panel-left-toggle-color', '#panel-left-toggle' );
	registerTextColorUpdateHandler( 'panel-right-toggle-color', '#panel-right-toggle' );

	// 4.b Site header - top row.
	registerBackgroundColorUpdateHandler( 'header-top-background-color', '.site-header-row-top' );
	registerTextColorUpdateHandler( 'header-top-text-color', '.site-header-row-top' );
	registerLinkHoverColorUpdateHandler( 'header-top', '.site-header-row-top a' );
	registerBorderStyleUpdateHandler( 'header-top-border', '.site-header-row-top' );

	// 4.c Site Header main row.
	registerBackgroundColorUpdateHandler( 'header-main-background-color', '.site-header-row-main' );
	registerTextColorUpdateHandler( 'header-main-text-color', '.site-header-row-main' );
	registerLinkHoverColorUpdateHandler( 'header-main', '.site-header-row-main a' );
	registerBorderStyleUpdateHandler( 'header-main-border', '.site-header-row-main' );

	// 4.d Site Header Bottom Row.
	registerBackgroundColorUpdateHandler( 'header-bottom-background-color', '.site-header-row-bottom' );
	registerTextColorUpdateHandler( 'header-bottom-text-color', '.site-header-row-bottom' );
	registerLinkHoverColorUpdateHandler( 'header-bottom', '.site-header-row-bottom a' );
	registerBorderStyleUpdateHandler( 'header-bottom-border', '.site-header-row-bottom' );

	// 5. Main Menu
	// 5.a - Menu background.
	registerBackgroundStyleUpdateHandler( 'main-menu-background', '#main-menu' );
	// 5.b Main menu alignment.
	registerAlignmentClassUpdateHandler( 'main-menu-alignment', '#main-menu', 'nav-menu-' );

	// 5.c - Menu Item styling.
	registerLinkHoverColorUpdateHandler( 'main-menu', '#main-menu .menu-item-level-0 > a' );
	registerBackgroundHoverColorUpdateHandler( 'main-menu-link-background-color', 'main-menu-link-hover-background-color', '#main-menu .menu-item-level-0 > a' );
	registerBorderStyleUpdateHandler( 'main-menu-link-border', '#main-menu .menu-item-level-0 > a' );
	registerBorderStyleUpdateHandler( 'main-menu-link-hover-border', '#main-menu .menu-item-level-0 > a:hover' );
	// 5.d Current menu item font weight.
	registerFontWeightUpdateHandler( 'main-menu-selected-item-font-weight', '#main-menu .current-menu-item > a, #main-menu .current-menu-parent > a' );
	registerTextColorUpdateHandler( 'main-menu-selected-item-color', '#main-menu .current-menu-item > a, #main-menu .current-menu-parent > a' );

	// 5.e Sub nav in main menu
	registerLinkHoverColorUpdateHandler( 'sub-menu', '#main-menu li:not(.menu-item-level-0) a' );
	registerBackgroundHoverColorUpdateHandler( 'sub-menu-link-background-color', 'sub-menu-link-hover-background-color', '#main-menu li:not(.menu-item-level-0) a' );
	registerBorderStyleUpdateHandler( 'sub-menu-link-border', '#main-menu li:not(.menu-item-level-0) a' );
	registerBorderStyleUpdateHandler( 'sub-menu-link-hover-border', '#main-menu li:not(.menu-item-level-0) a:hover' );

	// 5.f Current menu item font weight.
	registerFontWeightUpdateHandler( 'sub-menu-selected-item-font-weight', '#main-menu li .current-menu-item > a, #main-menu li .current-menu-parent > a' );
	registerTextColorUpdateHandler( 'sub-menu-selected-item-color', '#main-menu li .current-menu-item > a, .main-menu li .current-menu-parent > a' );

	// Quick menu 1
	//background
	registerBackgroundStyleUpdateHandler( 'quick-menu-1-background', '.quick-menu-1' );
	// 6.a menu alignment.
	registerAlignmentClassUpdateHandler( 'quick-menu-1-alignment', '#quick-menu-1', 'nav-menu-' );

	// 6.b - Menu Item styling.
	registerLinkHoverColorUpdateHandler( 'quick-menu-1', '#quick-menu-1 .menu-item-level-0 > a' );
	registerBackgroundHoverColorUpdateHandler( 'quick-menu-1-link-background-color', 'quick-menu-1-link-hover-background-color', '#quick-menu-1 .menu-item-level-0 > a' );
	registerBorderStyleUpdateHandler( 'quick-menu-1-link-border', '#quick-menu-1 .menu-item-level-0 > a' );
	registerBorderStyleUpdateHandler( 'quick-menu-1-link-hover-border', '#quick-menu-1 .menu-item-level-0 > a:hover' );

	// 6.c Current menu item font weight.
	registerFontWeightUpdateHandler( 'quick-menu-1-selected-item-font-weight', '#quick-menu-1 .current-menu-item > a' );
	registerTextColorUpdateHandler( 'quick-menu-1-selected-item-color', '#quick-menu-1 .current-menu-item > a' );

	// Header bottom menu
	//background.
	registerBackgroundStyleUpdateHandler( 'header-bottom-menu-background', '.header-bottom-menu' );
	// 7.a menu alignment.
	registerAlignmentClassUpdateHandler( 'header-bottom-menu-alignment', '#header-bottom-menu', 'nav-menu-' );

	// 7.b - Menu Item styling.
	registerLinkHoverColorUpdateHandler( 'header-bottom-menu', '#header-bottom-menu .menu-item-level-0 > a' );
	registerBackgroundHoverColorUpdateHandler( 'header-bottom-menu-link-background-color', 'header-bottom-menu-link-hover-background-color', '#header-bottom-menu .menu-item-level-0 > a' );
	registerBorderStyleUpdateHandler( 'header-bottom-menu-link-border', '#header-bottom-menu .menu-item-level-0 > a' );
	registerBorderStyleUpdateHandler( 'header-bottom-menu-link-hover-border', '#header-bottom-menu .menu-item-level-0 > a:hover' );

	// 7.c Current menu item font weight.
	registerFontWeightUpdateHandler( 'header-bottom-menu-selected-item-font-weight', '#header-bottom-menu .current-menu-item > a' );
	registerTextColorUpdateHandler( 'header-bottom-menu-selected-item-color', '#header-bottom-menu .current-menu-item > a' );

	// 7.d - Header bottom sub Menu Item styling.
	registerLinkHoverColorUpdateHandler( 'header-bottom-sub-menu', '#header-bottom-menu li:not(.menu-item-level-0) a' );
	registerBackgroundHoverColorUpdateHandler( 'header-bottom-sub-menu-link-background-color', 'header-bottom-sub-menu-link-hover-background-color', '#header-bottom-menu li:not(.menu-item-level-0) a' );
	registerBorderStyleUpdateHandler( 'header-bottom-sub-menu-link-border', '#header-bottom-menu li:not(.menu-item-level-0) a' );
	registerBorderStyleUpdateHandler( 'header-bottom-sub-menu-link-hover-border', '#header-bottom-menu li:not(.menu-item-level-0) a:hover' );

	// 7.e Current menu item font weight.
	registerFontWeightUpdateHandler( 'header-bottom-sub-menu-selected-item-font-weight', '#header-bottom-menu li .current-menu-item > a, #header-bottom-menu li .current-menu-parent > a' );
	registerTextColorUpdateHandler( 'header-bottom-sub-menu-selected-item-color', '#header-bottom-menu li .current-menu-item > a, .header-bottom-menu li .current-menu-parent > a' );

	// 8. Page Header
	registerResponsiveRangeUpdateHandler( 'page-header-height', '.page-header', 'min-height', 'px' );
	// archive page.
	registerResponsiveRangeUpdateHandler( 'archive-page-header-height', 'body.archive .page-header', 'min-height', 'px' );
	// the use of .directory may have side effects in future,
	registerResponsiveRangeUpdateHandler( 'bp-dir-page-header-height', '.directory .page-header', 'min-height', 'px' );

	// for each post type, add this.
	if ( typeof CBPreviewData !== 'undefined' && typeof CBPreviewData.post_types !== 'undefined' ) {
		for ( var postType in CBPreviewData.post_types ) {
			if ( ! CBPreviewData.post_types.hasOwnProperty( postType ) ) {
				continue;
			}
			registerResponsiveRangeUpdateHandler( postType + '-page-header-height', 'body.single-type-' + postType + ' .page-header', 'min-height', 'px' );
		}
	}

	// 8.a Page Header Mask color.
	registerBackgroundColorUpdateHandler( 'page-header-mask-color', '.page-header-mask-enabled .page-header-mask, .has-cover-image .page-header-mask, .bp-user .page-header-mask' );
	// 8.b background
	registerBackgroundColorUpdateHandler( 'page-header-background-color', '.page-header' );
	registerBorderStyleUpdateHandler( 'page-header-border', '.page-header' );
	// 8.c page Header text colors
	registerTextColorUpdateHandler( 'page-header-title-text-color', '.page-header .page-header-title' );
	registerTextColorUpdateHandler( 'page-header-content-text-color', '.page-header-description' );
	registerTextColorUpdateHandler( 'page-header-meta-text-color', '.page-header-meta' );
	registerLinkHoverColorUpdateHandler( 'page-header-meta', '.page-header-meta a' );

	// 9. Main element.
	registerBackgroundStyleUpdateHandler( 'container-background', '.site-container' );
	registerBorderStyleUpdateHandler( 'container-border', '.site-container' );

	// content area.
	registerPaddingUpdateHandler( 'content-padding', '.site-content' );
	registerBackgroundStyleUpdateHandler( 'content-background', '.site-content' );
	registerBorderStyleUpdateHandler( 'content-border', '.site-content' );
	registerTextColorUpdateHandler( 'content-text-color', '.site-content' );
	registerLinkHoverColorUpdateHandler( 'content', '.site-content a' );

	// 10. Widgets
	registerBackgroundColorUpdateHandler( 'widget-title-background-color', '.widget-title' );
	registerTextColorUpdateHandler( 'widget-title-text-color', '.widget-title' );
	registerLinkHoverColorUpdateHandler( 'widget-title', 'body .widget-title a' );

	// widget content
	registerBackgroundColorUpdateHandler( 'widget-background-color', '.widget' );
	registerTextColorUpdateHandler( 'widget-text-color', '.widget' );
	registerLinkHoverColorUpdateHandler( 'widget', 'body .widget a' );
	registerBorderStyleUpdateHandler( 'widget-border', '.widget' );
	registerMarginUpdateHandler( 'widget-margin', '.widget' );
	registerPaddingUpdateHandler( 'widget-padding', '.widget' );

	// 11. Sidebar.
	registerPaddingUpdateHandler( 'sidebar-padding', '.site-sidebar' );
	registerBackgroundColorUpdateHandler( 'sidebar-background-color', '.site-sidebar' );
	registerTextColorUpdateHandler( 'sidebar-text-color', '.site-sidebar' );
	registerBorderStyleUpdateHandler( 'sidebar-border', '.site-sidebar' );
	registerLinkHoverColorUpdateHandler( 'sidebar', '.site-sidebar a' );
	// sidebar widget title
	registerBackgroundColorUpdateHandler( 'sidebar-widget-title-background-color', '.site-sidebar .widget-title' );
	registerTextColorUpdateHandler( 'sidebar-widget-title-text-color', '.site-sidebar .widget-title' );
	registerLinkHoverColorUpdateHandler( 'sidebar-widget-title', '.site-sidebar .widget-title a' );

	// sidebar widget content
	registerBackgroundColorUpdateHandler( 'sidebar-widget-background-color', '.site-sidebar .widget' );
	registerTextColorUpdateHandler( 'sidebar-widget-text-color', '.site-sidebar .widget' );
	registerLinkHoverColorUpdateHandler( 'sidebar-widget', '.site-sidebar .widget a' );
	registerBorderStyleUpdateHandler( 'sidebar-widget-border', '.site-sidebar .widget' );
	registerMarginUpdateHandler( 'sidebar-widget-margin', '.site-sidebar .widget' );
	registerPaddingUpdateHandler( 'sidebar-widget-padding', '.site-sidebar .widget' );

	// 12. Panel Left
	registerPaddingUpdateHandler( 'panel-left-padding', '.panel-sidebar-left' );
	registerBackgroundColorUpdateHandler( 'panel-left-background-color', '.panel-sidebar-left' );
	registerTextColorUpdateHandler( 'panel-left-text-color', '.panel-sidebar-left' );
	registerLinkHoverColorUpdateHandler( 'panel-left', '.panel-sidebar-left a' );

	// panel left widget title
	registerBackgroundColorUpdateHandler( 'panel-left-widget-title-background-color', '.panel-sidebar-left .widget-title' );
	registerTextColorUpdateHandler( 'panel-left-widget-title-text-color', '.panel-sidebar-left .widget-title' );
	registerLinkHoverColorUpdateHandler( 'panel-left-widget-title', '.panel-sidebar-left .widget-title a' );

	// panel left widget content
	registerBackgroundColorUpdateHandler( 'panel-left-widget-background-color', '.panel-sidebar-left .widget' );
	registerTextColorUpdateHandler( 'panel-left-widget-text-color', '.panel-sidebar-left .widget' );
	registerLinkHoverColorUpdateHandler( 'panel-left-widget', '.panel-sidebar-left .widget a' );
	registerBorderStyleUpdateHandler( 'panel-left-widget-border', '.panel-sidebar-left .widget' );
	registerMarginUpdateHandler( 'panel-left-widget-margin', '.panel-sidebar-left .widget' );
	registerPaddingUpdateHandler( 'panel-left-widget-padding', '.panel-sidebar-left .widget' );

	// left panel menu.

	// 13.a - Menu Item styling.
	registerLinkHoverColorUpdateHandler( 'panel-left-menu', '#panel-left-menu .menu-item-level-0 > a' );
	registerBackgroundHoverColorUpdateHandler( 'panel-left-menu-link-background-color', 'panel-left-menu-link-hover-background-color', '#panel-left-menu .menu-item-level-0 > a', '#panel-left-menu .menu-item-level-0 > a:hover' );

	registerBorderStyleUpdateHandler( 'panel-left-menu-link-border', '#panel-left-menu .menu-item-level-0 > a' );
	registerBorderStyleUpdateHandler( 'panel-left-menu-link-hover-border', '#panel-left-menu .menu-item-level-0 > a:hover' );

	// 13.b Current menu item font weight.
	registerFontWeightUpdateHandler( 'panel-left-menu-selected-item-font-weight', '#panel-left-menu .current-menu-item.menu-item-level-0 > a, #panel-left-menu .current-menu-parent.menu-item-level-0 > a' );
	registerTextColorUpdateHandler( 'panel-left-menu-selected-item-color', '#panel-left-menu .current-menu-item.menu-item-level-0 > a, #panel-left-menu .current-menu-parent.menu-item-level-0 > a' );

	// 13.c Sub nav in main menu
	registerLinkHoverColorUpdateHandler( 'panel-left-sub-menu', '#panel-left-menu li:not(.menu-item-level-0) a' );
	registerBackgroundHoverColorUpdateHandler( 'panel-left-sub-menu-link-background-color', 'panel-left-sub-menu-link-hover-background-color', '#panel-left-menu li:not(.menu-item-level-0) a' );
	registerBorderStyleUpdateHandler( 'panel-left-sub-menu-link-border', '#panel-left-menu li:not(.menu-item-level-0) a' );
	registerBorderStyleUpdateHandler( 'panel-left-sub-menu-link-hover-border', '#panel-left-menu li:not(.menu-item-level-0) a:hover' );

	registerFontWeightUpdateHandler( 'panel-left-sub-menu-selected-item-font-weight', '#panel-left-menu li .current-menu-item > a, #panel-left-menu li .current-menu-parent > a' );
	registerTextColorUpdateHandler( 'panel-left-sub-menu-selected-item-color', '#panel-left-menu li .current-menu-item > a, #panel-left-menu li .current-menu-parent > a' );

	//14. Panel Right
	registerPaddingUpdateHandler( 'panel-right-padding', '.panel-sidebar-right' );
	registerBackgroundColorUpdateHandler( 'panel-right-background-color', '.panel-sidebar-right' );
	registerTextColorUpdateHandler( 'panel-right-text-color', '.panel-sidebar-right' );
	registerLinkHoverColorUpdateHandler( 'right-left', '.panel-sidebar-right a' );

	// sidebar widget title
	registerBackgroundColorUpdateHandler( 'panel-right-widget-title-background-color', '.panel-sidebar-right .widget-title' );
	registerTextColorUpdateHandler( 'panel-right-widget-title-text-color', '.panel-sidebar-right .widget-title' );
	registerLinkHoverColorUpdateHandler( 'panel-right-widget-title', '.panel-sidebar-right .widget-title a' );

	// panel right sidebar widget content
	registerBackgroundColorUpdateHandler( 'panel-right-widget-background-color', '.panel-sidebar-right .widget' );
	registerTextColorUpdateHandler( 'panel-right-widget-text-color', '.panel-sidebar-right .widget' );
	registerLinkHoverColorUpdateHandler( 'panel-right-widget', '.panel-sidebar-right .widget a' );
	registerBorderStyleUpdateHandler( 'panel-right-widget-border', '.panel-sidebar-right .widget' );
	registerMarginUpdateHandler( 'panel-right-widget-margin', '.panel-sidebar-right .widget' );
	registerPaddingUpdateHandler( 'panel-right-widget-padding', '.panel-sidebar-right .widget' );

	// panel right menu
	// 15.a - Menu Item styling.
	registerLinkHoverColorUpdateHandler( 'panel-right-menu', '#panel-right-menu .menu-item-level-0 > a' );
	registerBackgroundHoverColorUpdateHandler( 'panel-right-menu-link-background-color', 'panel-right-menu-link-hover-background-color', '#panel-right-menu .menu-item-level-0 > a', '#panel-right-menu .menu-item-level-0 > a:hover' );
	registerBorderStyleUpdateHandler( 'panel-right-menu-link-border', '#panel-right-menu .menu-item-level-0 > a' );
	registerBorderStyleUpdateHandler( 'panel-right-menu-link-hover-border', '#panel-right-menu .menu-item-level-0 > a:hover' );
	// 15.b Current menu item font weight.
	registerFontWeightUpdateHandler( 'panel-right-menu-selected-item-font-weight', '#panel-right-menu .current-menu-item > a, #panel-right-menu .current-menu-parent > a' );
	registerTextColorUpdateHandler( 'panel-right-menu-selected-item-color', '#panel-right-menu .current-menu-item > a, #panel-right-menu .current-menu-parent > a' );

	// 15.c Sub nav in main menu
	registerLinkHoverColorUpdateHandler( 'panel-right-sub-menu', '#panel-right-menu li:not(.menu-item-level-0) a' );
	registerBackgroundHoverColorUpdateHandler( 'panel-right-sub-menu-link-background-color', 'panel-right-sub-menu-link-hover-background-color', '#panel-right-menu li:not(.menu-item-level-0) a' );
	registerBorderStyleUpdateHandler( 'panel-right-sub-menu-link-border', '#panel-right-menu li:not(.menu-item-level-0) a' );
	registerBorderStyleUpdateHandler( 'panel-right-sub-menu-link-hover-border', '#panel-right-menu li:not(.menu-item-level-0) a:hover' );

	registerFontWeightUpdateHandler( 'panel-right-sub-menu-selected-item-font-weight', '#panel-right-menu li .current-menu-item > a, #panel-right-menu li .current-menu-parent > a' );
	registerTextColorUpdateHandler( 'panel-right-sub-menu-selected-item-color', '#panel-right-menu li .current-menu-item > a, .panel-right-menu li .current-menu-parent > a' );

	// 16. Footer
	registerBackgroundStyleUpdateHandler( 'footer-background', '.site-footer' );
	registerTextColorUpdateHandler( 'footer-text-color', '.site-footer' );
	registerLinkHoverColorUpdateHandler( 'footer', '.site-footer a' );
	registerBorderStyleUpdateHandler( 'footer-border', '.site-footer' );

	// 16. Footer widget area.
	registerFontUpdateHandler( 'footer-top', '.site-footer-top' );
	registerBackgroundStyleUpdateHandler( 'footer-top-background', '.site-footer-top' );
	registerTextColorUpdateHandler( 'footer-top-text-color', '.site-footer-top' );
	registerLinkHoverColorUpdateHandler( 'footer-top', '.site-footer-top a' );
	registerBorderStyleUpdateHandler( 'footer-top-border', '.site-footer-top' );

	// widget title
	registerBackgroundColorUpdateHandler( 'footer-top-widget-title-background-color', '.site-footer-top .widget-title' );
	registerTextColorUpdateHandler( 'footer-top-widget-title-text-color', '.site-footer-top .widget-title' );
	registerLinkHoverColorUpdateHandler( 'footer-top-widget-title', '.site-footer-top .widget-title a' );

	// widget content
	registerBackgroundColorUpdateHandler( 'footer-top-widget-background-color', '.site-footer-top .widget' );
	registerTextColorUpdateHandler( 'footer-top-widget-text-color', '.site-footer-top .widget' );
	registerLinkHoverColorUpdateHandler( 'footer-top-widget', '.site-footer-top .widget a' );
	registerBorderStyleUpdateHandler( 'footer-top-widget-border', '.site-footer-top .widget' );
	registerMarginUpdateHandler( 'footer-top-widget-margin', '.site-footer-top .widget' );
	registerPaddingUpdateHandler( 'footer-top-widget-padding', '.site-footer-top .widget' );

	// 17. Site Copyright area
	registerBackgroundColorUpdateHandler( 'site-copyright-background-color', '#site-copyright' );
	registerTextColorUpdateHandler( 'site-copyright-text-color', '#site-copyright' );
	registerLinkHoverColorUpdateHandler( 'site-copyright', '#site-copyright a' );
	registerBorderStyleUpdateHandler( 'site-copyright-border', '.site-copyright' );

	// BuddyPress.
	registerFontUpdateHandler( 'bp-single-item-title', 'div#item-header h2' );
	registerLinkHoverColorUpdateHandler( 'bp-single-item-title', 'div#item-header h2 a' );
	registerFontUpdateHandler( 'bp-single-item-title', 'div#item-header h2 a' );

	// buttons bg.
	registerBackgroundHoverColorUpdateHandler( 'bp-dropdown-toggle-background-color', 'bp-dropdown-toggle-hover-background-color', '.dropdown-toggle', '.dropdown-toggle:hover' );

	registerTextHoverColorUpdateHandler( 'bp-dropdown-toggle-text-color', 'bp-dropdown-toggle-hover-text-color', '.dropdown-toggle', '.dropdown-toggle:hover' );

	// Single Item.
	registerFontUpdateHandler( 'item-entry-title', '.item-single .entry-title' );
	registerLinkHoverColorUpdateHandler( 'item-entry-title', '.item-single .entry-title a' );

	registerFontUpdateHandler( 'item-list-entry-meta', '.item-single .entry-meta' );
	registerTextColorUpdateHandler( 'item-list-entry-meta-text-color', '.item-single .entry-meta-item' );
	registerTextColorUpdateHandler( 'item-list-entry-meta-separator-text-color', '.item-single .entry-meta-separator' );
	registerLinkHoverColorUpdateHandler( 'item-list-entry-meta', '.item-single .entry-meta a' );

	registerFontUpdateHandler( 'item-list-entry-content', '.entry-content' );
	registerTextColorUpdateHandler( 'item-list-entry-content-text-color', '.entry-content' );
	registerLinkHoverColorUpdateHandler( 'item-list-entry-content', '.entry-content a' );

	// Item List entry
	registerFontUpdateHandler( 'item-list-entry-title', '.item-list .entry-title' );
	registerLinkHoverColorUpdateHandler( 'item-list-entry-title', '.item-list .entry-title a' );

	registerFontUpdateHandler( 'item-list-entry-meta', '.item-list .entry-meta' );
	registerTextColorUpdateHandler( 'item-list-entry-meta-text-color', '.item-list .entry-meta-item' );
	registerTextColorUpdateHandler( 'item-list-entry-meta-separator-text-color', '.item-list .entry-meta-separator' );
	registerLinkHoverColorUpdateHandler( 'item-list-entry-meta', '.item-list .entry-meta a' );

	registerFontUpdateHandler( 'item-list-entry-content', '.entry-summary' );
	registerTextColorUpdateHandler( 'item-list-entry-content-text-color', '.entry-summary' );
	registerLinkHoverColorUpdateHandler( 'item-list-entry-content', '.entry-summary a' );

	// BP
	// User page header height.
	registerResponsiveRangeUpdateHandler( 'bp-member-profile-page-header-height', '.bp-user .page-header', 'min-height', 'px' );
	/**
	 * Apply responsive font size/line height change
	 *
	 * @param {string} element
	 * @param {string} selector string
	 */
	function registerFontUpdateHandler( element, selector ) {
		api( element + '-font-settings', function( value ) {
			value.bind( function( to ) {
				var map = {};

				if ( to.variant ) {
					generateCSSStyles( element + '-font-variant', selector, { 'font-weight': to.variant } );
				}

				// check if we have the font-size?
				if ( to['font-size'] ) {
					map['font-size'] = _.mapObject( to['font-size'], function( val, key ) {
						return val + 'px';
					} );
				}

				if ( to['line-height'] ) {
					map['line-height'] = _.mapObject( to['line-height'], function( val, key ) {
						return val + 'em';
					} );
				}

				generateResponsiveCSSStyles( element + '-font-settings', selector, map );
			} );
		} );
	}

	/**
	 * Apply changes in font size(responsive array) live to the given css selectors
	 *
	 * @param {string} setting setting id
	 * @param {string} selector  css selector
	 */
	function registerFontSizeUpdateHandler( setting, selector ) {
		api( setting, function( value ) {
			value.bind( function( to ) {
				if ( to ) {
					to = _.mapObject( to, function( val, key ) {
						return val + 'px';
					} );
					generateResponsiveCSSStyles( setting + '-font-size', selector, { 'font-size': to } );
				}
			} );
		} );
	}
	/**
	 * Apply changes in font size(responsive array) live to the given css selectors
	 *
	 * @param {string} setting setting id
	 * @param {string} selector css selector
	 * @param {string} property
	 * @param {string} unit
	 */
	function registerResponsiveRangeUpdateHandler( setting, selector, property, unit ) {
		if ( 'undefined' === typeof unit ) {
			unit = '';
		}

		api( setting, function( value ) {
			value.bind( function( to ) {
				var data = {};
				if ( to ) {
					to = _.mapObject( to, function( val, key ) {
						return val + unit;
					} );
					data[property] = to;
					generateResponsiveCSSStyles( setting + '-' + property, selector, data );
				}
			} );
		} );
	}

	/**
	 * Apply change in font weight live to the selectors
	 *
	 * @param {string} setting
	 * @param {string} selector css selector
	 */
	function registerFontWeightUpdateHandler( setting, selector ) {
		api( setting, function( value ) {
			value.bind( function( to ) {
				if ( to ) {
					generateCSSStyles( setting + '-font-weight', selector, { 'font-weight': to } );
				}
			} );
		} );
	}

	/**
	 * Apply the line height change live for the given element to the given selector
	 *
	 * @param {string} setting unique setting id
	 * @param {string} selector ( css selector )
	 */
	function registerLineHeightUpdateHandler( setting, selector ) {
		api( setting, function( value ) {
			value.bind( function( to ) {
				if ( to ) {
					generateCSSStyles( setting + '-line-height', selector, { 'line-height': to + 'em' } );
				}
			} );
		} );
	}

	/**
	 * Apply new color change.
	 *
	 * @param {string} setting
	 * @param {string} selector
	 */
	function registerTextColorUpdateHandler( setting, selector ) {
		api( setting, function( value ) {
			value.bind( function( to ) {
				generateCSSStyles( setting + '-color', selector, { color: to } );
			} );
		} );
	}

	/**
	 * Apply hover color Style
	 *
	 * @param {string} setting
	 * @param {string} settingHover
	 * @param {string} selector
	 * @param {string} selectorHover
	 */
	function registerTextHoverColorUpdateHandler( setting, settingHover, selector, selectorHover ) {
		if ( setting ) {
			registerTextColorUpdateHandler( setting, selector );
		}

		if ( ! selectorHover && selector ) {
			selectorHover = selector + ':hover';
		}

		if ( settingHover ) {
			registerTextColorUpdateHandler( settingHover, selectorHover );
		}
	}

	/**
	 * Apply hover styles to links
	 *
	 * @param {string} element
	 * @param {string} selector
	 */
	function registerLinkHoverColorUpdateHandler( element, selector ) {
		registerTextHoverColorUpdateHandler( element + '-link-color', element + '-link-hover-color', selector, selector + ':hover' );
	}

	// element: main
	// selector: '#container'
	// Apply Background styles
	function registerBackgroundColorUpdateHandler( setting, selector ) {
		api( setting, function( value ) {
			value.bind( function( to ) {
				generateCSSStyles( setting + '-background-color', selector, { 'background-color': to } );
			} );
		} );
	}

	/**
	 * Apply Background hover colors.
	 *
	 * @param {string} setting
	 * @param {string} settingHover
	 * @param {string} selector
	 * @param {string} selectorHover
	 */
	function registerBackgroundHoverColorUpdateHandler( setting, settingHover, selector, selectorHover ) {
		if ( setting ) {
			registerBackgroundColorUpdateHandler( setting, selector );
		}

		if ( ! selectorHover && selector ) {
			selectorHover = selector + ':hover';
		}

		if ( settingHover ) {
			registerBackgroundColorUpdateHandler( settingHover, selectorHover );
		}
	}

	/**
	 * Apply background style.
	 *
	 * @param {string} setting
	 * @param {string} selector
	 */
	function registerBackgroundStyleUpdateHandler( setting, selector ) {
		api( setting, function( value ) {
			value.bind( function( to ) {
				// if bg image is not set, do not override other image props?
				if ( ! to['background-image'] ) {
					to = { 'background-color': to['background-color'] };
					if ( $( selector ).css( 'background-image' ) ) {
						// if a background image is set, remove it?
						to['background-image'] = 'none';
					}
				} else {
					to['background-image'] = 'url(' + to['background-image'] + ')';
				}

				generateCSSStyles( setting + '-background', selector, to );
			} );
		} );
	}

	/**
	 * Apply border style.
	 *
	 * @param {string} setting
	 * @param {string} selector
	 */
	function registerBorderStyleUpdateHandler( setting, selector ) {
		api( setting, function( value ) {
			value.bind( function( to ) {
				var widths = to['border-width'],
					borderWidth;
				widths = _.mapObject( widths, function( val, key ) {
					return isNaN( parseInt( val ) ) ? 0 : parseInt( val ) + 'px';
				} );
				borderWidth = widths.top + ' ' + widths.right + ' ' + widths.bottom + ' ' + widths.left;
				generateCSSStyles( setting, selector, {
					border: to['border-style'] + ' ' + to['border-color'],
					'border-width': borderWidth
				} );
			} );
		} );
	}
	/**
	 * Apply border style.
	 *
	 * @param {string} setting
	 * @param {string} selector
	 */
	function registerMarginUpdateHandler( setting, selector ) {
		api( setting, function( value ) {
			value.bind( function( to ) {
				var margin = getResponsiveTRBL( to );

				generateResponsiveCSSStyles( setting, selector, {
					margin: margin
				} );
			} );
		} );
	}

	/**
	 * Apply border style.
	 *
	 * @param {string} setting
	 * @param {string} selector
	 */
	function registerPaddingUpdateHandler( setting, selector ) {
		api( setting, function( value ) {
			value.bind( function( gutters ) {
				var padding = getResponsiveTRBL( gutters );
				generateResponsiveCSSStyles( setting, selector, {
					padding: padding
				} );
			} );
		} );
	}

	/**
	 * Prepare responsive TRBL(TOP, RIGHT, BOTTOM, LEFT) values.
	 *
	 * @param {Object} values
	 * @return {Object} Object with left/right/top/bottom properties.
	 */
	function getResponsiveTRBL( values ) {
		var gutters = _.mapObject( values, function( gutter ) {
			gutter = _.mapObject( gutter, function( val, key ) {
				return isNaN( parseInt( val ) ) ? 0 : parseInt( val ) + 'px';
			} );

			return gutter.top + ' ' + gutter.right + ' ' + gutter.bottom + ' ' + gutter.left;
		} );

		return gutters;
	}

	/**
	 * Apply alignment classes.
	 *
	 * @param {string} setting
	 * @param {string} selector
	 * @param {string} prefix
	 */
	function registerAlignmentClassUpdateHandler( setting, selector, prefix ) {
		var allClasses;
		if ( ! prefix ) {
			prefix = '';
		}

		allClasses = [ 'left', 'center', 'right' ].map( function( value ) {
			return prefix + value;
		} ).join( ' ' );
		api( setting, function( value ) {
			var $el = $( selector );
			value.bind( function( to ) {
				$el.removeClass( allClasses );
				$el.addClass( prefix + to );
			} );
		} );
	}

	/**
	 * Apply visibility toggle.
	 *
	 * @param {string} setting
	 * @param {string} selector
	 */
	function registerVisibilityToggleUpdateHandler( setting, selector ) {
		api( setting, function( value ) {
			value.bind( function( to ) {
				var $el = $( selector );
				if ( 'all' === to ) {
					$el.show();
				} else {
					$el.hide();
				}
			} );
		} );
	}

	/**
	 * Apply content update.
	 *
	 * @param {string} setting
	 * @param {string} selector
	 */
	function registerContentUpdateHandler( setting, selector ) {
		api( setting, function( value ) {
			value.bind( function( to ) {
				$( selector ).html( to );
			} );
		} );
	}

	/**
	 * Handle Layout width change in the customizer.
	 */
	function registerLayoutWidthChangeHandler() {
		// Content Width %.
		var $body = $( 'body' );
		api( 'theme-fluid-width', function( value ) {
			value.bind( function( to ) {
				$body.css( { width: to + '%' } );
			} );
		} );
	}

	/**
	 * Handle Layout width change in the customizer.
	 */
	function registerContentWidthChangeHandler() {
		var $body = $( 'body' );
		// Content Width %.
		api( 'content-width', function( value ) {
			var $container = $( '#site-container' ),
				$sidebar = $( '#site-sidebar' ),
				$content = $( '#site-content' ),
				sidebar;

			value.bind( function( to ) {
				// If it is single column page, do not change width.
				if ( $container.hasClass( 'page-single-col' ) || $body.hasClass( 'layout-single-col' ) && $container.hasClass( 'page-layout-default' ) ) {
					return;
				}

				sidebar = 100 - to;

				$content.css( { width: to + '%' } );
				$sidebar.css( { width: sidebar + '%', display: 'block' } );

				if ( sidebar < 5 ) {
					$sidebar.hide();//display:none
				}
			} );
		} );
	}

	/**
	 * Footer text update handler(used only once).
	 *
	 * @param {string} setting
	 */
	function registerFooterTextUpdateHandler( setting ) {
		api( setting, function( value ) {
			value.bind( function( to ) {
				if ( to ) {
					to = to.replace( '\[current-year\]', ( new Date() ).getFullYear() );
					$( '#site-copyright p' ).html( to );
				}
			} );
		} );
	}
	/**
	 * Adds an internal css <style> block when customizing. Using <style> block helps us keep up with the actual priority.
	 *
	 * @param {string} setting unique setting id, must be unique for each supplied css object, else will override
	 * @param {string} selector the css selector to which the styles should be supplied
	 * @param {Object} cssObject
	 */
	function generateCSSStyles( setting, selector, cssObject ) {
		var rules = '',
			styleID = 'cb-custom-css-style-setting-' + setting,
			$style = $( '#' + styleID );

		// Remove any previous style block for this setting.
		$style.remove();

		if ( $.isEmptyObject( cssObject ) ) {
			return;
		}

		for ( var property in cssObject ) {
			if ( ! cssObject.hasOwnProperty( property ) ) {
				continue;
			}

			rules += property + ':' + cssObject[property] + ';';
		}

		$( '<style type="text/css" id="' + styleID + '">\r\n' +
            selector + '{' + rules + '}' +
            '</style>'
		).appendTo( $head );
	}

	/**
	 * Adds an internal css <style> block when customizing. Using <style> block helps us keep up with the actual priority.
	 *
	 * It helps us deal with responsive properties.
	 *
	 * @param {string} setting unique setting id, must be unique for each supplied css object, else will override
	 * @param {string} selector the css selector to which the styles should be supplied
	 * @param {Object} cssObject
	 */
	function generateResponsiveCSSStyles( setting, selector, cssObject ) {
		var rules = '',
			css = '',
			mediaProps = {},
			device = '',
			styleID = 'cb-custom-css-style-setting-responsive-' + setting,
			$style = $( '#' + styleID ),
			rValues;

		// Remove any previous style block for this setting.
		$style.remove();

		if ( $.isEmptyObject( cssObject ) ) {
			return;
		}

		// organize properties by device.
		for ( var property in cssObject ) {
			if ( ! cssObject.hasOwnProperty( property ) || $.isEmptyObject( cssObject[property] ) ) {
				continue;
			}

			rValues = cssObject[property]; // responsive values arranged by device.
			for ( device in rValues ) {
				mediaProps[device] = mediaProps[device] || {};
				mediaProps[device][property] = rValues[device];
			}
		}

		// we have rebuilt the props by device.
		for ( device in mediaProps ) {
			rules = '';// reset.
			// Build media query.
			css += mediaQueryDevices[device] + ' { ';
			for ( var prop in mediaProps[device] ) {
				rules += prop + ':' + mediaProps[device][prop] + ';';
			}
			css += selector + '{' + rules + '}';
			css += '}';// end of media query.
		}
		// append.
		$( '<style type="text/css" id="' + styleID + '">\r\n' +
            css +
            '</style>'
		).appendTo( $head );
	}

	/**
	 * Global object to let child themes utilize it.
	 *
	 * @type {{
	 *  registerAlignmentClassUpdateHandler: registerAlignmentClassUpdateHandler,
	 *  registerTextHoverColorUpdateHandler: registerTextHoverColorUpdateHandler,
	 *  registerLinkHoverColorUpdateHandler: registerLinkHoverColorUpdateHandler,
	 *  registerTextColorUpdateHandler: registerTextColorUpdateHandler,
	 *  registerFontWeightUpdateHandler: registerFontWeightUpdateHandler,
	 *  generateCSSStyles: generateCSSStyles,
	 *  registerFontUpdateHandler: registerFontUpdateHandler,
	 *  registerLineHeightUpdateHandler: registerLineHeightUpdateHandler,
	 *  registerFontSizeUpdateHandler: registerFontSizeUpdateHandler,
	 *  registerBackgroundColorUpdateHandler: registerBackgroundColorUpdateHandler,
	 *  registerBorderStyleUpdateHandler: registerBorderStyleUpdateHandler,
	 *  registerBackgroundStyleUpdateHandler: registerBackgroundStyleUpdateHandler,
	 *  registerBackgroundHoverColorUpdateHandler: registerBackgroundHoverColorUpdateHandler,
	 *  registerVisibilityToggleUpdateHandler: registerVisibilityToggleUpdateHandler,
	 *  registerContentUpdateHandler: registerContentUpdateHandler,
	 *  generateResponsiveCSSStyles: generateResponsiveCSSStyles
	 *  }}
	 */
	window.CBCustomizeHandlers = {
		//Register font property handler.
		registerFontUpdateHandler: registerFontUpdateHandler,
		registerFontSizeUpdateHandler: registerFontSizeUpdateHandler,
		registerFontWeightUpdateHandler: registerFontWeightUpdateHandler,
		registerLineHeightUpdateHandler: registerLineHeightUpdateHandler,
		// Register text/link color handlers.
		registerTextColorUpdateHandler: registerTextColorUpdateHandler,
		registerTextHoverColorUpdateHandler: registerTextHoverColorUpdateHandler,
		registerLinkHoverColorUpdateHandler: registerLinkHoverColorUpdateHandler,
		// Register background property update handlers.
		registerBackgroundColorUpdateHandler: registerBackgroundColorUpdateHandler,
		registerBackgroundHoverColorUpdateHandler: registerBackgroundHoverColorUpdateHandler,
		registerBackgroundStyleUpdateHandler: registerBackgroundStyleUpdateHandler,
		// Border style update handlers.
		registerBorderStyleUpdateHandler: registerBorderStyleUpdateHandler,
		// Misc setting handlers.
		registerAlignmentClassUpdateHandler: registerAlignmentClassUpdateHandler,
		registerVisibilityToggleUpdateHandler: registerVisibilityToggleUpdateHandler,
		registerContentUpdateHandler: registerContentUpdateHandler,
		// Generate inline css.
		generateCSSStyles: generateCSSStyles,
		generateResponsiveCSSStyles: generateResponsiveCSSStyles
	};
}( jQuery, wp.customize ) );
