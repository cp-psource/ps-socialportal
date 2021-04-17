<?php
/**
 * Admin support view.
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
<div class="content-section support-section">

	<div class="col-3">
		<h3>
			<span class="dashicons dashicons-sos"></span>
			<?php esc_html_e( 'Forum', 'social-portal' ); ?>
		</h3>
		<p>
			<?php esc_html_e( "Bitte durchsuche das Support-Forum, bevor Du ein neues Thema erstellst. Wenn Du die Lösung nicht finden kannst, kannst Du ein neues Thema erstellen.", 'social-portal' ); ?>
		<hr>
		<a target="_blank" href="<?php echo esc_url( $this->support_url ); ?>"><?php esc_html_e( 'Gehe zu den Support-Foren', 'social-portal' ); ?></a>
		</p>
	</div>

	<div class="col-3">
		<h3>
			<span class="dashicons dashicons-admin-tools"></span>
			<?php esc_html_e( 'Changelog', 'social-portal' ); ?>
		</h3>
		<p>
			<?php esc_html_e( 'Möchtest Du wissen, was sich in den neuesten Themenupdates geändert hat? Die Liste der Änderungen findest Du in unserem Änderungsprotokoll.', 'social-portal' ); ?>
		<hr>
		<a target="_blank" href="<?php echo esc_url( $this->changelog_url ); ?>"><?php esc_html_e( 'Changelog', 'social-portal' ); ?></a>
		</p>
	</div>

</div>

