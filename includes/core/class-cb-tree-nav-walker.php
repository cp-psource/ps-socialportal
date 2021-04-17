<?php
/**
 * Tree Nav Walker, Used in the left/right panels.
 *
 * @package    PS_SocialPortal
 * @subpackage Core
 * @copyright  Copyright (c) 2018, DerN3rd
 * @license    https://www.gnu.org/licenses/gpl.html GNU Public License
 * @author     DerN3rd
 * @since      1.0.0
 */

// Do not allow direct access over web.
defined( 'ABSPATH' ) || exit;

/**
 * PS SocialPortal TreeView nav walker, based on Bootstrap Nav walker
 *
 * Enables Treeview menu for the community builder theme
 */
class CB_TreeView_Nav_Walker extends Walker_Nav_Menu {

	/**
	 * Start level.
	 *
	 * @param string $output Passed by reference. Used to append additional content.
	 * @param int    $depth Depth of page. Used for padding.
	 * @param array  $args args.
	 */
	public function start_lvl( &$output, $depth = 0, $args = array() ) {

		$indent  = str_repeat( "\t", $depth );
		$output .= "\n$indent<ul role='menu' class='treeview-sub-menu'>\n";
	}

	/**
	 * Start element.
	 *
	 * @see Walker::start_el()
	 *
	 * @param string $output Passed by reference. Used to append additional content.
	 * @param object $item Menu item data object.
	 * @param int    $depth Depth of menu item. Used for padding.
	 * @param array  $args args.
	 * @param int    $id id.
	 */
	public function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {

		$indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';

		$args = apply_filters( 'nav_menu_item_args', $args, $item, $depth );

		/**
		 * Dividers, Headers or Disabled
		 * =============================
		 * Determine whether the item is a Divider, Header, Disabled or regular
		 * menu item. To prevent errors we use the strcasecmp() function to so a
		 * comparison that is not case sensitive. The strcasecmp() function returns
		 * a 0 if the strings are equal.
		 */
		if ( strcasecmp( $item->attr_title, 'divider' ) == 0 && $depth === 1 ) {
			$output .= $indent . '<li role="presentation" class="divider">';
		} elseif ( strcasecmp( $item->title, 'divider' ) == 0 && $depth === 1 ) {
			$output .= $indent . '<li role="presentation" class="divider">';
		} elseif ( strcasecmp( $item->attr_title, 'header' ) == 0 && $depth === 1 ) {
			$output .= $indent . '<li role="presentation" class="header">' . esc_attr( $item->title );
		} elseif ( strcasecmp( $item->attr_title, 'disabled' ) == 0 ) {
			$output .= $indent . '<li role="presentation" class="disabled"><a href="#">' . esc_attr( $item->title ) . '</a>';
		} else {

			$value     = '';
			$classes   = empty( $item->classes ) ? array() : (array) $item->classes;
			$classes[] = 'menu-item-' . $item->ID;

			$fa_class = '';
			foreach ( $classes as $key => $class ) {

				if ( strpos( $class, 'fa' ) !== false ) {
					unset( $classes[ $key ] );// remove from list fa, fa-xyz.

					if ( strpos( $class, 'fa-' ) !== false ) {
						$fa_class = $class;
					}
				}
			}

			$fa_item = '';

			if ( $fa_class ) {
				$fa_item = "<i class='fa {$fa_class}'></i>";
			}

			$class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args, $depth ) );

			if ( isset( $args->has_children ) ) {
				$class_names .= ' treeview';
			}

			//if ( in_array( 'current-menu-item', $classes, true ) ) {
				//$class_names .= ' menu-item-active';
			// }

			$class_names = $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '';

			$id = apply_filters( 'nav_menu_item_id', 'menu-item-' . $item->ID, $item, $args, $depth );
			$id = $id ? ' id="' . esc_attr( $id ) . '"' : '';

			$output .= $indent . '<li' . $id . $value . $class_names . '>';

			$atts           = array();
			$atts['title']  = ! empty( $item->title ) ? $item->title : '';
			$atts['target'] = ! empty( $item->target ) ? $item->target : '';
			$atts['rel']    = ! empty( $item->xfn ) ? $item->xfn : '';

			// If item has_children add atts to a.
			if ( $args->has_children && 0 === $depth ) {
				$atts['href'] = '#';
			} else {
				$atts['href'] = ! empty( $item->url ) ? $item->url : '';
			}

			$atts = apply_filters( 'nav_menu_link_attributes', $atts, $item, $args );

			$attributes = '';
			foreach ( $atts as $attr => $value ) {
				if ( ! empty( $value ) ) {
					$value       = ( 'href' === $attr ) ? esc_url( $value ) : esc_attr( $value );
					$attributes .= ' ' . $attr . '="' . $value . '"';
				}
			}

			$item_output = $args->before;

			/*
			 * Glyphicons
			 * ===========
			 * Since the the menu item is NOT a Divider or Header we check the see
			 * if there is a value in the attr_title property. If the attr_title
			 * property is NOT null we apply it as the class name for the glyphicon.
			 */
			if ( 'header'== $item->attr_title  ) {
				$item_output .= '<span class="nav-title">';
			} elseif ( ! empty( $item->attr_title ) ) {
				$item_output .= '<a' . $attributes . '><span class="glyphicon ' . esc_attr( $item->attr_title ) . '"></span>&nbsp;';
			} else {
				$item_output .= '<a' . $attributes . '>';
			}

			$item_output .= $fa_item;

			$item_output .= $args->link_before . '<span>' . apply_filters( 'the_title', $item->title, $item->ID ) . '</span>' . $args->link_after;
			if( 'header' == $item->attr_title  ) {
				$item_output .='</span>';
			} else{
				$item_output .= '</a>';// ( $args->has_children && 0 === $depth ) ? ' <i class="fa fa-angle-left fa-right-align"></i></a>' : '</a>';
			}
			$item_output .= $args->after;

			$output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
		}
	}

	/**
	 * Traverse elements to create list from elements.
	 *
	 * Display one element if the element doesn't have any children otherwise,
	 * display the element and its children. Will only traverse up to the max
	 * depth and no ignore elements under that depth.
	 *
	 * This method shouldn't be called directly, use the walk() method instead.
	 *
	 * @see Walker::start_el()
	 * @since 2.5.0
	 *
	 * @param object $element Data object.
	 * @param array  $children_elements List of elements to continue traversing.
	 * @param int    $max_depth Max depth to traverse.
	 * @param int    $depth Depth of current element.
	 * @param array  $args args.
	 * @param string $output Passed by reference. Used to append additional content.
	 *
	 * @return null Null on failure with no changes to parameters.
	 */
	public function display_element( $element, &$children_elements, $max_depth, $depth, $args, &$output ) {
		if ( ! $element ) {
			return;
		}

		$id_field = $this->db_fields['id'];

		// Display this element.
		if ( is_object( $args[0] ) ) {
			$args[0]->has_children = ! empty( $children_elements[ $element->$id_field ] );
		}

		parent::display_element( $element, $children_elements, $max_depth, $depth, $args, $output );
	}

	/**
	 * Menu Fallback
	 * =============
	 * If this function is assigned to the wp_nav_menu's fallback_cb variable
	 * and a menu has not been assigned to the theme location in the WordPress
	 * menu manager the function with display nothing to a non-logged in user,
	 * and will add a link to the WordPress menu manager if logged in as an admin.
	 *
	 * @param array $args passed from the wp_nav_menu function.
	 */
	public static function fallback( $args ) {

		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		$html = '';

		if ( ! empty( $args['container'] ) ) {
			$html = '<' . $args['container'];

			if ( $args['container_id'] ) {
				$html .= ' id="' . $args['container_id'] . '"';
			}

			if ( $args['container_class'] ) {
				$html .= ' class="' . $args['container_class'] . '"';
			}

			$html .= '>';
		}

		$html .= '<ul';

		if ( $args['menu_id'] ) {
			$html .= ' id="' . $args['menu_id'] . '"';
		}

		if ( $args['menu_class'] ) {
			$html .= ' class="' . $args['menu_class'] . '"';
		}

		$html .= '>';
		$html .= '<li><a href="' . admin_url( 'nav-menus.php' ) . '">' . __( 'Füge ein Menü hinzu', 'social-portal' ) . '</a></li>';
		$html .= '</ul>';

		if ( ! empty( $args['container'] ) ) {
			$html .= '</' . $args['container'] . '>';
		}

		echo $html;
	}

}
