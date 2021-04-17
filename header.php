<?php
/**
 * PS SocialPortal Header file.
 *
 * This is the template that displays all of the <head> section
 * and everything up until <div id="site-container">.
 *
 * @package    PS_SocialPortal
 * @subpackage Template
 * @copyright  Copyright (c) 2018, DerN3rd
 * @license    https://www.gnu.org/licenses/gpl.html GNU Public License
 * @author     DerN3rd
 */

// Do not allow direct access over web.
defined( 'ABSPATH' ) || exit;

?><!DOCTYPE html>
<html <?php language_attributes(); ?> class="no-js">
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>"/>
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1"/>
	<link rel="profile" href="http://gmpg.org/xfn/11"/>

	<?php wp_head(); ?>

	<?php
	// Any custom Head js?
	$custom_scripts = cb_get_option( 'custom-head-js' );
	if ( ! empty( $custom_scripts ) ) {
		echo $custom_scripts; // WPCS: XSS ok.
	}
	?>
</head>

<body <?php body_class(); ?> id="social-portal">
<?php wp_body_open(); ?>
<a class="skip-link screen-reader-text" href="#site-content"><?php _e( 'Zum Inhalt springen', 'social-portal' ); ?></a>
<h1 class="accessibly-hidden"><?php bloginfo( 'name' ); ?></h1>
<div id='site-page' class="site-page">

	<?php do_action( 'cb_before_site_header' ); ?>

	<?php if ( cb_is_site_header_enabled() ) : ?>
		<header id="site-header" class="site-header clearfix">

			<?php
			/**
			 * PLEAS DO NOT COMMENT THE LINE BELOW THIS COMMENT.
			 * It is used to build the header layout.
			 * For more details, Please see
			 * includes/layout/builder/cb-page-builder.php &
			 * includes/layout/builder/cb-site-header-generator.php
			 */
			?>
			<?php do_action( 'cb_site_header' ); ?>

		</header><!-- #site-header -->
	<?php endif; ?>

	<?php do_action( 'cb_after_site_header' ); ?>

<?php
do_action( 'cb_before_site_container' );
