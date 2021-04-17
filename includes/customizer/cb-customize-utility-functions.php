<?php
/**
 * Utility functions for customize controls.
 *
 * @package    PS_SocialPortal
 * @subpackage Customizer\Controls
 * @copyright  Copyright (c) 2018, WMS N@W
 * @license    https://www.gnu.org/licenses/gpl.html GNU Public License
 * @author     WMS N@W
 */

// Do not allow direct access over web.
defined( 'ABSPATH' ) || exit;

/**
 * Markup to show the responsive device.
 */
function cb_customize_control_responsive_device_markup() {
	?>
    <div class="devices-wrapper cb-control-clearfix">
        <div class="devices">
            <button type="button" class="preview-desktop" aria-pressed="false" data-device="desktop">
                <span class="screen-reader-text"><?php _ex( 'Aktiviere Desktop-Vorschaumodus', 'Passe den Text des Bildschirmlesegeräts für die Steuerungsvorschau an', 'social-portal' ); ?></span>
            </button>
            <button type="button" class="preview-tablet active" aria-pressed="true" data-device="tablet">
                <span class="screen-reader-text"><?php _ex( 'Aktiviere Tablet-Vorschaumodus', 'Passe den Text des Bildschirmlesegeräts für die Steuerungsvorschau an', 'social-portal' ); ?></span>
            </button>
            <button type="button" class="preview-mobile" aria-pressed="false" data-device="mobile">
                <span class="screen-reader-text"><?php _ex( 'Aktiviere Mobil-Vorschaumodus', 'Passe den Text des Bildschirmlesegeräts für die Steuerungsvorschau an', 'social-portal' ); ?></span>
            </button>
        </div>
    </div>
	<?php
}
