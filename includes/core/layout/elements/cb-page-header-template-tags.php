<?php
/**
 * Page header blocks.
 *
 * @package PS_SocialPortal
 */

// Do not allow direct access over web.
defined( 'ABSPATH' ) || exit;

/**
 * Load the main page header section
 *
 * Locates the template from current child theme/Theme use filter to override the loaded template part
 */
function cb_load_page_header() {
	if ( cb_is_page_header_enabled() ) {
		$template = apply_filters( 'cb_page_header_template_part', 'template-parts/page-header.php' );
		$located  = apply_filters( 'cb_page_header_template', locate_template( (array) $template, false, false ) );

		if ( is_readable( $located ) ) {
			load_template( $located );
		}
	}
}

/**
 * Injects header background image for posts/blog/directory section which is later processed by imgLiquid plugin to
 *  have a nice looking header
 *
 * @return string
 */
function cb_get_page_header_image() {
	$object_id = get_queried_object_id();
	$image_url = '';

	// is term archive?
	if ( is_singular() ) {
		// Look for specific.
		$image_url = get_post_meta( $object_id, 'cb-header-image', true );
		if ( ! $image_url && cb_get_option( 'use-post-thumbnail-in-page-header' ) && has_post_thumbnail( $object_id ) ) {
			$image_url = get_the_post_thumbnail_url( $object_id, 'cb-featured-page-header' );
		}
	} elseif ( is_category() || is_tax() ) {
		$image_url = get_term_meta( $object_id, 'cb-header-image', true );

		// fallback.
		if ( ! $image_url ) {
			$image_url = get_theme_mod( 'archive-header-image' );
		}
	} elseif ( is_404() ) {
		$image_url = get_theme_mod( '404-header-image' );
	} elseif ( is_search() ) {
		$image_url = get_theme_mod( 'search-header-image' );
	} elseif ( is_archive() ) {// archive fallback(including author, in future differentiate author).
		$image_url = get_theme_mod( 'archive-header-image' );
	}

	// fallback.
	if ( empty( $image_url ) ) {
		$image_url = get_header_image();
	}

	$image_url = apply_filters( 'cb_custom_header_image_url', $image_url );

	return $image_url;
}

/**
 * Page Header contents.
 *
 * @return array() ith keys('title', 'description', 'meta')
 */
function cb_get_page_header_contents() {
	$title       = '';
	$description = '';
	$meta        = '';

	if ( is_singular() ) {
		$title       = get_the_title();
		$description = '';
		if ( cb_show_in_page_header( 'meta' ) ) {
			$meta = cb_get_article_header_meta( 'single' );
		}
	} elseif ( is_search() ) {
		/* translators: %s: search term */
		$title = sprintf( esc_html__( 'Suchergebnisse für: %s', 'social-portal' ), '<span class="search-term">' . esc_html( get_search_query() ) . '</span>' );
	} elseif ( is_archive() ) {
		$title       = get_the_archive_title();
		$description = get_the_archive_description();
	} elseif ( is_404() ) {
		$description = '<i class="fa fa-user-secret"></i>' . __( '404, Inhalt nicht gefunden!', 'social-portal' );
	}

	return apply_filters(
		'cb_page_header_contents',
		array(
			'title'       => $title,
			'description' => $description,
			'meta'        => $meta,
		)
	);
}
