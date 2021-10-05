<?php
/**
 * Template Tags
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
 * Check if the specific feature is visible in Page header(mostly used for meta, title etc).
 *
 * @param string $type type ( 'title', 'description', 'meta').
 * @param int    $post_id post id.
 *
 * @return bool
 */
function cb_show_in_page_header( $type, $post_id = 0 ) {
	static $header_items = null;

	if ( is_null( $header_items ) ) {
		if ( is_page() ) {
			$header_items = cb_get_option( 'page-page-header-items' );
		} elseif ( is_singular() ) {
			$header_items = cb_get_option( get_post_type() . '-page-header-items', cb_get_default( 'post-page-header-items' ) );
		} else {
			$header_items = cb_get_option( 'archive-page-header-items' );
		}
	}

	$show = $header_items && in_array( $type, $header_items, true );

	return apply_filters( 'cb_show_in_page_header', $show, $type, $header_items, $post_id );
}

/**
 * Show the content type in page header.
 *
 * @param string $type type ( 'title', 'description', 'meta').
 * @param int    $post_id post id.
 *
 * @return bool
 */
function cb_show_in_article_entry( $type, $post_id = 0 ) {
	static $header_items = null;

	if ( is_null( $header_items ) ) {
		if ( is_page() ) {
			$header_items = cb_get_option( 'page-article-items' );
		} elseif ( is_singular() ) {
			$header_items = cb_get_option( get_post_type() . '-article-items', cb_get_default( 'post-article-items' ) );
		} else {
			$header_items = cb_get_option( 'archive-article-items' );
		}
	}

	$show = $header_items && in_array( $type, $header_items, true );

	return apply_filters( 'cb_show_in_article_entry', $show, $type, $header_items, $post_id );
}

/**
 * Is post title visible
 * Checks whether the post title should be shown inside the <article> tag.
 * When the Page header is visible, It is off by default
 *
 * @param int $post_id post id.
 *
 * @return boolean
 */
function cb_is_post_title_visible( $post_id = 0 ) {
	$visible = cb_show_in_article_entry( 'title', $post_id );

	if ( ! $post_id ) {
		$post_id = get_queried_object_id();
	}

	if ( ! $visible && ( is_single( $post_id ) || is_page( $post_id ) ) && ! cb_is_page_header_enabled() ) {
		$visible = true;
	}

	return apply_filters( 'cb_post_title_visible', $visible, $post_id );
}

if ( ! function_exists( 'cb_get_comment_count' ) ) :
	/**
	 * Get the appropriate comment count message
	 *
	 * @return string
	 */
	function cb_get_comment_count() {

		$comment_count = get_comments_number();

		if ( comments_open() || $comment_count > 0 ) {
			return get_comments_number_text( __( '0 Kommentare', 'social-portal' ), __( '1 Kommentar', 'social-portal' ), __( '%s Kommentare', 'social-portal' ) );
		} else {
			return ''; // __( 'Comments are closed', 'social-portal' )
		}
	}

endif;

if ( ! function_exists( 'cb_get_article_entry_header_meta' ) ) :
	/**
	 * Should we show the post meta inside article content area.
	 *
	 * @param  string $context loop context.
	 */
	function cb_get_article_entry_header_meta( $context = '' ) {
		return cb_show_in_article_entry( 'meta' ) ? cb_get_article_header_meta( $context ) : '';
	}

endif;

if ( ! function_exists( 'cb_get_article_entry_footer_meta' ) ) :
	/**
	 * Should we show the post meta inside article content area.
	 *
	 * @param  string $context loop context.
	 */
	function cb_get_article_entry_footer_meta( $context = '' ) {
		return cb_get_article_footer_meta( $context );
	}

endif;

if ( ! function_exists( 'cb_get_article_header_meta' ) ) :

	/**
	 * Get post entry header meta
	 *
	 * @param string $context the context. Possible values 'single', 'loop'.
	 *
	 * @return string
	 */
	function cb_get_article_header_meta( $context = '' ) {

		$meta = array();

		if ( empty( $context ) ) {
			$context = is_singular() ? 'single' : 'loop';
		}

		if ( 'single' === $context ) {
			$post_type = get_post_type();
			$meta      = cb_get_option( $post_type . '-header-meta', cb_get_default( 'post-header-meta' ) );
		} elseif ( 'loop' === $context ) {
			$meta = cb_get_option( 'archive-post-header-meta', cb_get_default( 'archive-post-header-meta' ) );
		}

		return apply_filters( 'cb_article_header_meta', cb_generate_article_meta( $meta, 'header', $context ), $meta, $context );
	}

endif;


if ( ! function_exists( 'cb_get_article_footer_meta' ) ) :
	/**
	 * Get post entry footer meta
	 *
	 * @param string $context context.
	 *
	 * @return string
	 */
	function cb_get_article_footer_meta( $context = '' ) {

		$meta = array();

		if ( empty( $context ) ) {
			$context = is_singular() ? 'single' : 'loop';
		}

		if ( 'single' === $context ) {
			$post_type = get_post_type();
			$meta      = cb_get_option( $post_type . '-footer-meta', cb_get_default( 'post-footer-meta' ) );
		} elseif ( 'loop' === $context ) {
			$meta = cb_get_option( 'archive-post-footer-meta', cb_get_default( 'archive-post-footer-meta' ) );
		}

		return apply_filters( 'cb_article_footer_meta', cb_generate_article_meta( $meta, 'footer', $context ), $meta, $context );
	}

endif;

if ( ! function_exists( 'cb_generate_article_meta' ) ) :
	/**
	 * Generate post meta from the settings.
	 *
	 * @param array  $options enabled meta options.
	 * @param string $location Meta location. Possible values 'header', 'footer'.
	 * @param string $context Meta context. Possible values 'loop', 'single'.
	 */
	function cb_generate_article_meta( $options, $location = 'header', $context = '' ) {

		$html = array();

		if ( empty( $options ) ) {
			$options = array();
		}

		foreach ( $options as $meta_type ) {
			$meta_value = '';
			switch ( $meta_type ) {
				case 'author':
					global $authordata;
					$user = $authordata;

					if ( is_object( $authordata ) ) {
						$user = $authordata;
					} elseif ( is_single() ) {
						$user = get_user_by( 'id', get_post_field( 'post_author', get_queried_object() ) );
					}

					if ( ! $user ) {
						break;
					}

					$url        = get_author_posts_url( $user->ID, $user->user_nicename );
					$meta_value = sprintf( '<a href="%1$s"><span class="entry-meta-item-user-avatar">%2$s</span>%3$s</a>', $url, get_avatar( $user->ID, 32 ), get_the_author_meta( 'display_name', $user->ID ) );
					break;

				case 'post-date':
					$meta_value = get_the_date();
					break;

				case 'categories':
					$meta_value = get_the_category_list( ', ' );
					break;

				case 'tags':
					$meta_value = get_the_tag_list( '', ', ', '' );
					break;

				case 'comments':
					$comment_text = cb_get_comment_count();
					if ( $comment_text ) {
						$meta_value = sprintf( '<a href="%1$s"><i class="fa fa-comment-o"></i> <span class="comment-number">%2$s</span></a>', get_comments_link(), $comment_text );
					}
					break;

			}

			$meta_value = apply_filters( 'cb_post_meta_value', $meta_value, $meta_type, $location, $context );

			if ( ! empty( $meta_value ) ) {
				$html[] = sprintf( '<span class="entry-meta-item entry-meta-item-%1$s">%2$s</span>', esc_attr( $meta_type ), $meta_value );
			}
		}

		$separator = cb_get_post_meta_separator();

		$html = apply_filters( 'cb_entry_meta_items', $html, $options, $location, $context );

		$url = get_edit_post_link( get_the_ID() );

		if ( $url ) {
			$html[] = sprintf( '<span class="entry-meta-item entry-meta-item-edit-link"><a class="edit-link post-edit-link" href="%1$s" title="%2$s"><i class="fa fa-pencil-square"></i><span class="fa-text-hidden">%2$s</span></a></span>', esc_url( $url ), __( 'Bearbeiten', 'social-portal' ) );
		}

		return join( $separator, $html );
	}
endif;

if ( ! function_exists( 'cb_get_post_meta_separator' ) ) :
	/**
	 * Get post meta separator.
	 *
	 * @return string
	 */
	function cb_get_post_meta_separator() {
		return '<span class="entry-meta-separator">|</span>';
	}

endif;

if ( ! function_exists( 'cb_show_post_thumbnail' ) ) :
	/**
	 * Should we show the post thumbnail?
	 *
	 * @return bool
	 */
	function cb_show_post_thumbnail() {

		$options = array();

		if ( post_password_required() || is_attachment() || ! has_post_thumbnail() ) {
			$options = array();
		} elseif ( is_page() ) {
			$options = cb_get_option( 'page-article-items', array() );
		} elseif ( is_singular() ) {
			$options = cb_get_option( get_post_type() . '-article-items', cb_get_default( 'post-article-items' ) );
		} elseif ( ! is_singular() ) {
			$options = cb_get_option( 'archive-article-items', array() );
		}

		return apply_filters( 'cb_show_post_thumbnail', $options && in_array( 'featured-image', $options, true ) );
	}

endif;

if ( ! function_exists( 'cb_post_thumbnail' ) ) :

	/**
	 * Functions used to generate html content and used inside the theme as template tags
	 **/

	/**
	 * Prints the html code for post thumbnail
	 *
	 * @param string $size size.
	 */
	function cb_post_thumbnail( $size = '' ) {

		if ( ! cb_show_post_thumbnail() ) {
			return;
		}

		// $thumb_size = $size;

		// if size is not given, let us try o guess it.
		if ( empty( $size ) && is_singular() ) {
			if ( cb_is_sidebar_enabled() ) {
				$size = 'cb-featured-regular'; // 800.
			} else {
				$size = 'cb-featured-regular-full'; // 1200.
			}
		} elseif ( empty( $size ) ) {
			// it is archive page.
			$display = cb_get_posts_display_type();
			if ( 'masonry' === $display ) {
				$size = 'thumbnail';
			} elseif ( cb_is_sidebar_enabled() ) {
				$size = 'cb-featured-regular';
			} else {
				$size = 'cb-featured-regular-full';
			}
		}
		// let theme use thumbnail and then convert to internal.
		if ( 'thumbnail' === $size ) {
			$size = 'post-thumbnail';
		}

		?>
		<?php if ( is_singular() ) : ?>
            <div class="post-thumbnail post-thumbnail-single">
				<?php the_post_thumbnail( $size ); ?>
            </div><!-- .post-thumbnail -->
		<?php else : ?>
            <div class="post-thumbnail post-thumbnail-loop">

                <a href="<?php the_permalink(); ?>">
					<?php the_post_thumbnail( $size, array( 'alt' => get_the_title() ) ); ?>
                </a>

            </div>
		<?php endif; // End is_singular(). ?>
		<?php
	}

endif;

if ( ! function_exists( 'cb_posts_pagination' ) ) :
	/**
	 * Prints the Generated Pagination links(for archive pages/post loops)
	 *
	 * @param array $args before and after markup.
	 */
	function cb_posts_pagination( $args = array() ) {

		$pagination = get_the_posts_pagination(
			apply_filters(
				'cb_posts_pagination_args',
				array(
					'prev_text'          => '<i class="fa fa-arrow-circle-left"></i>',
					'next_text'          => '<i class="fa fa-arrow-circle-right"></i>',
					'before_page_number' => '',
					// '<span class="meta-nav screen-reader-text">' . esc_html__( 'Page', 'social-portal' ) . ' </span>',
					'type'               => 'list',
				)
			)
		);

		if ( empty( $pagination ) ) {
			return;
		}

		$args = wp_parse_args(
			$args,
			array(
				'before' => '<div class="clearfix pagination-wrapper posts-pagination-wrapper">',
				'after'  => '</div>',
			)
		);

		echo $args['before'] . $pagination . $args['after'];
	}

endif;


if ( ! function_exists( 'cb_post_navigation' ) ) :
	/**
	 * Prints the Prev/Next for single posts.
	 */
	function cb_post_navigation() {

		the_post_navigation(
			apply_filters(
				'cb_post_navigation_args',
				array(
					'prev_text' => '<i class="fa fa-arrow-circle-left"></i> %title',
					'next_text' => '%title <i class="fa fa-arrow-circle-right"></i>',
				)
			)
		);
	}

endif;

if ( ! function_exists( 'cb_is_breadcrumb_plugin_active' ) ) :
	/**
	 * Check if we have a breadcrumb plugin active?
	 *
	 * @return bool
	 */
	function cb_is_breadcrumb_plugin_active() {

		$is_enabled = 0;
		if ( function_exists( 'bcn_display' ) ) {
			$is_enabled = true;
		} elseif ( function_exists( 'breadcrumb_trail' ) ) {
			$is_enabled = 1;
		}

		return apply_filters( 'cb_breadcrumb_plugin_active', $is_enabled );
	}

endif;

/**
 * @todo add template control to enable/disable it
 * Checks if the plugins for breadcrumb exists or not?
 *
 * @return bool
 */
function cb_is_breadcrumb_enabled() {

	$is_enabled = false;

	if ( cb_is_breadcrumb_plugin_active() ) {
		$is_enabled = true;
	}

	return apply_filters( 'cb_breadcrumb_enabled', $is_enabled );
}

if ( ! function_exists( 'cb_breadcrumb' ) ) :
	/**
	 * Generate breadcrumb links
	 */
	function cb_breadcrumb() {

		if ( function_exists( 'bcn_display' ) ) {
			bcn_display();
		} elseif ( function_exists( 'breadcrumb_trail' ) ) {
			breadcrumb_trail( cb_get_breadcrumb_args() );
		} elseif ( cb_is_psf_active() && is_psforum() ) {
			psf_breadcrumb(); // worst case, always show psf breadcrumb.
		} elseif ( cb_is_wc_active() && is_woocommerce() ) {
			woocommerce_breadcrumb();
		}

		do_action( 'cb_breadcrumb' );
	}

endif;

/**
 * Get args for breadcrumb trail plugin
 *
 * @return array
 */
function cb_get_breadcrumb_args() {

	$args = array(
		'before'        => false,
		'separator'     => ' /',
		'show_on_front' => false,
		'show_browse'   => false,
	);

	return apply_filters( 'cb_breadcrumb_args', $args );
}

/**
 * Load breadcrumb template
 */
function cb_load_breadcrumbs() {
	get_template_part( 'template-parts/breadcrumb' );
}

/**
 * Load Feedback template
 */
function cb_load_site_feedback_message() {
	get_template_part( 'template-parts/site-feedback-message' );
}

/**
 * Nav menu.
 *
 * For wp_nav_menu() callback from the main navigation in header.php
 *
 * Used when the custom menus haven't been configured.
 *
 * @param array Menu arguments from wp_nav_menu().
 *
 * @see wp_nav_menu()
 */
function cb_main_nav( $args ) {

	$pages_args = array(
		'depth'    => 1,
		'echo'     => false,
		'exclude'  => '',
		'title_li' => '',
	);

	$menu = wp_page_menu( $pages_args );

	$classes = esc_attr( $args['menu_class'] );
	$id      = esc_attr( $args['menu_id'] );
	$menu = str_replace(
		array(
			'<div class="menu"><ul>',
			'</ul></div>',
		),
		array(
			"<div id='{$id}' class='{$classes}'><ul class='nav-list'>",
			'</ul></div><!-- #nav -->',
		),
		$menu
	);
	echo $menu; // WPCS: XSS ok.
}

/**
 * Get the current selected display style for the archive posts
 *
 * @return string masonry|standard
 */
function cb_get_posts_display_type() {

	$display_type = cb_get_option( 'archive-posts-display-type' );

	if ( is_front_page() ) {
		$display_type = cb_get_option( 'home-posts-display-type' );
	} elseif ( is_search() ) {
		$display_type = cb_get_option( 'search-posts-display-type' );
	}

	return apply_filters( 'cb_posts_display_type', $display_type );
}

/**
 * Get no. of cols per row for the current displayed list
 *
 * @return int
 */
function cb_get_posts_list_column_count() {

	$key = 'archive-posts-per-row';

	if ( is_front_page() ) {
		$key = 'home-posts-per-row';
	} elseif ( is_search() ) {
		$key = 'search-posts-per-row';
	}

	return apply_filters( 'cb_posts_per_row', cb_get_option( $key ), $key );
}

/**
 * Is post list using masonry.
 */
function cb_is_posts_list_using_masonry() {

	if ( cb_get_posts_display_type() === 'masonry' ) {
		return true;
	}

	return false;
}

/**
 * Is search page using masonry.
 *
 * @return bool
 */
function cb_is_search_page_using_masonry() {
	return cb_get_option( 'search-posts-display-type' ) === 'masonry';
}

/**
 * Is archive page using masonry.
 *
 * @return bool
 */
function cb_is_archive_page_using_masonry() {
	return cb_get_option( 'archive-posts-display-type' ) === 'masonry';
}

/**
 * Is home page using masonry.
 *
 * @return bool
 */
function cb_is_home_page_using_masonry() {
	return cb_get_option( 'home-posts-display-type' ) === 'masonry';
}

/**
 * Are social icons enabled in header.
 *
 * @return bool
 */
function cb_is_header_social_icons_enabled() {
	return (bool) cb_get_option( 'header-show-social' );
}

/**
 * Are social icons enabled in footer.
 *
 * @return int
 */
function cb_is_footer_social_icons_enabled() {
	return (bool) cb_get_option( 'footer-show-social' );
}

/**
 * Print Post entry class based on display type.
 */
function cb_post_display_class() {
	echo cb_get_post_display_class(); // WPCS: XSS ok.
}

/**
 * Get css class for the post entry
 *
 * @param string $class css class.
 *
 * @return string
 */
function cb_get_post_display_class( $class = '' ) {

	if ( is_archive() || is_front_page() || is_search() ) {
		$display_type = cb_get_posts_display_type();
		$class       .= ' post-display-type-' . $display_type;

		if ( 'masonry' === $display_type ) {
			// archive-posts-per-row.
			$class .= ' ' . cb_get_item_grid_class( cb_get_posts_list_column_count() );
		}
	}

	return $class;
}

/**
 * Pint class List.
 *
 * @param string $class class names.
 */
function cb_post_list_class( $class = '' ) {
	echo cb_get_posts_list_class( $class ); // WPCS: XSS ok.
}

/**
 * Get the css class list to apply on posts list
 *
 * @param string $class css class.
 *
 * @return string list of css classes to apply
 */
function cb_get_posts_list_class( $class = '' ) {

	$classes = array();
	// let us take the archive layout as default.
	$display_type = cb_get_posts_display_type();

	$classes[] = 'item-list posts-list posts-display-' . $display_type;

	if ( 'masonry' === $display_type || 'grid' === $display_type ) {
		$classes[] = 'row'; // add bs row.
	}

	$class = cb_parse_class_list( $class );

	if ( $class ) {
		$classes = array_merge( $classes, $class );
	}

	$classes = apply_filters( 'cb_posts_list_css_classes', $classes, $display_type, $class );
	$classes = array_map( 'esc_attr', $classes );

	return join( ' ', $classes );
}


/**
 * How many columns per row for the context
 *
 * @param string $context context.
 *
 * @return string
 */
function cb_get_item_grid_cols( $context ) {

	$key  = $context . '-per-row';
	$cols = cb_get_option( $key, cb_get_default( $key ) );

	if ( empty( $cols ) ) {
		$cols = 'auto';
	}

	$cols = apply_filters( 'cb_item_grid_cols', $cols, $context );

	return $cols;
}

/**
 * Generates appropriate col-xyz-class names based on the given column count
 *
 * @param int $cols column count.
 *
 * @return string
 */
function cb_get_item_grid_class( $cols ) {
	// do not allow mischief.
	if ( intval( $cols ) <= 0 ) {
		return '';
	}

	$grid_class_suffix = absint( 12 / $cols );
	// we have a 12 col grid, we divide that by col count to get the grid class.
	// for md/lg we care, for rest we don't.
	$classes = "col-xs-12 col-sm-6 col-md-{$grid_class_suffix} col-lg-{$grid_class_suffix}";

	return apply_filters( 'cb_item_grid_class', $classes, $cols );
}

/**
 * Get the button class after applying filter.
 *
 * @param string $context button context.
 * @param string $classes button classes.
 *
 * @return string
 */
function cb_get_button_class( $context, $classes ) {
	return apply_filters( 'cb_btn_classes', $classes, $context );
}

/**
 * Get the contents to be shown in the footer copyright section
 *
 * @return mixed
 */
function cb_get_footer_copyright() {

	$content = cb_get_option( 'footer-text' );
	$content = str_replace( '[current-year]', date( 'Y' ), $content );

	return apply_filters( 'cb_footer_copyright_contents', $content );
}
