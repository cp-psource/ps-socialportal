<?php
/**
 * BuddyPress Configurator.
 *
 * @package    PS_SocialPortal
 * @subpackage BuddyPress/Bootstrap
 * @copyright  Copyright (c) 2019, DerN3rd
 * @license    https://www.gnu.org/licenses/gpl.html GNU Public License
 * @author     DerN3rd
 * @since      1.0.0
 */

// Do not allow direct access over web.
defined( 'ABSPATH' ) || exit;

/**
 * Activity ajax action handlers.
 */
class CB_BP_Activity_Ajax {

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

		add_action( 'wp_ajax_activity_filter', array( $this, 'get_collection' ) );
		add_action( 'wp_ajax_nopriv_activity_filter', array( $this, 'get_collection' ) );

		add_action( 'wp_ajax_activity_get_older_updates', array( $this, 'paged_collection' ) );
		add_action( 'wp_ajax_nopriv_activity_get_older_updates', array( $this, 'paged_collection' ) );

		// Get single activity.
		add_action( 'wp_ajax_activity_get_single', array( $this, 'get_single_activity' ) );
		add_action( 'wp_ajax_nopriv_activity_get_single', array( $this, 'get_single_activity' ) );

		// post.
		add_action( 'wp_ajax_post_update', array( $this, 'post_update' ) );
		add_action( 'wp_ajax_activity_delete', array( $this, 'delete_activity' ) );

		add_action( 'wp_ajax_activity_mark_fav', array( $this, 'favorite' ) );
		add_action( 'wp_ajax_activity_mark_unfav', array( $this, 'unvaforite' ) );
		add_action( 'wp_ajax_activity_mark_spam', array( $this, 'spam' ) );

		add_action( 'wp_ajax_activity_post_comment', array( $this, 'post_comment' ) );
		add_action( 'wp_ajax_activity_delete_comment', array( $this, 'delete_comment' ) );
		add_action( 'wp_ajax_activity_mark_comment_spam', array( $this, 'spam_comment' ) );

		add_action( 'wp_ajax_activity_mark_mentions_read', array( $this, 'mark_mentions_read' ) );
	}

	/**
	 * Get a collection of activity.
	 */
	public function get_collection() {
		if ( ! bp_is_post_request() ) {
			return;
		}

		$scope = '';
		if ( ! empty( $_POST['scope'] ) ) {
			$scope = $_POST['scope'];
		}

		// We need to calculate and return the feed URL for each scope.
		switch ( $scope ) {
			case 'friends':
				$feed_url = bp_loggedin_user_domain() . bp_get_activity_slug() . '/friends/feed/';
				break;
			case 'groups':
				$feed_url = bp_loggedin_user_domain() . bp_get_activity_slug() . '/groups/feed/';
				break;
			case 'favorites':
				$feed_url = bp_loggedin_user_domain() . bp_get_activity_slug() . '/favorites/feed/';
				break;
			case 'mentions':
				$feed_url = bp_loggedin_user_domain() . bp_get_activity_slug() . '/mentions/feed/';

				if ( isset( $_POST['_wpnonce_activity_filter'] ) && wp_verify_nonce( wp_unslash( $_POST['_wpnonce_activity_filter'] ), 'activity_filter' ) ) {
					bp_activity_clear_new_mentions( bp_loggedin_user_id() );
				}

				break;
			default:
				$feed_url = home_url( bp_get_activity_root_slug() . '/feed/' );
				break;
		}

		// Buffer the loop in the template to a var for JS to spit out.
		ob_start();
		bp_get_template_part( 'activity/activity-loop' );
		$result['contents'] = ob_get_contents();

		/**
		 * Filters the feed URL for when activity is requested via AJAX.
		 *
		 * @param string $feed_url URL for the feed to be used.
		 * @param string $scope Scope for the activity request.
		 */
		$result['feed_url'] = apply_filters( 'bp_legacy_theme_activity_feed_url', $feed_url, $scope );
		ob_end_clean();

		wp_send_json_success( $result );
	}

	/**
	 * Get paginated response.
	 */
	public function paged_collection() {

		if ( ! bp_is_post_request() ) {
			return;
		}

		$scope = '';
		if ( ! empty( $_POST['scope'] ) ) {
			$scope = $_POST['scope'];
		}

		// We need to calculate and return the feed URL for each scope.
		switch ( $scope ) {
			case 'friends':
				$feed_url = bp_loggedin_user_domain() . bp_get_activity_slug() . '/friends/feed/';
				break;
			case 'groups':
				$feed_url = bp_loggedin_user_domain() . bp_get_activity_slug() . '/groups/feed/';
				break;
			case 'favorites':
				$feed_url = bp_loggedin_user_domain() . bp_get_activity_slug() . '/favorites/feed/';
				break;
			case 'mentions':
				$feed_url = bp_loggedin_user_domain() . bp_get_activity_slug() . '/mentions/feed/';

				if ( isset( $_POST['_wpnonce_activity_filter'] ) && wp_verify_nonce( wp_unslash( $_POST['_wpnonce_activity_filter'] ), 'activity_filter' ) ) {
					bp_activity_clear_new_mentions( bp_loggedin_user_id() );
				}

				break;
			default:
				$feed_url = home_url( bp_get_activity_root_slug() . '/feed/' );
				break;
		}

		// Buffer the loop in the template to a var for JS to spit out.
		ob_start();
		bp_get_template_part( 'activity/activity-loop' );
		$result['contents'] = ob_get_contents();

		/**
		 * Filters the feed URL for when activity is requested via AJAX.
		 *
		 * @param string $feed_url URL for the feed to be used.
		 * @param string $scope Scope for the activity request.
		 */
		$result['feed_url'] = apply_filters( 'bp_legacy_theme_activity_feed_url', $feed_url, $scope );
		ob_end_clean();

		wp_send_json_success( $result );
	}

	/**
	 * Get a single activity.
	 */
	public function get_single_activity() {
		if ( ! bp_is_post_request() ) {
			return;
		}

		$activity_id    = isset( $_POST['activity_id'] ) ? absint( $_POST['activity_id'] ) : 0;
		$activity_array = bp_activity_get_specific(
			array(
				'activity_ids'     => $activity_id,
				'display_comments' => 'stream',
			)
		);

		$activity = ! empty( $activity_array['activities'][0] ) ? $activity_array['activities'][0] : false;

		if ( empty( $activity ) ) {
			wp_send_json_error(
				array(
					'redirect' => bp_activity_get_permalink( $activity_id ),
				)
			);
		}

		/**
		 * Fires before the return of an activity's full, non-excerpted content via a POST request.
		 *
		 * @param string $activity Activity content. Passed by reference.
		 */
		do_action_ref_array( 'bp_legacy_theme_get_single_activity_content', array( &$activity ) );

		// Activity content retrieved through AJAX should run through normal filters, but not be truncated.
		remove_filter( 'bp_get_activity_content_body', 'bp_activity_truncate_entry', 5 );

		/** This filter is documented in bp-activity/bp-activity-template.php */
		$content = apply_filters_ref_array( 'bp_get_activity_content_body', array( $activity->content, &$activity ) );

		wp_send_json_success(
			array(
				'contents' => $content,
			)
		);
	}

	/**
	 * Post a new update.
	 */
	public function post_update() {

		$bp = buddypress();

		if ( ! bp_is_post_request() ) {
			return;
		}

		if ( ! is_user_logged_in() || empty( $_POST['_wpnonce'] ) || ! wp_verify_nonce( $_POST['_wpnonce'], 'post_update' ) ) {
			wp_send_json_error(
				array(
					'message' => __( 'Ungültige Aktion.', 'social-portal' ),
				)
			);
		}

		$content = apply_filters( 'cb_activity_post_content', trim( wp_unslash( $_POST['content'] ) ) );

		// 'bp_activity_needs_content' is false for psourcemediathek and rt media.
		if ( empty( $content ) && ! apply_filters( 'bp_activity_needs_content', true ) ) {
			$content = '&nbsp;';
		}

		// BuddyPress will not take empty content, so let us check.
		if ( empty( $content ) ) {
			wp_send_json_error(
				array(
					'message' => __( 'Bitte gib einige Inhalte zum Posten ein.', 'social-portal' ),
				)
			);
		}

		$activity_id = 0;
		$item_id     = 0;
		$object      = '';

		// Try to get the item id from posted variables.
		if ( ! empty( $_POST['item_id'] ) ) {
			$item_id = (int) $_POST['item_id'];
		}

		// Try to get the object from posted variables.
		if ( ! empty( $_POST['object'] ) ) {
			$object = sanitize_key( $_POST['object'] );

			// If the object is not set and we're in a group, set the item id and the object.
		} elseif ( bp_is_group() ) {
			$item_id = bp_get_current_group_id();
			$object  = 'groups';
		}

		if ( ! $object && bp_is_active( 'activity' ) ) {
			$activity_id = bp_activity_post_update(
				array(
					'content'    => $content,
					'error_type' => 'wp_error',
				)
			);

		} elseif ( 'groups' === $object ) {
			if ( $item_id && bp_is_active( 'groups' ) ) {
				$activity_id = groups_post_update(
					array(
						'content'    => $content,
						'group_id'   => $item_id,
						'error_type' => 'wp_error',
					)
				);
			}
		} else {
			// This filter is documented in bp-activity/bp-activity-actions.php.
			$activity_id = apply_filters( 'bp_activity_custom_update', false, $object, $item_id, $content );
		}

		if ( false === $activity_id ) {
			wp_send_json_error(
				array(
					'message' => __( 'Beim Posten Deines Updates ist ein Problem aufgetreten. Bitte versuche es erneut.', 'social-portal' ),
				)
			);

		} elseif ( is_wp_error( $activity_id ) && $activity_id->get_error_code() ) {
			wp_send_json_error(
				array(
					'message' => $activity_id->get_error_message(),
				)
			);
		}

		do_action( 'cb_activity_posted', $activity_id );

		$last_recorded = ! empty( $_POST['since'] ) ? date( 'Y-m-d H:i:s', intval( $_POST['since'] ) ) : 0;
		if ( $last_recorded ) {
			$activity_args               = array( 'since' => $last_recorded );
			$bp->activity->last_recorded = $last_recorded;
			add_filter( 'bp_get_activity_css_class', 'bp_activity_newest_class', 10, 1 );
		} else {
			$activity_args = array( 'include' => $activity_id );
		}

		ob_start();

		if ( bp_has_activities( $activity_args ) ) {
			while ( bp_activities() ) {
				bp_the_activity();
				bp_get_template_part( 'activity/entry' );
			}
		}

		if ( ! empty( $last_recorded ) ) {
			remove_filter( 'bp_get_activity_css_class', 'bp_activity_newest_class', 10 );
		}

		$content = ob_get_clean();

		wp_send_json_success(
			array(
				'message'  => __( 'Veröffentlicht.', 'social-portal' ),
				'contents' => $content,
			)
		);
	}

	/**
	 * Delete activity.
	 */
	public function delete_activity() {
		if ( ! bp_is_post_request() ) {
			return;
		}

		$activity_id = isset( $_POST['id'] ) ? absint( $_POST['id'] ) : 0;

		if ( ! $activity_id || ! is_user_logged_in() || empty( $_POST['_wpnonce'] ) || ! wp_verify_nonce( $_POST['_wpnonce'], 'bp_activity_delete_link' ) ) {
			wp_send_json_error(
				array(
					'message' => __( 'Ungültige Aktion.', 'social-portal' ),
				)
			);
		}

		$activity = new BP_Activity_Activity( $activity_id );

		// Check access.
		if ( ! bp_activity_user_can_delete( $activity ) ) {
			wp_send_json_error(
				array(
					'message' => __( 'Zugriff verweigert.', 'social-portal' ),
				)
			);
		}

		/** This action is documented in bp-activity/bp-activity-actions.php */
		do_action( 'bp_activity_before_action_delete_activity', $activity->id, $activity->user_id );

		$deleted = '';
		if ( ! bp_activity_delete(
			array(
				'id'      => $activity->id,
				'user_id' => $activity->user_id,
			)
		)
		) {
			wp_send_json_error(
				array(
					'message' => __( 'Beim Löschen ist ein Problem aufgetreten. Bitte versuche es erneut.', 'social-portal' ),
				)
			);
		}

		/** This action is documented in bp-activity/bp-activity-actions.php */
		do_action( 'bp_activity_action_delete_activity', $activity->id, $activity->user_id );
		wp_send_json_success(
			array(
				'message' => __( 'Erfolgreich gelöscht.', 'social-portal' ),
				'id'      => $activity->id,
			)
		);
	}

	/**
	 * Post a new comment
	 */
	public function post_comment() {
		global $activities_template;

		if ( ! is_user_logged_in() || empty( $_POST['_wpnonce'] ) || ! wp_verify_nonce( $_POST['_wpnonce'], 'new_activity_comment' ) ) {
			wp_send_json_error(
				array(
					'message' => __( 'Ungültige Aktion.', 'social-portal' ),
				)
			);
		}

		$feedback = __( 'Beim Posten Deiner Antwort ist ein Fehler aufgetreten. Bitte versuche es erneut.', 'social-portal' );

		if ( empty( $_POST['content'] ) && apply_filters( 'bp_activity_comment_reply_needs_content', true ) ) {
			wp_send_json_error(
				array(
					'message' => __( 'Bitte lasse den Kommentarbereich nicht leer.', 'social-portal' ),
				)
			);
		}

		$activity_id = isset( $_POST['activity_id'] ) ? absint( $_POST['activity_id'] ) : 0;
		$comment_id  = isset( $_POST['comment_id'] ) ? absint( $_POST['comment_id'] ) : 0;

		if ( empty( $activity_id ) || empty( $comment_id ) ) {
			wp_send_json_error(
				array(
					'message' => $feedback,
				)
			);
		}

		$activity_item = new BP_Activity_Activity( $activity_id );

		if ( ! bp_activity_user_can_read( $activity_item ) ) {
			wp_send_json_error(
				array(
					'message' => $feedback,
				)
			);
		}

		$comment_id = bp_activity_new_comment(
			array(
				'activity_id' => $activity_id,
				'content'     => $_POST['content'],
				'parent_id'   => $_POST['comment_id'],
				'error_type'  => 'wp_error',
			)
		);

		if ( is_wp_error( $comment_id ) ) {
			wp_send_json_error(
				array(
					'message' => esc_html( $comment_id->get_error_message() ),
				)
			);
		}

		// Load the new activity item into the $activities_template global.
		bp_has_activities(
			array(
				'display_comments' => 'stream',
				'hide_spam'        => false,
				'show_hidden'      => true,
				'include'          => $comment_id,
			)
		);

		// Swap the current comment with the activity item we just loaded.
		if ( isset( $activities_template->activities[0] ) ) {
			$activities_template->activity                  = new stdClass();
			$activities_template->activity->id              = $activities_template->activities[0]->item_id;
			$activities_template->activity->current_comment = $activities_template->activities[0];

			// Because the whole tree has not been loaded, we manually
			// determine depth.
			$depth     = 1;
			$parent_id = (int) $activities_template->activities[0]->secondary_item_id;
			while ( $parent_id !== (int) $activities_template->activities[0]->item_id ) {
				$depth ++;
				$p_obj     = new BP_Activity_Activity( $parent_id );
				$parent_id = (int) $p_obj->secondary_item_id;
			}
			$activities_template->activity->current_comment->depth = $depth;
		}

		ob_start();
		// Get activity comment template part.
		bp_get_template_part( 'activity/comment' );
		$contents = ob_get_clean();
		unset( $activities_template );
		wp_send_json_success(
			array(
				'message' => __( 'Kommentar erfolgreich gepostet.', 'social-portal' ),
				'content' => $contents,
			)
		);
	}

	/**
	 * Mark favorite.
	 */
	public function favorite() {
		// Bail if not a POST action.
		if ( ! bp_is_post_request() ) {
			return;
		}

		$id    = isset( $_POST['id'] ) ? absint( $_POST['id'] ) : 0;
		$nonce = isset( $_POST['_wpnonce'] ) ? $_POST['_wpnonce'] : '';
		if ( ! $id || ! $nonce || ! is_user_logged_in() || ( ! wp_verify_nonce( $nonce, 'mark_favorite' ) && ! wp_verify_nonce( $nonce, 'unmark_favorite' ) ) ) {
			wp_send_json_error(
				array(
					'message' => __( 'Ungültige Aktion.', 'social-portal' ),
				)
			);
		}

		$activity_item = new BP_Activity_Activity( $id );
		if ( ! bp_activity_user_can_read( $activity_item, bp_loggedin_user_id() ) ) {
			wp_send_json_error(
				array(
					'message' => __( 'Es gab ein Problem beim Markieren des Favoriten.', 'social-portal' ),
				)
			);
		}

		if ( ! bp_activity_add_user_favorite( $activity_item->id ) ) {
			wp_send_json_error(
				array(
					'message' => __( 'Es gab ein Problem beim Markieren des Favoriten.', 'social-portal' ),
				)
			);
		}

		wp_send_json_success(
			array(
				'message' => __( 'Markierter Favorit.', 'social-portal' ),
				'label'   => __( 'Favorit entfernen', 'social-portal' ),
				'title'   => __( 'Favorit entfernen.', 'social-portal' ),
			)
		);

	}

	/**
	 * Mark unfavorite.
	 */
	public function unvaforite() {

		if ( ! bp_is_post_request() ) {
			return;
		}

		$id    = isset( $_POST['id'] ) ? absint( $_POST['id'] ) : 0;
		$nonce = isset( $_POST['_wpnonce'] ) ? $_POST['_wpnonce'] : '';

		if ( ! $id || ! $nonce || ! is_user_logged_in() || ( ! wp_verify_nonce( $nonce, 'mark_favorite' ) && ! wp_verify_nonce( $nonce, 'unmark_favorite' ) ) ) {
			wp_send_json_error(
				array(
					'message' => __( 'Ungültige Aktion.', 'social-portal' ),
				)
			);
		}

		$activity_item = new BP_Activity_Activity( $id );

		if ( ! bp_activity_user_can_read( $activity_item, bp_loggedin_user_id() ) ) {
			wp_send_json_error(
				array(
					'message' => __( 'Es gab ein Problem beim Entfernen des Favoriten.', 'social-portal' ),
				)
			);
		}

		if ( ! bp_activity_remove_user_favorite( $activity_item->id ) ) {
			wp_send_json_error(
				array(
					'message' => __( 'Es gab ein Problem beim Entfernen des Favoriten.', 'social-portal' ),
				)
			);
		}

		wp_send_json_success(
			array(
				'message' => __( 'Favorit entfernt.', 'social-portal' ),
				'label'   => __( 'Favorit', 'social-portal' ),
				'title'   => __( 'Markiere als Favoriten.', 'social-portal' ),
			)
		);
	}

	/**
	 * Mark spam.
	 */
	public function spam() {

		if ( ! bp_is_post_request() ) {
			return;
		}

		$bp = buddypress();

		$activity_id = isset( $_POST['id'] ) ? absint( $_POST['id'] ) : 0;


		// Check that user is logged in, Activity Streams are enabled, and Akismet is present.
		if ( ! $activity_id || ! is_user_logged_in() || empty( $bp->activity->akismet ) ) {
			wp_send_json_error(
				array(
					'message' => __( 'Ungültige Aktion.', 'social-portal' ),
				)
			);
		}

		// Is the current user allowed to spam items?
		if ( ! bp_activity_user_can_mark_spam() ) {
			wp_send_json_error(
				array(
					'message' => __( 'Ungültige Aktion.', 'social-portal' ),
				)
			);
		}

		// Load up the activity item.
		$activity = new BP_Activity_Activity( $activity_id );
		if ( empty( $activity->component ) ) {
			wp_send_json_error(
				array(
					'message' => __( 'Ungültige Aktion.', 'social-portal' ),
				)
			);
		}

		// Check nonce.
		check_admin_referer( 'bp_activity_akismet_spam_' . $activity->id );

		// This action is documented in bp-activity/bp-activity-actions.php.
		do_action( 'bp_activity_before_action_spam_activity', $activity->id, $activity );

		// Mark as spam.
		bp_activity_mark_as_spam( $activity );
		$activity->save();

		// This action is documented in bp-activity/bp-activity-actions.php.
		do_action( 'bp_activity_action_spam_activity', $activity->id, $activity->user_id );
		wp_send_json_success(
			array(
				'message' => __( 'Spam erfolgreich markiert.', 'social-portal' ),
				'id'      => $activity->id,
			)
		);
	}

	/**
	 * Handle comment delete.
	 */
	public function delete_comment() {

		if ( ! is_user_logged_in() || empty( $_POST['id'] )
		     || ! is_numeric( $_POST['id'] )
		     || empty( $_POST['_wpnonce'] )
		     || ! wp_verify_nonce( $_POST['_wpnonce'], 'bp_activity_delete_link' )
		) {
			wp_send_json_error(
				array(
					'message' => __( 'Ungültige Aktion.', 'social-portal' ),
				)
			);
		}

		$comment = new BP_Activity_Activity( absint( $_POST['id'] ) );

		// Check access.
		if ( ! bp_current_user_can( 'bp_moderate' ) && $comment->user_id != bp_loggedin_user_id() ) {
			wp_send_json_error(
				array(
					'message' => __( 'Erlaubnis verweigert.', 'social-portal' ),
				)
			);
		}

		// This action is documented in bp-activity/bp-activity-actions.php.
		do_action( 'bp_activity_before_action_delete_activity', $comment->id, $comment->user_id );

		if ( ! bp_activity_delete_comment( $comment->item_id, $comment->id ) ) {
			wp_send_json_error(
				array(
					'message' => __( 'Beim Löschen ist ein Problem aufgetreten. Bitte versuche es erneut.', 'social-portal' ),
				)
			);
		}

		/** This action is documented in bp-activity/bp-activity-actions.php */
		do_action( 'bp_activity_action_delete_activity', $comment->id, $comment->user_id );

		wp_send_json_success(
			array(
				'message' => __( 'Gelöscht.', 'social-portal' ),
				'id'      => $comment->id,
			)
		);
	}

	public function spam_comment() {

	}
}
