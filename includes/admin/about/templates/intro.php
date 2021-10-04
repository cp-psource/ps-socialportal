<?php
/**
 * Admin Intro view.
 *
 * @package    PS_SocialPortal
 * @subpackage Admin
 * @copyright  Copyright (c) 2019-2021, DerN3rd
 * @license    https://www.gnu.org/licenses/gpl.html GNU Public License
 * @author     DerN3rd
 */

// Do not allow direct access over web.
defined( 'ABSPATH' ) || exit;

$theme_data	= wp_get_theme();
?>
<div class="content-section welcome-text">

	<p>
		<?php
		/* translators: %s theme name */
		printf( esc_html__( 'Vielen Dank, dass Du Dich für %s entschieden hast, das leistungsstärkste BuddyPress-Theme.', 'social-portal' ), $theme_data->Name ) ;
		?>
	</p>
</div>

<div class="content-section intro-section">

	<div class="col-3">
		<h3><?php esc_html_e( 'Dokumentation', 'social-portal' ); ?></h3>
		<p>
			<?php /* translators: %s theme name */
			 printf( esc_html__( 'Benötigst Du Hilfe beim Einrichten? In unserer Dokumentation findest Du detaillierte Informationen zur Verwendung von %s.', 'social-portal' ), $theme_data->Name );
			?>
		</p>
		<a target="_blank" href="<?php echo esc_url( $this->docs_url ); ?>" class="button button-primary button-docs"><?php esc_html_e( 'Dokumentation anzeigen', 'social-portal' ); ?></a>
	</div>

	<div class="col-3">
		<h3><?php esc_html_e( 'Demo', 'social-portal' ); ?></h3>
		<p>

			<?php esc_html_e( "Wir verwenden dieses Theme auch auf unserer Webseite. Sieh dir PS Social-Portal im Live-Einsatz an.", 'social-portal' ); ?>
		</p>
		<a href="<?php echo esc_url( add_query_arg( array( 'utm_source' => 'dashboard', 'utm_campaign' => 'demo' ), $this->demo_url ) ); ?>" class="button button-primary button-demo" target="_blank"><?php esc_html_e( 'Demo anzeigen', 'social-portal' ); ?></a>
	</div>

	<div class="col-3">
		<h3><?php esc_html_e( 'Theme Customizer', 'social-portal' ); ?></h3>
		<p>
			<?php /* translators: %s theme name */
			printf( esc_html__( '%s unterstützt den Theme Customizer für alle Theme-Einstellungen. Klicke auf "Anpassen", um Deine Webseite zu personalisieren.', 'social-portal' ), esc_html( $theme_data->Name ) );
			?>
		</p>
		<a target="_blank" href="<?php echo esc_url( wp_customize_url() );?>" class="button button-primary button-customize"><?php esc_html_e( 'Starte Customizing', 'social-portal' ); ?></a>
	</div>

	<div class="col-3">
		<h3><?php esc_html_e( 'Plugin-Bundle', 'social-portal' ); ?></h3>
		<p>
			<?php /* translators: %s Theme Pluginbundle */
			printf( esc_html__( '%s bringt einige Plugins mit, welche Die Funktionen des Themes erweitern, bringe Emojis zu den Aktivitäten und mehr...', 'social-portal' ), esc_html( $theme_data->Name ) );
			?>
		</p>
		<a target="_blank" href="https://n3rds.work/docs/ps-social-portal-theme-plugins/" class="button button-primary button-plugin-doc"><?php esc_html_e( 'Plugin-Bundle Doc', 'social-portal' ); ?></a>
	</div>

</div> <!-- end of intro sections -->

