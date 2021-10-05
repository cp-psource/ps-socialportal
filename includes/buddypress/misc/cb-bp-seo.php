<?php
/**
 * WP SEO Compatibility
 *
 * @package    PS_SocialPortal
 * @subpackage BuddyPress
 * @copyright  Copyright (c) 2019, DerN3rd
 * @license    https://www.gnu.org/licenses/gpl.html GNU Public License
 * @author     DerN3rd
 */

// Do not allow direct access over web.
defined( 'ABSPATH' ) || exit;

/**
 * WordPress seo title fix for BuddyPress
 */
function cb_disable_wp_seo_title_filter() {

	if ( class_exists( 'WPSEO_Frontend' ) && is_buddypress() ) {
		$instance = WPSEO_Frontend::get_instance();

		if ( has_filter( 'wp_title', array( $instance, 'title' ) ) ) {
			remove_filter( 'wp_title', array( $instance, 'title' ), 15 );
		}

		if ( has_filter( 'pre_get_document_title', array( $instance, 'title' ) ) ) {
			remove_filter( 'pre_get_document_title', array( $instance, 'title' ), 15 );
		}
	}
}

add_action( 'bp_template_redirect', 'cb_disable_wp_seo_title_filter' );


/**
 * Generate Item meta for SEO.
 *
 * @param string $desc meta descriptio by yoast.
 *
 * @return string
 */
function bpdev_bp_items_metadesc( $desc ) {
	// if it is not buddypress page or buddypress directory pages let the plugin do its work.
	if ( ! is_buddypress() || bp_is_directory() ) {
		return $desc;
	}

	// we do not cover directory as directory meta can be customized from pages->Edit screen.
	// now, let us check if we are on members page.
	if ( bp_is_user() ) {

		// what should me the description,
		// I am going to put it like Profile & Recent activities of [user_dsplay_name] on the site [sitename].
		// if you are creative,
		// you can use some xprofile field and xprofile_get_field_data to make it better.
		$desc = sprintf( "%s's Profile & Recent activities on %s", bp_get_displayed_user_fullname(), get_bloginfo( 'name' ) );

		// here we can do it based on each of the component or action using bp_is_current_component('component name') && bp_is_current_action('action_name')
		// here I am showing an example for BuddyBlog component
		// on buddyblog single post.
		if ( function_exists( 'buddyblog_is_single_post' ) && buddyblog_is_single_post() ) {
			$post_id = 0;

			if ( buddyblog_use_slug_in_permalink() ) {
				$slug    = bp_action_variable( 0 );
				$post_id = buddyblog_get_post_id_from_slug( $slug );
			} else {
				$post_id = intval( bp_action_variable( 0 ) );
			}

			if ( $post_id ) {
				$desc = WPSEO_Meta::get_value( 'metadesc', $post_id );
			}
		}

		// I have another strategy that I will propose at the end of this post to make it super easy.
	} elseif ( bp_is_active( 'groups' ) && bp_is_group() ) {
		// for single group.
		// let us use group description.
		$post_id = false;
		// by default, use group description.
		$group = groups_get_current_group();
		$desc  = $group->description;

		// are we looking for forum?
		if ( bp_is_current_action( 'forum' ) && function_exists( 'psforum' ) ) {

			// we will get an array of ids.
			$forum_ids = psf_get_group_forum_ids();

			if ( $forum_ids ) {
				$post_id = array_pop( $forum_ids );
			}

			// check if we are at single topic.
			if ( bp_is_action_variable( 'topic', 0 ) && bp_action_variable( 1 ) ) {
				// we are on single topic, get topic id
				// get the topic as post.
				$topics = get_posts( array(
					'name'      => bp_action_variable( 1 ),
					'post_type' => psf_get_topic_post_type(),
					'per_page'  => 1,
				) );
				// get the id.
				if ( ! empty( $topics ) ) {
					$post_id = $topics[0]->ID;
				}
			}
		} // end of forum post finding.

		// if the post id is given.
		if ( $post_id ) {
			$desc = WPSEO_Meta::get_value( 'metadesc', $post_id );
		}
		// check if the forum is active and get the current post id /meta.
	}

	return $desc;
}

add_filter( 'wpseo_metadesc', 'bpdev_bp_items_metadesc' );