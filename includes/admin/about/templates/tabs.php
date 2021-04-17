<?php
/**
 * Admin Tabs list.
 *
 * @package    PS_SocialPortal
 * @subpackage Admin
 * @copyright  Copyright (c) 2019, DerN3rd
 * @license    https://www.gnu.org/licenses/gpl.html GNU Public License
 * @author     DerN3rd
 */

// Do not allow direct access over web.
defined( 'ABSPATH' ) || exit;
?>
<!-- Tabs -->
<?php
$active_tab = $this->get_current_tab();
$menu_slug  = $this->menu_slug;
$url        = admin_url( 'themes.php' );
?>

<div class="nav-tab-wrapper">
    <a href="<?php echo add_query_arg( array( 'page' => $menu_slug, 'tab' => 'intro' ), $url ); ?>" class="nav-tab nav-tab-intro <?php echo $active_tab == 'intro' ? 'nav-tab-active' : ''; ?>">
		<?php esc_html_e( 'Erste Schritte', 'social-portal' ); ?>
    </a>
    <!--
    <a href="<?php echo add_query_arg( array( 'page' => $menu_slug, 'tab' => 'docs' ), $url ); ?>"
       class="nav-tab nav-tab-docs <?php echo $active_tab == 'docs' ? 'nav-tab-active' : ''; ?>">
        <span class="dashicons dashicons-video-alt3"></span> <?php esc_html_e( 'Tutorials', 'social-portal' ); ?>
    </a>
-->
    <a href="<?php echo add_query_arg( array( 'page' => $menu_slug, 'tab' => 'support' ), $url ); ?>" class="nav-tab nav-tab-support <?php echo $active_tab == 'support' ? 'nav-tab-active' : ''; ?>">
		<?php esc_html_e( 'Support', 'social-portal' ); ?>
    </a>
</div>
