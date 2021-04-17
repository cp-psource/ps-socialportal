<?php
/**
 * Ajax action handler for notifications module.
 *
 * @package    PS_SocialPortal
 * @subpackage BuddyPress/Notifications
 * @copyright  Copyright (c) 2019, DerN3rd
 * @license    https://www.gnu.org/licenses/gpl.html GNU Public License
 * @author     DerN3rd
 * @since      1.0.0
 */

class CB_BP_Notifications_Ajax {

	/**
	 * CB_BP_Helper constructor.
	 */
	private function __construct() {
	}

	/**
	 * Boot
	 */
	public static function boot() {
		static $self;
		if ( is_null( $self ) ) {
			$self = new self();
			$self->setup();
		}

		return $self;
	}

	/**
	 * Setup.
	 */
	private function setup() {

		add_action( 'wp_ajax_cb_notifications_get', array( $this, 'get_collection' ) );
		// Get single notification.
		add_action( 'wp_ajax_cb_notifications_get_single', array( $this, 'get_single' ) );

		// post.
		add_action( 'wp_ajax_cb_notifications_delete', array( $this, 'delete' ) );
		add_action( 'wp_ajax_cb_notifications_delete_bulk', array( $this, 'delete_bulk' ) );

		add_action( 'wp_ajax_cb_notifications_mark_read', array( $this, 'mark_read' ) );
		add_action( 'wp_ajax_cb_notifications_mark_read_bulk', array( $this, 'mark_read_bulk' ) );
		add_action( 'wp_ajax_cb_notifications_mark_unread', array( $this, 'mark_unread' ) );
		add_action( 'wp_ajax_cb_notifications_mark_unread_bulk', array( $this, 'mark_unread_bulk' ) );
	}

	/**
	 * Get collection.
	 */
	public function get_collection() {
		// we may want to make it specific in future.
		$this->verify_nonce( 'notifications-action' );

		$page  = isset( $_POST['page'] ) ? absint( $_POST['page'] ) : 0;
		$scope = isset( $_POST['scope'] ) ? $_POST['scope'] : 'all';

		$args = array(
			'page'     => $page,
			'per_page' => 5,
			'user_id'  => bp_loggedin_user_id(),
		);
		if ( empty( $scope ) || 'all' === $scope ) {
			$is_new = 'both';
		} elseif ( 'unread' === $scope ) {
			$is_new = 1;
		} else {
			$is_new = 0;
		}
		$args['is_new'] = $is_new;

		if ( ! empty( $_POST['next'] ) && ! empty( $_POST['id'] ) ) {
			$args['id'] = CB_Notifications_Notifications::get_next(
				array(
					'id'         => absint( $_POST['id'] ),
					'next'       => absint( $_POST['next'] ),
					'is_new'     => $is_new,
					'user_id'    => bp_loggedin_user_id(),
					'order_by'   => 'id',
					'sort_order' => 'DESC',
				)
			);
		}

		ob_start();
		if ( bp_has_notifications( $args ) ) {
			$has_more = true;
			bp_get_template_part( 'members/single/notifications/notifications-loop' );
		} else {
			$has_more = false;
			bp_get_template_part( 'members/single/notifications/feedback-no-notifications' );
		}

		$content = ob_get_clean();
		wp_send_json_success(
			array(
				'contents' => $content,
				'has_more' => $has_more,
			)
		);

	}

	/**
	 * Get single notification.
	 */
	public function get_single() {

	}

	/**
	 * Delete single notification.
	 */
	public function delete() {
		$this->verify_nonce( 'notifications-action' );
		$id = isset( $_POST['id'] ) ? absint( $_POST['id'] ) : 0;
		if ( empty( $id ) ) {
			wp_send_json_error(
				array(
					'message' => __( 'Ungültige Aktion.', 'social-portal' ),
					'id'      => $id,
				)
			);
		}

		if ( ! bp_notifications_delete_notification( $id ) ) {
			wp_send_json_error(
				array(
					'message' => __( 'Nicht autorisierte Aktion.', 'social-portal' ),
					'id'      => $id,
				)
			);
		}

		wp_send_json_success(
			array(
				'message' => __( 'Erfolgreich gelöscht.', 'social-portal' ),
				'id'      => $id,
			)
		);
	}

	/**
	 * Delete bulk.
	 */
	public function delete_bulk() {
		$this->verify_nonce( 'notifications-action' );

		$ids = isset( $_POST['ids'] ) ? $_POST['ids'] : array();
		$ids = ! is_array( $ids ) ? explode( ',', $ids ) : $ids;
		$ids = wp_parse_id_list( $ids );

		if ( empty( $ids ) ) {
			wp_send_json_error(
				array(
					'message' => __( 'Invalid action.', 'social-portal' ),
				)
			);
		}

		$user_id = bp_loggedin_user_id();
		$deleted = false;
		foreach ( $ids as $id ) {
			if ( ! bp_notifications_check_notification_access( $user_id, $id ) ) {
				wp_send_json_error(
					array(
						'message' => __( 'Nicht autorisierte Aktion.', 'social-portal' ),
					)
				);
			}
		}

		foreach ( $ids as $id ) {
			$deleted = BP_Notifications_Notification::delete( array( 'id' => $id ) );
			if ( ! $deleted ) {
				break;
			}
		}

		if ( ! $deleted ) {
			wp_send_json_error(
				array(
					'message' => __( 'Es gab ein Problem. Bitte versuche es später noch einmal.', 'social-portal' ),
				)
			);
		}

		wp_send_json_success(
			array(
				'message' => __( 'Erfolgreich gelöscht.', 'social-portal' ),
				'ids'     => $ids,
			)
		);
	}

	/**
	 * Mark read single notification.
	 */
	public function mark_read() {
		$this->verify_nonce( 'notifications-action' );
		$id = isset( $_POST['id'] ) ? absint( $_POST['id'] ) : 0;
		if ( empty( $id ) ) {
			wp_send_json_error(
				array(
					'message' => __( 'Ungültige Aktion.', 'social-portal' ),
				)
			);
		}

		if ( ! bp_notifications_check_notification_access( bp_loggedin_user_id(), $id ) ) {
			wp_send_json_error(
				array(
					'message' => __( 'Nicht autorisierte Aktion.', 'social-portal' ),
					'id'      => $id,
				)
			);
		}

		$updated = BP_Notifications_Notification::update(
			array(
				'is_new' => 0,
			),
			array(
				'id' => $id,
			)
		);

		wp_send_json_success(
			array(
				'message' => __( 'Als gelesen markiert.', 'social-portal' ),
				'id'      => $id,
			)
		);
	}

	/**
	 * Mark read bulk.
	 */
	public function mark_read_bulk() {
		$this->verify_nonce( 'notifications-action' );
		$ids = isset( $_POST['ids'] ) ? $_POST['ids'] : array();

		$ids = ! is_array( $ids ) ? explode( ',', $ids ) : $ids;
		$ids = wp_parse_id_list( $ids );
		if ( empty( $ids ) ) {
			wp_send_json_error(
				array(
					'message' => __( 'Ungültige Aktion.', 'social-portal' ),
				)
			);
		}

		$user_id = bp_loggedin_user_id();
		foreach ( $ids as $id ) {
			if ( ! bp_notifications_check_notification_access( $user_id, $id ) ) {
				wp_send_json_error(
					array(
						'message' => __( 'Nicht autorisierte Aktion.', 'social-portal' ),
					)
				);
			}
		}

		$updated = false;
		foreach ( $ids as $id ) {
			$updated = BP_Notifications_Notification::update(
				array(
					'is_new' => 0,
				),
				array(
					'id' => $id,
				)
			);
		}

		wp_send_json_success(
			array(
				'message' => __( 'Als gelesen markiert.', 'social-portal' ),
				'ids'     => $ids,
			)
		);
	}

	/**
	 * Mark unread single item.
	 */
	public function mark_unread() {
		$this->verify_nonce( 'notifications-action' );
		$id = isset( $_POST['id'] ) ? absint( $_POST['id'] ) : 0;
		if ( empty( $id ) ) {
			wp_send_json_error(
				array(
					'message' => __( 'Ungültige Aktion.', 'social-portal' ),
					'id'      => $id,
				)
			);
		}

		if ( ! bp_notifications_check_notification_access( bp_loggedin_user_id(), $id ) ) {
			wp_send_json_error(
				array(
					'message' => __( 'Nicht autorisierte Aktion.', 'social-portal' ),
					'id'      => $id,
				)
			);
		}

		$updated = BP_Notifications_Notification::update(
			array(
				'is_new' => 1,
			),
			array(
				'id' => $id,
			)
		);

		wp_send_json_success(
			array(
				'message' => __( 'Als ungelesen markiert.', 'social-portal' ),
				'id'      => $id,
			)
		);
	}

	/**
	 * Mark unread bulk.
	 */
	public function mark_unread_bulk() {
		$this->verify_nonce( 'notifications-action' );

		$ids = isset( $_POST['ids'] ) ? $_POST['ids'] : array();

		$ids = ! is_array( $ids ) ? explode( ',', $ids ) : $ids;
		$ids = wp_parse_id_list( $ids );
		if ( empty( $ids ) ) {
			wp_send_json_error(
				array(
					'message' => __( 'Ungültige Aktion.', 'social-portal' ),
					'ids'     => $ids,
				)
			);
		}

		$user_id = bp_loggedin_user_id();
		foreach ( $ids as $id ) {
			if ( ! bp_notifications_check_notification_access( $user_id, $id ) ) {
				wp_send_json_error(
					array(
						'message' => __( 'Nicht autorisierte Aktion.', 'social-portal' ),
						'ids'     => $ids,
					)
				);
			}
		}

		$updated = false;
		foreach ( $ids as $id ) {
			$updated = BP_Notifications_Notification::update(
				array(
					'is_new' => 1,
				),
				array(
					'id' => $id,
				)
			);
		}

		wp_send_json_success(
			array(
				'message' => __( 'Als ungelesen markiert.', 'social-portal' ),
				'ids'     => $ids,
			)
		);
	}


	/**
	 * Verify nonce for the given action.
	 *
	 * @param string $nonce_action nonce action.
	 */
	private function verify_nonce( $nonce_action ) {

		if ( ! isset( $_POST['_wpnonce'] ) || ! wp_verify_nonce( $_POST['_wpnonce'], $nonce_action ) ) {
			wp_send_json_error(
				array(
					'message' => __( 'Ungültige Aktion.', 'social-portal' ),
				)
			);
		}
	}

	private function get_next_entries( $n, $last_id, $scope ) {
		BP_Notifications_Notification::get();
	}

}
