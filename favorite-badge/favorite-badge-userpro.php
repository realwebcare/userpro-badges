<?php
/**
 * Addon Name: WP Favorite Posts Integration with UserPro
 */

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

/**
 * Get the number of posts a specific user has favorited
 * @plugin wp favorite posts
 * @param $user_id int, defaults to current user
 * @return int
 */
function get_user_total_fav_posts($userid) {
	$all_users = get_users();
	$wp_favorite_tips = 0;
	foreach( $all_users as $user ) {
		$t4b_users[] = $user->ID;
	}
	foreach( $t4b_users as $user_id ) {
		$user_favorites = get_user_meta($user_id, 'wpfp_favorites', true);
		if($user_favorites) {
			foreach( $user_favorites as $post_id ) {
				$author_id = get_post_field( 'post_author', $post_id );
				if($author_id == $userid) {
					$wp_favorite_tips++;
				}
			}
		}
	}
	return $wp_favorite_tips;
}

/**
 * Get the badges of users or authors for
 * @plugin wp favorite posts
 * @param $user_id int, defaults to current user
 * @param $user_badges,$author_badges,
 * userpro badges to current user
 * @param $user_favorites,$author_favorites,
 * favorite posts count to current user
 */
add_action( 'wpfp_after_add', 'real_get_user_fav_posts_badges' );
add_action( 'wpfp_after_remove', 'real_get_user_fav_posts_badges' );
function real_get_user_fav_posts_badges($post_id = NULL) {
	global $post;

	// Must be logged in
	if ( ! is_user_logged_in() ) return;

	if($post_id == NULL)
		$post_id = $post->ID;

	$post    = get_post( $post_id );
	$user_id = get_current_user_id();
	$author_id = $post->post_author;

	$user_favorite_posts = get_user_meta($user_id, 'wpfp_favorites', true);
	if($user_favorite_posts) {
		$user_favorites = count($user_favorite_posts);
	} else {
		$user_favorites = 0;
	}
	$author_favorites = get_user_total_fav_posts($author_id);
	$user_badges = get_user_meta( $user_id, '_userpro_badges', true );
	$author_badges = get_user_meta( $author_id, '_userpro_badges', true );

	if($user_id != $author_id) {
		$badge_id = '1701';
		$auth_badge_id = '1801';
    
		/**
		 * Change this condition according to your requirements.
		 * Here checking how many times
		 * a user has favorited posts
		 * counting from 10 to 150+
		 * and giving badges according to achived points
		 */
		if($user_favorites > 9) {
			if($user_favorites >= 150) {
				$badge_url = 'https://www.YOUR_DOMAIN.com/wp-content/plugins/userpro/addons/badges/badges/fav_user_5.png';
				$badge_title = __('Favorite User 5: The user has 150 or more favorite tips', 'real_blog');
			} elseif($user_favorites >= 100) {
				$badge_url = 'https://www.YOUR_DOMAIN.com/wp-content/plugins/userpro/addons/badges/badges/fav_user_4.png';
				$badge_title = __('Favorite User 4: The user has 100 or more favorite tips', 'real_blog');
			} elseif($user_favorites >= 50) {
				$badge_url = 'https://www.YOUR_DOMAIN.com/wp-content/plugins/userpro/addons/badges/badges/fav_user_3.png';
				$badge_title = __('Favorite User 3: The user has 50 or more favorite tips', 'real_blog');
			} elseif($user_favorites >= 25) {
				$badge_url = 'https://www.YOUR_DOMAIN.com/wp-content/plugins/userpro/addons/badges/badges/fav_user_2.png';
				$badge_title = __('Favorite User 2: The user has 25 or more favorite tips', 'real_blog');
			} elseif($user_favorites >= 10) {
				$badge_url = 'https://www.YOUR_DOMAIN.com/wp-content/plugins/userpro/addons/badges/badges/fav_user_1.png';
				$badge_title = __('Favorite User 1: The user has 10 or more favorite tips', 'real_blog');
			}
		} else {
			$badge_url = '';
			$badge_title = '';
		}

		// find if that badge exists
		if (is_array($user_badges)){
			foreach($user_badges as $k => $badge){
				if( isset($badge['badge_id']) ){
					if ( $badge['badge_id'] == $badge_id ) {
						unset($user_badges[$k]);
					}
				}
			}
			update_user_meta($user_id, '_userpro_badges', $user_badges);
		}
		else {
			$user_badges = array();
		}

		if($badge_url != '') {
			// add new badge to user
			$user_badges[] = array(
				'badge_url'		=> $badge_url,
				'badge_title'	=> $badge_title,
				'badge_id'		=> $badge_id
			);
			update_user_meta($user_id, '_userpro_badges', $user_badges);
		}
    
		/**
		 * Change this condition according to your requirements.
		 * Here checking how many times
		 * a author has achieved favorite counts
		 * counting from 10 to 150+
		 * and giving badges according to achived points
		 */
		if($author_favorites > 9) {
			if($author_favorites >= 150) {
				$auth_badge_url = 'https://www.YOUR_DOMAIN.com/wp-content/plugins/userpro/addons/badges/badges/fav_tipser_5.png';
				$auth_badge_title = __('Favorite Author 5: The author has achieved 150 or more favorite counts', 'real_blog');
			} elseif($author_favorites >= 100) {
				$auth_badge_url = 'https://www.YOUR_DOMAIN.com/wp-content/plugins/userpro/addons/badges/badges/fav_tipser_4.png';
				$auth_badge_title = __('Favorite Author 4: The author has achieved 100 or more favorite counts', 'real_blog');
			} elseif($author_favorites >= 50) {
				$auth_badge_url = 'https://www.YOUR_DOMAIN.com/wp-content/plugins/userpro/addons/badges/badges/fav_tipser_3.png';
				$auth_badge_title = __('Favorite Author 3: The author has achieved 50 or more favorite counts', 'real_blog');
			} elseif($author_favorites >= 25) {
				$auth_badge_url = 'https://www.YOUR_DOMAIN.com/wp-content/plugins/userpro/addons/badges/badges/fav_tipser_2.png';
				$auth_badge_title = __('Favorite Author 2: The author has achieved 25 or more favorite counts', 'real_blog');
			} elseif($author_favorites >= 10) {
				$auth_badge_url = 'https://www.YOUR_DOMAIN.com/wp-content/plugins/userpro/addons/badges/badges/fav_tipser_1.png';
				$auth_badge_title = __('Favorite Author 1: The author has achieved 10 or more favorite counts', 'real_blog');
			}
		} else {
			$auth_badge_url = '';
			$auth_badge_title = '';
		}

		// find if that badge exists
		if (is_array($author_badges)){
			foreach($author_badges as $k => $badge){
				if( isset($badge['badge_id']) ){
					if ( $badge['badge_id'] == $auth_badge_id ) {
						unset($author_badges[$k]);
					}
				}
			}
			update_user_meta($author_id, '_userpro_badges', $author_badges);
		}
		else {
			$author_badges = array();
		}

		if($auth_badge_url != '') {
			// add new badge to user
			$author_badges[] = array(
				'badge_url'		=> $auth_badge_url,
				'badge_title'	=> $auth_badge_title,
				'badge_id'		=> $auth_badge_id
			);
			update_user_meta($author_id, '_userpro_badges', $author_badges);
		}
	}
}
?>
