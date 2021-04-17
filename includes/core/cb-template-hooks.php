<?php
/**
 * PS SocialPortal Template Hooks.
 *
 * @package    PS_SocialPortal
 * @subpackage Core
 * @copyright  Copyright (c) 2018, DerN3rd
 * @license    https://www.gnu.org/licenses/gpl.html GNU Public License
 * @author     DerN3rd
 */

// Do not allow direct access over web.
defined( 'ABSPATH' ) || exit;

/***
 * General hooks for controlling behaviour/display customizations in WordPress
 */

if ( ! function_exists( 'cb_pingback_header' ) ) :
	/**
	 * Adds a pingback url auto-discovery header for single articles.
	 */
	function cb_pingback_header() {
		if ( is_singular() && pings_open() ) {
			printf( '<link rel="pingback" href="%s">' . "\n", get_bloginfo( 'pingback_url' ) );
		}
	}
endif;
add_action( 'wp_head', 'cb_pingback_header' );

/**
 * Adds DNS preconnect for the google fonts and font awesome.
 *
 * @param array  $hints hinds.
 * @param string $relation_type relation type.
 *
 * @return array
 */
function cb_resource_hints( $hints, $relation_type ) {
	if ( 'dns-prefetch' === $relation_type ) {
		$hints[] = '//fonts.googleapis.com';
		$hints[] = '//stackpath.bootstrapcdn.com';
	} else if ( 'prerender' === $relation_type ) {
	}

	return $hints;
}

add_filter( 'wp_resource_hints', 'cb_resource_hints', 10, 2 );
/***
 * TOC
 *
 * 2. Show Home in Main Menu?
 * 3. Filter Single Page template to use custom page template
 * 4. Feed Redirection
 * 6. Excerpt Length( 40 words default)
 * 7. Read More title filter
 * 8. Javascript Detection
 * 9. Sidebar Login Redirection setup
 * 10. Enable Shortcodes in text widget
 */

/**
 * Makes our wp_nav_menu() fallback, cb_main_nav(), to show a home link.
 *
 * @param array $args Default values for wp_page_menu().
 *
 * @see wp_page_menu()
 * @return array
 */
function cb_show_home_in_menu( $args ) {

	if ( cb_get_option( 'show-home-in-menu' ) ) {
		$args['show_home'] = true;
	}

	return $args;
}

add_filter( 'wp_page_menu_args', 'cb_show_home_in_menu' );

/**
 * Filters Single page template for the posts and other post types
 *  We could have also used 'template_include' instead to filter
 *
 * @param string $template template file.
 *
 * @return string
 */
function cb_filter_single_template( $template ) {

	$object = get_queried_object();

	$page_template = cb_get_page_template_slug( $object );

	if ( empty( $page_template ) ) {
		return $template;
	}

	$templates = array();

	if ( $page_template && 0 === validate_file( $page_template ) ) {
		$templates[] = $page_template;
	}

	if ( ! empty( $object->post_type ) ) {
		$templates[] = "single-{$object->post_type}-{$object->post_name}.php";
		$templates[] = "single-{$object->post_type}.php";
	}

	$templates[] = 'single.php';

	$template = locate_template( $templates );

	return $template;
}

add_filter( 'single_template', 'cb_filter_single_template' );

if ( ! function_exists( 'cb_feed_redirect' ) ) :
	/**
	 * Redirects Feed to the given custom feed url
	 */
	function cb_feed_redirect() {

		if ( ! is_feed() ) {
			return;
		}

		$feed_url = cb_get_option( 'custom-rss' );

		if ( empty( $feed_url ) ) {
			return;
		}

		if ( is_feed() && ! preg_match( '/feedburner|feedvalidator/i', $_SERVER['HTTP_USER_AGENT'] ) ) {
			wp_redirect( $feed_url, 302 );
			exit( 0 );
		}
	}

endif;
add_action( 'template_redirect', 'cb_feed_redirect' );

if ( ! function_exists( 'cb_set_default_excerpt_length' ) ) :
	/**
	 * Sets the post excerpt length to 40 words.
	 *
	 * @param int $length how many words.
	 */
	function cb_set_default_excerpt_length( $length ) {
		return 40;
	}
endif;

add_filter( 'excerpt_length', 'cb_set_default_excerpt_length' );

if ( ! function_exists( 'cb_filter_continue_reading_label' ) ) :
	/**
	 * Returns "Continue Reading" link for excerpts
	 */
	function cb_filter_continue_reading_label() {
		return '...';
	}

endif;
add_filter( 'excerpt_more', 'cb_filter_continue_reading_label' );

// add_filter( 'use_default_gallery_style', '__return_false' );

/**
 * Removes 'no-js' class from html element and adds 'js-enabled' class.
 */
function cb_javascript_detection() {
	echo "<script>(function(html){html.className = html.className.replace(/\bno-js\b/,'js-enabled')})(document.documentElement);</script>\n";
}

add_action( 'wp_head', 'cb_javascript_detection', 0 );
