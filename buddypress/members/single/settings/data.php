<?php
/**
 * BuddyPress - Member - Settings - Data(Export)
 *
 * @package    PS_SocialPortal
 * @subpackage BuddyPress
 * @copyright  Copyright (c) 2019, DerN3rd
 * @license    https://www.gnu.org/licenses/gpl.html GNU Public License
 * @author     DerN3rd
 */

// Do not allow direct access over web.
defined( 'ABSPATH' ) || exit;

do_action( 'bp_before_member_settings_template' );
?>

	<h4 class="screen-heading data-settings-screen">
		<?php esc_html_e( 'Data Export', 'social-portal' ); ?>
	</h4>
    <div class="bp-settings-section bp-settings-section-export">
<?php $request = bp_settings_get_personal_data_request(); ?>

<?php if ( $request ) : ?>

	<?php if ( 'request-completed' === $request->status ) : ?>

		<?php if ( bp_settings_personal_data_export_exists( $request ) ) : ?>
            <div class="info">
                <p><?php esc_html_e( 'Dein Antrag auf Export personenbezogener Daten wurde abgeschlossen.', 'social-portal' ); ?></p>
                <p><?php
	                /* translators: %s: personal data expiration date */
                    printf( esc_html__( 'Du kannst Deine persönlichen Daten herunterladen, indem Du auf den unten stehenden Link klickst. Aus Datenschutz- und Sicherheitsgründen löschen wir die Datei automatisch auf %s. Lade sie daher bitte vorher herunter.', 'social-portal' ), bp_settings_get_personal_data_expiration_date( $request ) ); ?></p>

                <p>
                    <strong><?php printf( '<a href="%1$s">%2$s</a>', bp_settings_get_personal_data_export_url( $request ), esc_html__( 'Lade persönliche Daten herunter', 'social-portal' ) ); ?></strong>
                </p>
            </div>
		<?php else : ?>
            <div class="info">
                <p><?php esc_html_e( 'Deine vorherige Anfrage für den Export personenbezogener Daten ist abgelaufen.', 'social-portal' ); ?></p>
                <p><?php esc_html_e( 'Bitte klicke auf die Schaltfläche unten, um eine neue Anfrage zu stellen.', 'social-portal' ); ?></p>
            </div>
			<form id="bp-data-export" method="post">
				<input type="hidden" name="bp-data-export-delete-request-nonce" value="<?php echo wp_create_nonce( 'bp-data-export-delete-request' ); ?>" />
				<button type="submit" name="bp-data-export-nonce" value="<?php echo wp_create_nonce( 'bp-data-export' ); ?>"><?php esc_html_e( 'Neuen Datenexport anfordern', 'social-portal' ); ?></button>
			</form>

		<?php endif; ?>

	<?php elseif ( 'request-confirmed' === $request->status ) : ?>
        <div class="info">
            <p><?php
	            /* translators: %s: request date */
                printf( esc_html__( 'Du hast zuvor einen Export Deiner persönlichen Daten auf %s angefordert.', 'social-portal' ), bp_settings_get_personal_data_confirmation_date( $request ) ); ?></p>
            <p><?php esc_html_e( 'Du erhältst einen Link zum Herunterladen Deines Exports per E-Mail, sobald wir Deine Anfrage erfüllen können.', 'social-portal' ); ?></p>
        </div>
	<?php endif; ?>

<?php else : ?>
    <div class="info">
	<p><?php esc_html_e( 'Du kannst einen Export Deiner persönlichen Daten beantragen, die gegebenenfalls folgende Elemente enthalten:', 'social-portal' ); ?></p>
    </div>
    <div class="cb-exportable-data-items">
		<?php bp_settings_data_exporter_items(); ?>
	</div>

	<p><?php esc_html_e( 'Wenn Du eine Anfrage stellen möchtest, klicke bitte auf die Schaltfläche unten:', 'social-portal' ); ?></p>

	<form id="bp-data-export" method="post">
		<button type="submit" name="bp-data-export-nonce" value="<?php echo wp_create_nonce( 'bp-data-export' ); ?>"><?php esc_html_e( 'Export persönlicher Daten anfordern', 'social-portal' ); ?></button>
	</form>

<?php endif; ?>
	</div>

	<h4 class="screen-heading data-settings-screen">
		<?php esc_html_e( 'Daten löschen', 'social-portal' ); ?>
	</h4>
	<div class="bp-settings-section bp-settings-section-export">
<?php /* translators: Link to Delete Account Settings page */ ?>
	<p><?php esc_html_e( 'Um alle mit Deinem Konto verknüpften Daten zu löschen, muss Dein Benutzerkonto vollständig gelöscht werden.', 'social-portal' ); ?> <?php if ( bp_disable_account_deletion() ) : ?><?php esc_html_e( 'Bitte wende Dich an den Seiten-Administrator, um die Löschung des Kontos zu beantragen.', 'social-portal' ); ?><?php else : ?><?php /* translators: %s: delete account link */ printf( esc_html__( 'Du kannst Dein Konto löschen, indem Du die Seite %s besuchst.', 'social-portal' ), sprintf( '<a href="%s">%s</a>', bp_displayed_user_domain() . bp_get_settings_slug() . '/delete-account/', esc_html__( 'Konto löschen', 'social-portal' ) ) ); ?><?php endif; ?></p>

    </div>
<?php

do_action( 'bp_after_member_settings_template' );
