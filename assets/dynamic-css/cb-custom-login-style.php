<?php
/**
 * PS SocialPortal WordPress Login page Customization
 *
 * @package PS_SocialPortal
 */

// Do not allow direct access over web.
defined( 'ABSPATH' ) || exit;

// if customization not enabled, return.
if ( cb_get_option( 'disable-custom-login-style' ) ) {
	return;
}

/**
 * Add a div that will be used to mask the login page background image, colors
 */
function cb_login_add_mask_div() {
	echo "<div id='login-page-mask'></div>";
}
add_action( 'login_header', 'cb_login_add_mask_div', 0 );


/**
 * Filter the login header link ( Logo link )
 *
 * @param string $url url.
 *
 * @return string
 */
function cb_filter_login_header_url( $url ) {
	return get_home_url( '/' );
}

add_filter( 'login_headerurl', 'cb_filter_login_header_url' );

/**
 * Filter the title attribute of the logo link
 *
 * @param string $title site title.
 *
 * @return string
 */
function cb_filter_login_header_title( $title ) {
	return get_bloginfo( 'name' );
}

add_filter( 'login_headertext', 'cb_filter_login_header_title' );

global $wp_version;

// for older version.
if ( version_compare( $wp_version, '5.2.0', '<' ) ) {
	add_filter( 'login_headertitle', 'cb_filter_login_header_title' );
}

/**
 * Load default Login customization css file from community Builder
 * It is used to reset various elements css
 */
function cb_load_login_style() {
	wp_enqueue_style( 'cb-login-style', social_portal()->url . '/assets/css/login.css', array(), CB_THEME_VERSION );
}

add_action( 'login_enqueue_scripts', 'cb_load_login_style' );

/**
 * Finally, Inject the customized css generated from the customizer screen
 */
function cb_generate_login_style_css() {

	$builder = cb_get_css_builder();

	// page font style.
	cb_css_add_font_style( $builder, 'login', 'body.login', false );

	// Page background/text.
	cb_css_add_common_style( $builder, 'login', 'body.login', true );

	// for label, Use body text color.
	$text_color = cb_get_option( 'login-text-color' );

	$builder->add(
		array(
			'selectors'    => array( '#login label' ),
			'declarations' => array(
				'color' => $text_color,
			),
		)
	);

	$builder->add(
		array(
			'selectors'    => array( '.login .message, .login #login_error' ),
			'declarations' => array(
				'color' => '#333', // until we provide the option to customize the background/color of notice.
			),
		)
	);

	$mask_color = cb_get_option( 'login-page-mask-color' );

	$builder->add(
		array(
			'selectors'    => array( '#login-page-mask' ),
			'declarations' => array(
				'background' => $mask_color,
			),
		)
	);

	// Link Color
	// .login #nav a,
	// .login #backtoblog a.
	cb_css_add_link_style( $builder, 'login', '.login #nav a, .login #backtoblog a, div#login p a', '.login #nav a:hover, .login #backtoblog a:hover, div#login p a:hover', false );

	// Login Box background.
	$builder->add(
		array(
			'selectors'    => array( '#login form' ),
			'declarations' => array(
				'background-color' => cb_get_option( 'login-box-background-color' ),
			),
		)
	);

	// Login Box border.
	cb_css_add_border_style( $builder, 'login-box', '#login', false );

	// Site Name.
	cb_css_add_font_style( $builder, 'login-logo', '.login h1 a', false );
	// Site name link.
	cb_css_add_link_style( $builder, 'login-logo', '.login h1 a', '.login h1 a:hover', false );

	$logo = get_theme_mod( 'login-logo' );
	// logo.
	if ( ! empty( $logo ) ) {
		$rules = array(
			'background-image' => "url({$logo})",
			'background-size'  => 'auto auto',
			'width'            => '320px',
			'height'           => 'auto',
			'text-indent'      => '-9999px',
		);

		$builder->add(
			array(
				'selectors'    => array( '.login h1 a' ),
				'declarations' => $rules,
			)
		);
	}

	// Input text.
	$builder->add(
		array(
			'selectors'    => array( '.login form .input, .login input[type="text"]' ),
			'declarations' => array(
				'background-color' => cb_get_option( 'login-input-background-color' ),
				'color'            => cb_get_option( 'login-input-text-color' ),
			),
		)
	);

	// input border style.
	cb_css_add_border_style( $builder, 'login-input', '.login form .input, .login input[type="text"]', false );

	// Input focus.
	$focus_selectors = '.login form .input:focus, .login input[type="text"]:focus, .login form .input:active, .login input[type="text"]:active';
	$builder->add(
		array(
			'selectors'    => array( $focus_selectors ),
			'declarations' => array(
				'background-color' => cb_get_option( 'login-input-focus-background-color' ),
				'color'            => cb_get_option( 'login-input-focus-text-color' ),
			),
		)
	);

	// focus border style.
	cb_css_add_border_style( $builder, 'login-input-focus', $focus_selectors, false );

	// placeholder color.
	$placeholder_color = cb_get_option( 'login-input-placeholder-color' );

	$placeholder_selectors = array(
		'*::-webkit-input-placeholder',
		'*:-moz-placeholder',
		'*::-moz-placeholder',
		'*:-ms-input-placeholder',
	);

	// Each placeholder selector needs to be a different rule.
	foreach ( $placeholder_selectors as $placeholder_selector ) {
		$builder->add(
			array(
				'selectors'    => array( $placeholder_selector ),
				'declarations' => array(
					'color'     => $placeholder_color,
					'font-size' => '14px',
				),
			)
		);
	}
	// Button colors.
	cb_css_add_button_style( $builder, 'login-submit-button', '#wp-submit.button-primary', '#wp-submit.button-primary:focus, #wp-submit.button-primary:active, #wp-submit.button-primary:hover', false );

	// Print CSS.
	$css = cb_get_css_builder()->build();

	if ( ! empty( $css ) ) {
		echo "\n<!-- Begin PS SocialPortal Custom Login CSS -->\n<style type=\"text/css\" id=\"cb-theme-custom-css\">\n";
		echo $css;
		echo "\n</style>\n<!-- End PS SocialPortal Custom CSS -->\n";
	}
}

add_action( 'login_head', 'cb_generate_login_style_css' );

/**
 * Load google fonts if needed.
 */
function cb_enqueue_login_fonts() {
	$gf_uri = CB_Fonts::get_login_page_fonts_uri();
	if ( ! empty( $gf_uri ) ) {
		wp_enqueue_style( 'cb-login-google-font', $gf_uri );
	}
}

add_action( 'login_enqueue_scripts', 'cb_enqueue_login_fonts' );

/**
 * Make labels text as the placeholder of the input box
 */
function cb_login_footer_js() {
	?>
    <script type="text/javascript">

		(function () {

			if (typeof document.querySelectorAll == 'undefined') {
				return;
			}

			var labels = document.querySelectorAll('body.login form label');

			for (var i = 0; i < labels.length; i++) {
				var label = labels[i];
				var text = label.textContent.trim();
				var parent = label.parentNode;
				label.setAttribute('id', 'label_'+label.getAttribute('for'));
				if (!parent) {
					continue;
				}
				var childNodes = parent.children;

				for (var c = 0; c < childNodes.length; c++) {
					var child = childNodes[c];

					if (child.nodeName == 'BR') {
						//label.removeChild(child);//remove line breaks
					} else if (child.nodeName == 'INPUT' && child.type !== 'radio' && child.type != 'checkbox') {
						child.setAttribute('placeholder', text); //it is either text|email|password
						label.childNodes[0].nodeValue = '';
					} else {

					}
				}
			}

			// let us deal with PassWord separately.
			var passLabel = document.querySelectorAll('body.login .user-pass-wrap label');
			if (passLabel.length) {
				document.getElementById('user_pass').setAttribute('placeholder',  passLabel[0].textContent.trim());
				passLabel[0].childNodes[0].nodeValue = '';
			}

		})();

    </script>
	<?php
}

add_action( 'login_footer', 'cb_login_footer_js' );
