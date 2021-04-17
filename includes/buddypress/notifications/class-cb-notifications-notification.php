<?php

/**
 * Short Description
 *
 * @package    wp_themes_dev
 * @subpackage ${NAMESPACE}
 * @copyright  Copyright (c) 2019, DerN3rd
 * @license    https://www.gnu.org/licenses/gpl.html GNU Public License
 * @author     DerN3rd
 * @since      1.0.0
 */
class CB_Notifications_Notifications extends BP_Notifications_Notification {

	// we implement the next n method to fetch next n entries for the user
	// BuddyPress does not allow passing ofsset as of 5.0.
	/**
	 * Get N next notification whose id is less that the given id.
	 *
	 * @param array $args {
	 *     Associative array of arguments. All arguments but $page and
	 *     $per_page can be treated as filter values for get_where_sql()
	 *     and get_query_clauses(). All items are optional.
	 *
	 * @type int|array $id ID of notification being updated. Can be an
	 *                                           array of IDs.
	 * @type int|array $user_id ID of user being queried. Can be an
	 *                                           array of user IDs.
	 * @type int|array $item_id ID of associated item. Can be an array
	 *                                           of multiple item IDs.
	 * @type int|array $secondary_item_id ID of secondary associated
	 *                                           item. Can be an array of multiple IDs.
	 * @type string|array $component_name Name of the component to
	 *                                           filter by. Can be an array of component names.
	 * @type string|array $component_action Name of the action to
	 *                                           filter by. Can be an array of actions.
	 * @type bool $is_new Whether to limit to new notifications. True
	 *                                           returns only new notifications, false returns only non-new
	 *                                           notifications. 'both' returns all. Default: true.
	 * @type string $search_terms Term to match against component_name
	 *                                           or component_action fields.
	 * @type string $order_by Database column to order notifications by.
	 * @type string $sort_order Either 'ASC' or 'DESC'.
	 * @type string $order_by Field to order results by.
	 * @type string $sort_order ASC or DESC.
	 * @type int $page Number of the current page of results. Default:
	 *                                           false (no pagination - all items).
	 * @type int $per_page Number of items to show per page. Default:
	 *                                           false (no pagination - all items).
	 * @type array $meta_query Array of meta_query conditions. See WP_Meta_Query::queries.
	 * @type array $date_query Array of date_query conditions. See first parameter of
	 *                                           WP_Date_Query::__construct().
	 * @type bool $update_meta_cache Whether to update meta cache. Default: true.
	 * }
	 * @return array Located notifications ids.
	 */
	public static function get_next( $args ) {

		if ( empty( $args['next'] ) || empty( $args['id'] ) ) {
			return array();
		}

		global $wpdb;
		$r    = self::parse_args( $args );
		$id   = absint( $args['id'] );
		$next = absint( $args['next'] );
		unset( $r['id'] );

		if ( empty( $id ) || empty( $next ) ) {
			return array();
		}

		// Get BuddyPress.
		$bp = buddypress();

		// METADATA.
		$meta_query_sql = self::get_meta_query_sql( $r['meta_query'] );

		// SELECT.
		$select_sql = 'SELECT id';

		// FROM.
		$from_sql = "FROM {$bp->notifications->table_name} n ";

		// JOIN.
		$join_sql = $meta_query_sql['join'];

		// WHERE.
		$where_sql = self::get_where_sql(
			array(
				'user_id'           => $r['user_id'],
				'item_id'           => $r['item_id'],
				'secondary_item_id' => $r['secondary_item_id'],
				'component_name'    => $r['component_name'],
				'component_action'  => $r['component_action'],
				'is_new'            => $r['is_new'],
				'search_terms'      => $r['search_terms'],
				'date_query'        => $r['date_query'],
			),
			$select_sql,
			$from_sql,
			$join_sql,
			$meta_query_sql
		);

		$op = '<';

		if ( $where_sql ) {
			$where_sql .= $wpdb->prepare( " AND id $op %d ", $id );
		}

		// ORDER BY.
		$order_sql = self::get_order_by_sql(
			array(
				'order_by'   => $r['order_by'],
				'sort_order' => $r['sort_order'],
			)
		);


		// LIMIT %d, %d.
		$pag_sql = $wpdb->prepare( 'LIMIT %d, %d', 0, $next );

		// Concatenate query parts.
		$sql = "{$select_sql} {$from_sql} {$join_sql} {$where_sql} {$order_sql} {$pag_sql}";

		return $wpdb->get_col( $sql );

	}
}
