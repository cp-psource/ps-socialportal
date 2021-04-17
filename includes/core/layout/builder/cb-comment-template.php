<?php
/**
 * Comment Template modifications.
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
 * Applies cb customisations to the post comment form.
 *
 * @param array $default_labels The default options for strings, fields etc in the form.
 *
 * @see comment_form()
 * @return array
 */
function cb_filter_comment_form_defaults( $default_labels ) {

	$commenter = wp_get_current_commenter();
	$req       = get_option( 'require_name_email' );
	$aria_req  = ( $req ? " aria-required='true'" : '' );
	$fields = array(
		'author' => '<p class="comment-form-author">' . '<label for="author">' . __( 'Name', 'social-portal' ) . ( $req ? '<span class="required"> *</span>' : '' ) . '</label> ' .
		            '<input id="author" name="author" type="text" value="' . esc_attr( $commenter['comment_author'] ) . '" size="30"' . $aria_req . ' /></p>',
		'email'  => '<p class="comment-form-email"><label for="email">' . __( 'Email', 'social-portal' ) . ( $req ? '<span class="required"> *</span>' : '' ) . '</label> ' .
		            '<input id="email" name="email" type="text" value="' . esc_attr( $commenter['comment_author_email'] ) . '" size="30"' . $aria_req . ' /></p>',
		'url'    => '<p class="comment-form-url"><label for="url">' . __( 'Webseite', 'social-portal' ) . '</label>' .
		            '<input id="url" name="url" type="text" value="' . esc_attr( $commenter['comment_author_url'] ) . '" size="30" /></p>',
	);

	$new_labels = array(
		'comment_field' => '<p class="form-textarea"><textarea name="comment" id="comment" cols="60" rows="10" aria-required="true"></textarea></p>',
		'fields'        => apply_filters( 'comment_form_default_fields', $fields ),
		'logged_in_as'  => '',
		/* translators: %s: login url */
		'must_log_in'   => '<p class="alert">' . sprintf( __( 'Du musst <a href="%1$s">angemeldet sein</a>, um einen Kommentar abgeben zu können.', 'social-portal' ), wp_login_url( get_permalink() ) ) . '</p>',
		'title_reply'   => __( 'Leave a reply', 'social-portal' ),
	);

	return apply_filters( 'cb_comment_form_defaults', array_merge( $default_labels, $new_labels ) );
}

add_filter( 'comment_form_defaults', 'cb_filter_comment_form_defaults', 10 );
