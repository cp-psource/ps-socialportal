<?php
/**
 * Page Building blocks.
 *
 * These are page element block
 * You can attach them to various Community+ template actions to build layouts
 *
 * See /includes/builder.php to see how the default layout is generated.
 *
 * @package PS_SocialPortal
 */

// Do not allow direct access over web.
defined( 'ABSPATH' ) || exit;

/**
 * 1. Site Logo : cb_site_branding()
 * 2. Offcanvas toggle left : cb_offcanvas_toggle_left()
 * 3. Offcanvas toggle right : cb_offcanvas_toggle_right()
 * 4. Primary Menu : cb_primary_menu()
 * 5. Search Form : cb_search_form()
 * 6. Login/Account Links: cb_login_account_links()
 * 7. Social Links: cb_social_links()
 */

/**
 * Logo block
 *
 * Generate Logo/Logo text
 */
function cb_site_branding() {
	$class = has_custom_logo() ? 'has-custom-logo' : '';
	$class .= cb_has_mobile_logo() ? ' has-mobile-logo' : '';

    ?>
    <!-- site logo -->
    <div id="site-branding" data-site-name="<?php bloginfo( 'name' ); ?>" class="site-branding <?php echo esc_attr( $class ); ?>">
		<?php cb_site_branding_content(); ?>

    </div><!-- end of site logo section -->
	<?php
}

/**
 * Site branding content.
 */
function cb_site_branding_content() {
	echo get_custom_logo(); // WPCS: XSS ok.
	echo cb_get_mobile_logo();// WPCS: XSS ok.
	cb_sitename_text();
	if ( get_theme_mod( 'show-tagline' ) ) {
		echo cb_get_site_description(); // WPCS: XSS ok.
	}
}

/**
 * Print the toggles for offcanvas.
 */
function cb_offcanvas_toggles() {
	cb_offcanvas_toggle_left();
	cb_offcanvas_toggle_right();
}

/**
 * Off canvas Toggle handle for left panel
 */
function cb_offcanvas_toggle_left() {
    ?>
    <!-- left panel toggler -->
    <a id="panel-left-toggle" href="#panel-left" title="<?php esc_attr_e( 'Öffne linkes Panel', 'social-portal' ); ?>"><i class="fa fa-bars"></i></a>
	<?php
}

/**
 * Offcanvas toggle Handle for right panel
 */
function cb_offcanvas_toggle_right() {
    ?>
    <!-- right panel toggler -->
    <a id="panel-right-toggle" href="#panel-right" title="<?php esc_attr_e( 'Öffne rechtes Panel', 'social-portal' ); ?>"><i class="fa fa-bars"></i></a>
	<?php
}

/**
 * Quick Menu 1.
 */
function cb_quick_menu_1() {
	cb_nav_menu(
		'quick-menu-1',
		array(
			'menu_id'    => 'quick-menu-1',
			'menu_class' => 'nav-menu hq-menu quick-menu-1',
		)
	);
}

/**
 * Header bottom menu.
 */
function cb_header_bottom_menu() {
	cb_nav_menu(
		'header-bottom-menu',
		array(
			'menu_id'    => 'header-bottom-menu',
			'menu_class' => 'nav-menu dd-menu hb-menu header-bottom-menu',
		)
	);
}

/**
 * Primary Menu block
 *
 * Generate Primary Menu
 */
function cb_primary_menu() {
    //return;
	cb_nav_menu(
		'primary',
		array(
			'menu_class' => 'nav-menu dd-menu main-menu',
			'menu_id'    => 'main-menu',
		)
	);
}

/**
 * Show the nav menu.
 *
 * @param string $location location.
 * @param array  $args args to override.
 */
function cb_nav_menu( $location, $args = array() ) {

	$args = wp_parse_args(
		$args,
		array(
			'container'      => false,
			'menu_id'        => 'nav',
			'menu_class'     => 'greedy-nav',
			'items_wrap'     => '<div id="%1$s" class="%2$s"><ul class="nav-list">%3$s</ul></div>',
			'theme_location' => $location,
			'fallback_cb'    => 'cb_main_nav',
		)
	);

	$prefix = 'primary' === $location ? 'main-menu' : $location;

	$args['menu_class'] = $args['menu_class'] . ' greedy-nav nav-menu-' . cb_get_option( $prefix . '-alignment', 'left' );

	wp_nav_menu( $args );
}

/**
 * Text block 1.
 */
function cb_custom_text_block_1() {
	$content = cb_get_option( 'custom-text-block-1', '' );

	if ( $content ) {
		$content = wp_kses_data( $content );
		$show    = true;
	} else {
		$show = is_customize_preview();
	}

	if ( $show ) {
		echo '<span class="h-text-block custom-text-block-1">' . $content . '</span>';// WPCS:XSS OK.
	}
}

/**
 * Search form block
 *
 * Generate Search form
 */
function cb_search_form() {
    ?>
    <form action="<?php echo esc_url( home_url( '/' ) ); ?>" method="get" id="search-form" class="search-form">
        <label for="search-terms" class="accessibly-hidden"><?php _e( 'Suchen nach:', 'social-portal' ); ?></label>
        <input type="text" id="search-terms" class="search-text" name="s" value="<?php echo isset( $_REQUEST['s'] ) ? esc_attr( wp_unslash( $_REQUEST['s'] ) ) : ''; ?>" placeholder="<?php _ex( 'Suche hier', 'Suchtext Platzhaltertext', 'social-portal' ); ?>"/>
        <button type="submit" name="search-submit" id="search-submit"><i class="fa fa-search"></i></button>
    </form><!-- #search-form -->
	<?php
}

/**
 * Display Search box in header
 */
function cb_header_search_form() {
	//cb_is_header_search_visible()
	//if ( cb_is_header_search_visible() ) {
		cb_search_form();
	//}
}

/**
 * Generate Social icons.
 *
 * @param array  $options an array of enabled social sites.
 * @param string $context social menu context. Possible values 'header', 'footer'.
 * @param bool   $echo show or return.
 * @return void|string
 */
function cb_social_links( $options, $context = '', $echo = true ) {

	$icons = array(
		'facebook'    => '<i class="fa fa-facebook-official"></i>',
		'twitter'     => '<i class="fa fa-twitter-square"></i>',
		'google-plus' => '<i class="fa fa-google-plus-square"></i>',
		'linkedin'    => '<i class="fa fa-linkedin-square"></i>',
		'instagram'   => '<i class="fa fa-instagram"></i>',
		'flickr'      => '<i class="fa fa-flickr"></i>',
		'youtube'     => '<i class="fa fa-youtube"></i>',
		'vimeo'       => '<i class="fa fa-vimeo-square"></i>',
		'pinterest'   => '<i class="fa fa-pinterest"></i>',
		'email'       => '<i class="fa fa-envelope-o"></i>',
		'rss'         => '<i class="fa fa-rss-square"></i>',
	);

	$icons = apply_filters( 'cb_social_icons', $icons, $options, $context );

	$html = '';

	foreach ( $options as $social_site ) {
		$url  = cb_get_option( 'social-' . $social_site );
		$icon = isset( $icons[ $social_site ] ) ? $icons[ $social_site ] : '';

		if ( 'rss' === $social_site && empty( $url ) ) {
			$url = get_feed_link();
		}

		if ( ! $url || ! $icon ) {
			continue;
		}

		if ( 'email' === $social_site ) {
			$url = esc_attr( $url );
			$url = "mailto:{$url}";// rebuild url.
		} else {
			$url = esc_url( $url );
		}

		$html .= "<li><a href='{$url}'>{$icon}</a></li>";
	}

	if ( ! empty( $html ) ) {
		$html = "<ul class='social-links'>" . $html . '</ul>';
	}

	if ( $echo ) {
		echo $html; // WPCS: XSS ok.
	} else {
		return $html;
	}
}


/**
 * Returns a custom logo, linked to home.
 * Based on get_custom_logo().
 *
 * @param int $blog_id Optional. ID of the blog in question. Default is the ID of the current blog.
 * @return string Custom logo markup.
 */
function cb_get_mobile_logo( $blog_id = 0 ) {
	$html          = '';
	$switched_blog = false;

	if ( is_multisite() && ! empty( $blog_id ) && (int) $blog_id !== get_current_blog_id() ) {
		switch_to_blog( $blog_id );
		$switched_blog = true;
	}

	$custom_logo_id = get_theme_mod( 'mobile_logo' );
	$home_url       = apply_filters( 'cb_site_home_url', home_url( '/' ) );

	// We have a logo. Logo is go.
	if ( $custom_logo_id ) {
		$custom_logo_attr = array(
			'class'    => 'cb-mobile-logo',
			'itemprop' => 'logo',
		);

		/*
		 * If the logo alt attribute is empty, get the site title and explicitly
		 * pass it to the attributes used by wp_get_attachment_image().
		 */
		$image_alt = get_post_meta( $custom_logo_id, '_wp_attachment_image_alt', true );
		if ( empty( $image_alt ) ) {
			$custom_logo_attr['alt'] = get_bloginfo( 'name', 'display' );
		}

		/*
		 * If the alt attribute is not empty, there's no need to explicitly pass
		 * it because wp_get_attachment_image() already adds the alt attribute.
		 */
		$html = sprintf( '<a href="%1$s" class="mobile-logo-link" rel="home" itemprop="url">%2$s</a>',
			esc_url( $home_url ),
			wp_get_attachment_image( $custom_logo_id, 'full', false, $custom_logo_attr )
		);
	}

	// If no logo is set but we're in the Customizer, leave a placeholder (needed for the live preview).
	elseif ( is_customize_preview() ) {
		$html = sprintf( '<a href="%1$s" class="mobile-logo-link" style="display:none;"><img class="cb-mobile-logo"/></a>',
			esc_url( $home_url )
		);
	}

	if ( $switched_blog ) {
		restore_current_blog();
	}

	/**
	 * Filters the custom logo output.
	 *
	 * @param string $html    Custom logo HTML output.
	 * @param int    $blog_id ID of the blog to get the custom logo for.
	 */
	return apply_filters( 'cb_get_mobile_logo', $html, $blog_id );
}

/**
 * Print site name as text for use in place of logo.
 */
function cb_sitename_text() {

	$logo_text_class = '';

	if ( function_exists( 'the_custom_logo' ) ) {
		$has_logo = has_custom_logo();
	} else {
		$has_logo = cb_get_option( 'logo' );
	}

	if ( $has_logo ) {
		$logo_text_class = 'hidden-logo';
	}
	$home_url = apply_filters( 'cb_site_home_url', home_url( '/' ) );

	?>
	<?php if ( ! $has_logo || $has_logo && is_customize_preview() ) : ?>
        <span class="site-title <?php echo $logo_text_class; ?>" id="site-title">
				<a class="site-title-link" href="<?php echo esc_url( $home_url ); ?>" title="<?php _ex( 'Startseite', 'Titel des Startseiten-Logo-Links', 'social-portal' ); ?>">
                    <?php bloginfo( 'name' ); ?>
                </a>
			</span>
	<?php endif; ?>
	<?php
}

/**
 * Get site name and link.
 *
 * @return string
 */
function cb_get_site_name() {
	ob_start();
	cb_sitename_text();

	return ob_get_clean();
}

/**
 * Get Site description.
 *
 * @return string
 */
function cb_get_site_description() {
	return '<span class="site-description">' . get_bloginfo( 'description' ) . '</span>';
}


function cb_get_customize_text_option( $option_name ) {
    $text = get_theme_mod($option_name, '' );
    return $text ? "<span class='custom-text-data custom-text-". esc_attr( $option_name ) ." '>{text}</span>" : '';
}


/**
 * Social links in site header.
 */
function cb_header_social_links() {

	if ( cb_get_option( 'header-show-social' ) ) {
		 cb_social_links( cb_get_option( 'header-social-icons' ), 'header' );
	}

	return '';
}

/**
 * Social links in site header.
 */
function cb_get_header_social_links() {

	if ( cb_get_option( 'header-show-social' ) ) {
		return cb_social_links( cb_get_option( 'header-social-icons' ), 'header', false );
	}

	return '';
}

/**
 * Social links in site footer.
 */
function cb_footer_social_links() {
	if ( cb_get_option( 'footer-show-social' ) ) {
		cb_social_links( cb_get_option( 'footer-social-icons' ), 'footer' );
	}
}

/**
 * Determines whether the site has a custom mobile logo.
 *
 * @param int $blog_id Optional. ID of the blog in question. Default is the ID of the current blog.
 * @return bool Whether the site has a custom logo or not.
 */
function cb_has_mobile_logo( $blog_id = 0 ) {
	$switched_blog = false;

	if ( is_multisite() && ! empty( $blog_id ) && get_current_blog_id() !== (int) $blog_id ) {
		switch_to_blog( $blog_id );
		$switched_blog = true;
	}

	$custom_logo_id = get_theme_mod( 'mobile_logo' );

	if ( $switched_blog ) {
		restore_current_blog();
	}

	return (bool) $custom_logo_id;
}


/**
 * Account Links Block
 *
 * Must be used inside a Ul element
 */
function cb_login_account_links() {
	?>
	<?php if ( cb_is_bp_active() ) : ?>

		<?php if ( is_user_logged_in() ) : ?>

			<?php if ( cb_get_option( 'header-show-notification-menu' ) ) : ?>
				<?php cb_bp_notification_menu(); ?>
			<?php endif; ?>

			<?php if ( cb_get_option( 'header-show-account-menu' ) ) : ?>
				<?php cb_bp_account_menu(); ?>
			<?php endif; ?>

		<?php else : ?>

			<?php if ( cb_get_option( 'header-show-login-links' ) ) : ?>

				<?php if ( bp_get_signup_allowed() ) : ?>
					<li class="site-header-not-logged-in-link site-header-register-link">
						<!-- mobile signup action button -->
						<a href="<?php echo bp_get_signup_page(); ?>" class='icon-nav-item bp-ajaxr' aria-label="<?php esc_attr_e( 'Registrieren', 'social-portal' ); ?>" title="<?php esc_attr_e( 'Registrieren', 'social-portal' ); ?>" data-balloon-pos="down">
							<i class="fa fa-user"></i>
						</a>
						<!-- desktop action button -->
						<a href="<?php echo bp_get_signup_page(); ?>" class='<?php echo esc_attr( cb_get_button_class( "header-signup", "btn header-button header-register-button bp-ajaxr") );?>'><?php _e( 'Registrieren', 'social-portal' ); ?></a>
					</li>
				<?php endif; ?>
				<li class="site-header-not-logged-in-link site-header-login-link">
					<!-- mobile login action button -->
					<a href="<?php echo wp_login_url(); ?>" class='icon-nav-item bp-ajaxl'  aria-label="<?php esc_attr_e( 'Einloggen', 'social-portal' ); ?>" title="<?php esc_attr_e( 'Einloggen', 'social-portal' ); ?>" data-balloon-pos="down">
						<i class="fa fa-sign-in"></i>
					</a>
					<!-- desktop action button -->
					<a href="<?php echo wp_login_url(); ?>" class='<?php echo esc_attr( cb_get_button_class( "header-login", "btn header-button header-login-button bp-ajaxl") );?>' title="<?php _e( 'Einloggen', 'social-portal' ); ?>"><?php _e( 'Einloggen', 'social-portal' ); ?></a>
				</li>

			<?php endif; ?>

		<?php endif; ?>

	<?php endif; // buddypress active block end. ?>
	<?php
}