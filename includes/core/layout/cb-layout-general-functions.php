<?php
/**
 * PS SocialPortal Layout Functions.
 *
 * @package    PS_SocialPortal
 * @subpackage Core
 * @copyright  Copyright (c) 2018, DerN3rd
 * @license    https://www.gnu.org/licenses/gpl.html GNU Public License
 * @author     DerN3rd
 */

// Do not allow direct access over web.
defined( 'ABSPATH' ) || exit;

/**
 * Get all available Global Theme layouts.
 *
 * @return array
 */
function cb_get_global_layouts() {

	$url = CB_THEME_URL . '/includes/customizer/assets/images/layouts/';

	$layouts = array(
		'layout-single-col'            => array(
			'url'   => $url . '1col.png',
			'label' => _x( 'Einzelne Spalte', 'Layout name in metabox', 'social-portal' ),
		),
		'layout-two-col-right-sidebar' => array(
			'url'   => $url . '2cr.png',
			'label' => _x( 'Zweispaltig rechte Seitenleiste', 'Layout name in metabox', 'social-portal' ),
		),
		'layout-two-col-left-sidebar'  => array(
			'url'   => $url . '2cl.png',
			'label' => _x( 'Zweispaltig linke Seitenleiste', 'Layout name in metabox', 'social-portal' ),
		),
	);

	return $layouts;
}

/**
 * Get Possible layouts for page
 * Used in Page_Layout_Control. It can override global layout.
 *
 * @return array
 */
function cb_get_page_layouts() {

	$url = CB_THEME_URL . '/includes/customizer/assets/images/layouts/';

	$layouts = array(
		'default'                    => array(
			'url'   => $url . 'default.png',
			'label' => _x( 'Standard', 'Page layout name', 'social-portal' ),
		),
		'page-single-col'            => array(
			'url'   => $url . '1col.png',
			'label' => _x( 'Einzelne Spalte', 'Page layout name', 'social-portal' ),
		),
		'page-two-col-right-sidebar' => array(
			'url'   => $url . '2cr.png',
			'label' => _x( 'Zweispaltig rechte Seitenleiste', 'Page layout name', 'social-portal' ),
		),
		'page-two-col-left-sidebar'  => array(
			'url'   => $url . '2cl.png',
			'label' => _x( 'Zweispaltig linke Seitenleiste', 'Page layout name', 'social-portal' ),
		),
	);

	return $layouts;
}

/**
 * Print the theme layout css class
 */
function cb_theme_layout_class() {
	echo esc_attr( cb_get_theme_layout_class() );
}

/**
 * Get the theme layout css class
 *
 * @return string layout css class
 */
function cb_get_theme_layout_class() {
	return cb_get_theme_layout();
}

/**
 * Get theme layout.
 *
 * @return string
 */
function cb_get_theme_layout() {
	return apply_filters( 'cb_theme_layout', cb_get_option( 'theme-layout', 'layout-two-col-right-sidebar' ) );
}

/**
 * Get Page Header image dimensions
 *
 * @return array
 */
function cb_get_page_header_dimensions() {

	return apply_filters(
		'cb_page_header_dimensions',
		array(
			'width'  => 2000,
			'height' => 450,
		)
	);
}

/**
 * Print site page header class.
 *
 * @param string $classes css class list.
 */
function cb_page_header_class( $classes = '' ) {
	echo esc_attr( cb_get_page_header_class( $classes ) );
}

/**
 * Get the css classes for site page header area.
 *
 * @param string $classes extra css classes.
 *
 * @return string
 */
function cb_get_page_header_class( $classes = '' ) {

	$classes = cb_parse_class_list( $classes );
	if ( cb_get_page_header_image() ) {
		array_unshift( $classes, 'page-header-mask-enabled' );
	}

	$classes = apply_filters( 'cb_page_header_classes', $classes );

	if ( $classes ) {
		$classes = array_map( 'esc_attr', $classes );
	}

	return 'page-header ' . join( ' ', $classes );
}

/**
 * Print page layout class.
 *
 * @param string $classes css class list.
 */
function cb_site_container_class( $classes = '' ) {
	echo esc_attr( cb_get_page_layout_class( $classes ) );
}

/**
 * Get the layout for single pages, It overrides the template layout
 *
 * @param string $classes list of css classes.
 *
 * @return string
 */
function cb_get_page_layout_class( $classes = '' ) {

	$layout  = cb_get_page_layout();
	$classes = cb_parse_class_list( $classes );
	array_unshift( $classes, $layout );

	$classes = apply_filters( 'cb_page_layout_classes', $classes, $layout );
	$classes = array_map( 'esc_attr', $classes );

	return 'site-container ' . join( ' ', $classes );
}

/**
 * Get current page layout.
 *
 * @return string
 */
function cb_get_page_layout() {

	$layout = '';

	if ( cb_is_bp_active() && is_buddypress() ) {

		if ( bp_is_user() ) {
			// user profile layout.
			$layout = cb_get_option( 'bp-member-profile-layout' );
		} elseif ( bp_is_group_create() ) {
			// is it group create?
			$layout = cb_get_option( 'bp-create-group-layout' );
		} elseif ( bp_is_group() ) {
			$layout = cb_get_option( 'bp-single-group-layout' );
		} elseif ( bp_is_activity_directory() ) {
			$layout = cb_get_activity_dir_page_layout();
		} elseif ( bp_is_members_directory() ) {
			$layout = cb_bp_get_members_dir_page_layout();
		} elseif ( bp_is_groups_directory() ) {
			$layout = cb_get_groups_dir_page_layout();
		} elseif ( bp_is_register_page() ) {
			$layout = cb_bp_get_signup_page_layout();
		} elseif ( bp_is_activation_page() ) {
			$layout = cb_bp_get_activation_page_layout();
		} elseif ( bp_is_create_blog() ) {
			$layout = cb_get_option( 'bp-create-blog-layout' );
		} elseif ( bp_is_blogs_directory() ) {
			$layout = cb_get_blogs_dir_page_layout();
		} elseif ( is_singular() ) {
			$layout = _cb_get_singular_layout();
		}
	} elseif ( cb_is_wc_active() && is_woocommerce() ) {
		$wc_layout = cb_get_option( 'wc-page-layout' );
		if ( empty( $wc_layout ) ) {
			$wc_layout = 'default';
		}

		// shop.
		if ( is_shop() ) {
			$layout = _cb_get_singular_layout( wc_get_page_id( 'shop' ) );
		} elseif ( is_product_taxonomy() ) {
			$layout = cb_get_option( 'product-category-page-layout' );//_cb_get_singular_layout( $page_id );
		} elseif ( is_product() ) {
			$layout = _cb_get_singular_layout();
			// Single product page has a fallback layout.
			if ( empty( $layout ) || $layout == 'default' ) {
				$layout = cb_get_option( 'product-page-layout' );
			}
		} else { // all other wc pages like cart, my account, checkout, single product etc.
			$layout = _cb_get_singular_layout();
		}
		// If any of the layout is set to default, they inherit the woocommerce global layout.
		if ( empty( $layout ) || $layout == 'default' ) {
			$layout = $wc_layout;
		}
	} elseif ( is_front_page() ) {
		$layout = cb_get_option( 'home-layout' );
	} elseif ( is_singular() ) {
		$layout = _cb_get_singular_layout();
	} elseif ( is_archive() ) {
		$layout = cb_get_option( 'archive-layout' );
	} elseif ( is_search() ) {
		$layout = cb_get_option( 'search-layout' );
	} elseif ( is_404() ) {
		$layout = cb_get_option( '404-layout' );
	}

	$layout = apply_filters( 'cb_page_layout', $layout );

	// reset default.
	if ( 'default' === $layout || empty( $layout ) ) {
		$layout = 'page-layout-default';
	}

	return $layout;
}

/**
 * Print site page content class.
 *
 * @param string $classes css class list.
 */
function cb_site_content_class( $classes = '' ) {
	echo esc_attr( cb_get_site_content_class( $classes ) );
}

/**
 * Get the css classes for site content area.
 *
 * @param string $classes extra css classes.
 *
 * @return string
 */
function cb_get_site_content_class( $classes = '' ) {

	$current_classes = '';
	if ( function_exists( 'buddypress' ) && ( bp_is_user() || bp_is_group() ) ) {
		$current_classes = 'site-content-bp site-content-single-' . bp_current_component();
	} elseif ( is_singular() ) {
		$current_classes = 'site-content-single site-content-single-' . get_post_type();
	} elseif ( is_search() ) {
		$current_classes = 'site-content-search';
	} elseif ( is_archive() ) {
		$current_classes = 'site-content-archive';
	}

	$classes = cb_parse_class_list( $classes );

	if ( $current_classes ) {
		array_unshift( $classes, $current_classes );
	}

	$classes = apply_filters( 'cb_site_content_classes', $classes, $current_classes );
	$classes = array_map( 'esc_attr', $classes );

	return 'site-content ' . join( ' ', $classes );
}

/**
 * Get the class applied based on current active footer widgetized area
 *
 * @return string
 */
function cb_get_footer_widget_wrapper_class() {

	$sidebars = array(
		'footer-1',
		'footer-2',
		'footer-3',
		'footer-4',
	);

	$count = 0;

	foreach ( $sidebars as $sidebar ) {

		if ( is_active_sidebar( $sidebar ) ) {
			$count ++;
		}
	}

	// widget-cols-1, widget-cols-2,3,4.
	return 'widget-cols-' . $count;
}

/**
 * Check if current site header support a feature(element).
 *
 * @param string $feature_id feature element name.
 *
 * @return bool
 */
function cb_site_header_supports( $feature_id ) {
	$presets = cb_get_all_registered_site_header_row_presets();
	// Presets must be registered.
	if ( empty( $presets ) ) {
		return false;
	}

	$header_rows = array( 'top', 'main', 'bottom' );
	// Find preset for enabled rows
	// and check if any of those preset support the feature.
	foreach ( $header_rows as $row ) {
		if ( ! cb_is_site_header_row_enabled( $row ) ) {
			continue;
		}

		$enabled_preset = cb_get_option( "site-header-row-{$row}-preset" );
		if ( empty( $enabled_preset ) || ! isset( $presets[ $enabled_preset ] ) ) {
			continue;
		}

		$preset = $presets[ $enabled_preset ];

		if ( empty( $preset['supports'] ) ) {
			continue;
		}

		if ( in_array( $feature_id, $preset['supports'], true ) ) {
			return true;
		}
	}

	return false;
}

/**
 * Register a site header preset.
 *
 * A Preset is what defines a header row.
 *
 * @param string $id preset id.
 * @param array  $args preset args.
 *
 * @return bool
 */
function cb_register_site_header_row_preset( $id, $args ) {
	$store   = social_portal()->store;
	$presets = $store->get( 'site_header_row_presets' );
	if ( empty( $presets ) ) {
		$presets = array();
	}

	$args = wp_parse_args(
		$args,
		array(
			'label'    => '',
			'url'      => '',
			'callback' => '',
			'supports' => array(), // ('feature id').
			'rows'     => array(), // supported rows('top', 'bottom', 'main').
		)
	);

	// Validate.
	if ( empty( $id ) || empty( $args['url'] ) || empty( $args['callback'] ) || ! is_callable( $args['callback'] ) ) {
		return false;
	}

	$presets[ $id ] = $args;
	$store->set( 'site_header_row_presets', $presets );
	return true;
}

/**
 * Get registered site header presets.
 *
 * @return array
 */
function cb_get_all_registered_site_header_row_presets() {
	$presets = social_portal()->store->get( 'site_header_row_presets' );
	return empty( $presets ) ? array() : $presets;
}

/**
 * Get the supported presets for the given row.
 *
 * @param string $row row name.('top', 'main', 'bottom').
 *
 * @return array
 */
function cb_get_site_header_row_presets( $row ) {
	$presets = cb_get_all_registered_site_header_row_presets();

	if ( empty( $presets ) ) {
		return array();
	}

	$supported = array();

	foreach ( $presets as $key => $preset ) {
		if ( empty( $preset['rows'] ) || in_array( $row, $preset['rows'], true ) ) {
			$supported[ $key ] = $preset;
		}
	}

	return $supported;
}

/**
 * Site Header Renderer.
 *
 * Renders rows of the site header.
 */
function cb_site_header_renderer() {

	$rows = cb_get_option( 'site-header-rows', array() );

	$priority = 10;

	foreach ( $rows as $row ) {
		if ( ! cb_is_site_header_row_available( $row ) ) {
			continue;
		}

		$row_callback = 'cb_site_header_row_' . $row;

		if ( ! function_exists( $row_callback ) ) {
			continue;
		}

		add_action( 'cb_site_header', $row_callback, $priority );
		$priority += 10;
	}
}

/**
 * Render Site Header Row contents.
 *
 * @param string $row row name(top,main,bottom).
 */
function cb_site_header_row_contents_render_enable( $row ) {
	$callback = cb_get_site_header_row_preset_renderer( cb_get_option( "site-header-row-{$row}-preset" ), $row );

	// cb_site_header_row_top.
	// cb_site_header_row_main.
	// cb_site_header_row_bottom.
	$row_action = "cb_site_header_row_{$row}";
	if ( is_callable( $callback ) ) {
		call_user_func( $callback, $row_action, $row );
	}
}

/**
 * Get the Preset Rendering callback.
 *
 * @param string $preset preset.
 * @param string $row row(top,main,bottom).
 *
 * @return string
 */
function cb_get_site_header_row_preset_renderer( $preset, $row = '' ) {

	$presets  = cb_get_all_registered_site_header_row_presets();
	$callback = '';
	if ( $presets && isset( $presets[ $preset ] ) ) {
		$callback = $presets[ $preset ]['callback'];
	}

	return apply_filters( 'cb_site_header_row_preset_render_callback', $callback, $preset, $row );
}

/**
 * Get layout for single post/post type page
 *
 * @internal for internal use.
 *
 * @param int $post_id post id.
 *
 * @return string
 */
function _cb_get_singular_layout( $post_id = 0 ) {

	if ( ! $post_id ) {
		$post_id = get_queried_object_id();
	}

	$available_layouts = cb_get_registered_page_layout_classes();
	$key               = cb_get_page_layout_meta_key();

	$layout_current = get_post_meta( $post_id, $key, true );

	if ( ! $layout_current ) {
		$layout_current = 0; // default.
	}

	$layout = isset( $available_layouts[ $layout_current ] ) ? $available_layouts[ $layout_current ] : '';

	return $layout;
}

/**
 * Get the Page template for the given post
 *
 * @param int $post_id post id.
 *
 * @return bool|mixed|string
 */
function cb_get_page_template_slug( $post_id = null ) {

	$post = get_post( $post_id );
	if ( ! $post ) {
		return false;
	}

	$template = get_post_meta( $post->ID, '_wp_page_template', true );

	if ( ! $template || 'default' === $template ) {
		return '';
	}

	return $template;
}

/**
 * Get panel visibility
 *
 * @param string $panel panel name 'left'|'right'.
 *
 * @return mixed|string
 */
function cb_get_panel_visibility( $panel ) {

	$visibility = '';
	$user_scope = '';

	// _cb_panel_left_visibility
	// _cb_panel_right_visibility.
	$visibility_key = "_cb_panel_{$panel}_visibility";

	// _cb_panel_left_user_scope
	// _cb_panel_right_user_scope.
	$user_scope_key = "_cb_panel_{$panel}_user_scope";

	// IDE Help,
	// panel-left-user-scope
	// panel-right-user-scope.
	$user_scope_option_name = "panel-{$panel}-user-scope";
	// panel-right-visibility
	// panel-left-visibility.
	$visibility_option_name = "panel-{$panel}-visibility";
	if ( is_singular() ) {
		$visibility = get_post_meta( get_queried_object_id(), $visibility_key, true );
		$user_scope = get_post_meta( get_queried_object_id(), $user_scope_key, true );
	}

	// If visibility is not specified use, default.
	if ( ! $visibility ) {
		$visibility = cb_get_option( $visibility_option_name );
	}

	if ( ! $user_scope ) {
		$user_scope = cb_get_option( $user_scope_option_name );
	}

	// If the User scope is 'logged-in' & User is not logged in,
	// or the scope is 'logged-out' and user is logged in, reset visibility to none.
	if ( ( 'logged-in' === $user_scope && ! is_user_logged_in() ) || ( 'logged-out' === $user_scope && is_user_logged_in() ) ) {
		// reset panel visibility.
		$visibility = 'none'; // never show it.
	}
	// back compat.
	if ( 'never' === $visibility ) {
		$visibility = 'none';
	}

	return apply_filters( 'cb_panel_visibility', $visibility, $user_scope, $panel );
}

/**
 * Get the meta key used for page layout
 *
 * @return string
 */
function cb_get_page_layout_meta_key() {
	return '_cb_page_layout_type';
}

/**
 * Get Registered page layout css classes.
 * We will use it to generate class for the content div
 *
 * @return array( layout-name=>css-class name)
 */
function cb_get_registered_page_layout_classes() {

	return array(
		0                            => '', // default.
		'page-single-col'            => 'page-single-col', // single column page.
		'page-two-col-right-sidebar' => 'page-two-col-right-sidebar',
		'page-two-col-left-sidebar'  => 'page-two-col-left-sidebar', // left sidebar.
	);
}
