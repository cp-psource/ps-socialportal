<?php
/**
 * Admin Bar Menu Manager:- Helps scrap nodes from the admin bar.
 *
 * @package    PS SocialPortal
 * @subpackage Core
 * @copyright  Copyright (c) 2018, DerN3rd
 * @license    https://www.gnu.org/licenses/gpl.html GNU Public License
 * @author     DerN3rd
 * @since      1.0.0
 */

// Do not allow direct access over web.
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'WP_Admin_Bar' ) ) {
	require_once ABSPATH . '/' . WPINC . '/class-wp-admin-bar.php';
}

/**
 * Yes, WP Has a messed implementation of admin bar that does not allow for partial rendering
 * and we are doing all kinds of hack here to make that possible
 */
class CB_Admin_Bar_Menu_Manager extends WP_Admin_Bar {

	/**
	 * Protected nodes.
	 *
	 * @var array
	 */
	protected $nodes = array();

	/**
	 * Bound.
	 *
	 * @var bool
	 */
	protected $bound = false;

	/**
	 * CB_Admin_Bar_Menu_Manager constructor.
	 *
	 * @param array $nodes nodes.
	 */
	public function __construct( $nodes = array() ) {

		$this->nodes = $nodes;
		$this->_xbind();
	}

	/**
	 * Render the item given by the id
	 *
	 * @param string $id id.
	 *
	 * @return string
	 */
	public function partial( $id ) {

		$node = $this->_xget_node( $id );

		if ( ! $node ) {
			return '';
		}

		ob_start();

		if ( 'item' === $node->type ) {
			$this->_render_item( $node );
		} else {
			$this->_render_group( $node );
		}

		// We got them all, let us change the ids.
		$menus = ob_get_clean();

		return $menus;
	}

	/**
	 * Render account Menu
	 *
	 * @param string $prefix the prefix used to replace wp-admin-bar-my-account.
	 */
	public function account( $prefix = 'nav' ) {

		$menus = $this->partial( 'my-account-buddypress' );
		$menus = str_replace( array( 'wp-admin-bar-my', 'ab-sub-wrapper' ), array( $prefix, 'nav-links-wrapper' ), $menus );

		echo $menus;
	}

	/**
	 * Render Notifications menu
	 */
	public function notifications() {

		$menus = $this->partial( 'bp-notifications-default' );
		$menus = str_replace( array( 'wp-admin-bar', 'ab-sub-wrapper' ), array( 'nav-account', 'nav-links-wrapper' ), $menus );

		echo $menus;
	}

	/**
	 * Render sites menu.
	 */
	public function sites() {

		$menus = $this->partial( 'my-sites' );
		$menus = str_replace( array( 'wp-admin-bar', 'ab-sub-wrapper' ), array( 'nav-account', 'nav-links-wrapper' ), $menus );

		echo '<ul class="sites-dashboard-list">';

		if ( cb_is_sites_menu_visible() ) {
			echo $menus;
		}

		$cap = cb_get_option( 'dashboard-link-capability' );

		if ( cb_is_header_account_menu_visible() && $cap && current_user_can( $cap ) ) {
			echo '<li><a href="' . admin_url( '/' ) . '">' . __( 'Dashboard', 'social-portal' ) . '</a></li>';
		}
		echo '</ul>';
	}

	/**
	 * Bind.
	 *
	 * @return object|null
	 */
	final protected function _xbind() {

		// do not bind again.
		if ( $this->bound ) {
			return null;
		}

		// Normalize nodes: define internal 'children' and 'type' properties.
		foreach ( $this->nodes as $node ) {
			$node->children = array();
			$node->type     = ( $node->group ) ? 'group' : 'item';
			unset( $node->group );

			// The Root wants your orphans. No lonely items allowed.
			if ( ! $node->parent ) {
				$node->parent = 'root';
			}
		}

		foreach ( $this->nodes as $node ) {

			if ( 'root' === $node->id ) {
				continue;
			}

			// Fetch the parent node. If it isn't registered, ignore the node.
			if ( ! $parent = $this->_xget_node( $node->parent ) ) {
				continue;
			}

			// Generate the group class (we distinguish between top level and other level groups).
			$group_class = ( 'root' === $node->parent ) ? 'ab-top-menu' : 'ab-submenu';

			if ( 'group' === $node->type ) {
				if ( empty( $node->meta['class'] ) ) {
					$node->meta['class'] = $group_class;
				} else {
					$node->meta['class'] .= ' ' . $group_class;
				}
			}

			// Items in items aren't allowed. Wrap nested items in 'default' groups.
			if ( 'item' === $parent->type && 'item' === $node->type ) {
				$default_id = $parent->id . '-default';
				$default    = $this->_xget_node( $default_id );

				// The default group is added here to allow groups that are
				// added before standard menu items to render first.
				if ( ! $default ) {
					// Use _set_node because add_node can be overloaded.
					// Make sure to specify default settings for all properties.
					$this->_xset_node(
						array(
							'id'       => $default_id,
							'parent'   => $parent->id,
							'type'     => 'group',
							'children' => array(),
							'meta'     => array(
								'class' => $group_class,
							),
							'title'    => false,
							'href'     => false,
						)
					);
					$default            = $this->_xget_node( $default_id );
					$parent->children[] = $default;
				}
				$parent = $default;

				// Groups in groups aren't allowed. Add a special 'container' node.
				// The container will invisibly wrap both groups.
			} elseif ( 'group' === $parent->type && 'group' === $node->type ) {
				$container_id = $parent->id . '-container';
				$container    = $this->_xget_node( $container_id );

				// We need to create a container for this group, life is sad.
				if ( ! $container ) {
					// Use _set_node because add_node can be overloaded.
					// Make sure to specify default settings for all properties.
					$this->_xset_node(
						array(
							'id'       => $container_id,
							'type'     => 'container',
							'children' => array( $parent ),
							'parent'   => false,
							'title'    => false,
							'href'     => false,
							'meta'     => array(),
						)
					);

					$container = $this->_xget_node( $container_id );

					// Link the container node if a grandparent node exists.
					$grandparent = $this->_xget_node( $parent->parent );

					if ( $grandparent ) {
						$container->parent = $grandparent->id;

						$index = array_search( $parent, $grandparent->children, true );

						if ( false === $index ) {
							$grandparent->children[] = $container;
						} else {
							array_splice( $grandparent->children, $index, 1, array( $container ) );
						}
					}

					$parent->parent = $container->id;
				}

				$parent = $container;
			}

			// Update the parent ID (it might have changed).
			$node->parent = $parent->id;

			// Add the node to the tree.
			$parent->children[] = $node;
		}

		$root        = $this->_xget_node( 'root' );
		$this->bound = true;

		return $root;
	}

	/**
	 * Set node.
	 *
	 * @param array $args args.
	 */
	public function _xset_node( $args ) {
		$this->nodes[ $args['id'] ] = (object) $args;
	}

	/**
	 * Get node by id.
	 *
	 * @param string $id node id.
	 *
	 * @return mixed|null
	 */
	public function _xget_node( $id ) {
		return isset( $this->nodes[ $id ] ) ? $this->nodes[ $id ] : null;
	}
}
