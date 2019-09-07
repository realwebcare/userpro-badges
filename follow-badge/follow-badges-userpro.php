<?php
/**
 * Addon Name: UserPro Follow Badge Integration
 */

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

if ( function_exists('userpro_init') ) :

 //*************** Actions ***************\\
 add_action("userpro_sc_after_follow", "real_get_badges_real_up_follow", 1, 1);
 add_action("userpro_sc_after_unfollow", "real_get_badges_real_up_follow", 1, 1);

 //*************** Add or Remove Badge to User ***************\\
 function real_get_badges_real_up_follow($args){

	// Must be logged in
	if ( ! is_user_logged_in() ) return;

	$follower_id = $args['from'];	// who has clicked on follow button
	$following_id = $args['to'];	// who has been followed

	// I am following
	$user_follower_count = get_user_meta($follower_id, '_userpro_following_ids', true);	
	if($user_follower_count) {
		$user_follow = count($user_follower_count);
	} else {
		$user_follow = 0;
	}

	// Someone else is following me
	$user_following_count = get_user_meta($following_id, '_userpro_followers_ids', true);
	if($user_following_count) {
		$user_following = count($user_following_count);
	} else {
		$user_following = 0;
	}

	$user_followers = get_user_meta( $follower_id, '_userpro_badges', true );
	$following_users = get_user_meta( $following_id, '_userpro_badges', true );

	$badge_follow_id = '1501';
	$badge_following_id = '1601';
    
	/**
	 * Change this condition according to your requirements.
	 * Here checking how many times
	 * a user is following other users
	 * counting from 10 to 150+
	 * and giving badges according to achived points
	 */
	if($user_follow > 9) {
		if($user_follow >= 150) {
			$badge_url = 'https://www.YOUR_DOMAIN.com/wp-content/plugins/userpro/addons/badges/badges/following_5.png';
			$badge_title = __('Follower Level 5: The user is following 150 or more users', 'real_blog');
		} elseif($user_follow >= 100) {
			$badge_url = 'https://www.YOUR_DOMAIN.com/wp-content/plugins/userpro/addons/badges/badges/following_4.png';
			$badge_title = __('Follower Level 4: The user is following 100 or more users', 'real_blog');
		} elseif($user_follow >= 50) {
			$badge_url = 'https://www.YOUR_DOMAIN.com/wp-content/plugins/userpro/addons/badges/badges/following_3.png';
			$badge_title = __('Follower Level 3: The user is following 50 or more users', 'real_blog');
		} elseif($user_follow >= 25) {
			$badge_url = 'https://www.YOUR_DOMAIN.com/wp-content/plugins/userpro/addons/badges/badges/following_2.png';
			$badge_title = __('Follower Level 2: The user is following 25 or more users', 'real_blog');
		} elseif($user_follow >= 10) {
			$badge_url = 'https://www.YOUR_DOMAIN.com/wp-content/plugins/userpro/addons/badges/badges/following_1.png';
			$badge_title = __('Follower Level 1: The user is following 10 or more users', 'real_blog');
		}
	} else {
		$badge_url = '';
		$badge_title = '';
	}

	// find if that badge exists
	if (is_array($user_followers)){
		foreach($user_followers as $k => $badge){
			if( isset($badge['badge_id']) ){
				if ( $badge['badge_id'] == $badge_follow_id ) {
					unset($user_followers[$k]);
				}
			}
		}
		update_user_meta($follower_id, '_userpro_badges', $user_followers);
	}
	else {
		$user_followers = array();
	}

	if($badge_url != '') {
		// add new badge to user
		$user_followers[] = array(
			'badge_url'		=> $badge_url,
			'badge_title'	=> $badge_title,
			'badge_id'		=> $badge_follow_id
		);
		update_user_meta($follower_id, '_userpro_badges', $user_followers);
	}
    
	/**
	 * Change this condition according to your requirements.
	 * Here checking how many times
	 * a user is being followed by other users
	 * counting from 10 to 150+
	 * and giving badges according to achived points
	 */
	if($user_following > 9) {
		if($user_following >= 150) {
			$badge_url_two = 'https://www.YOUR_DOMAIN.com/wp-content/plugins/userpro/addons/badges/badges/follower_5.png';
			$badge_title_two = __('Following Level 5: The user has 150 or more followers', 'real_blog');
		} elseif($user_following >= 100) {
			$badge_url_two = 'https://www.YOUR_DOMAIN.com/wp-content/plugins/userpro/addons/badges/badges/follower_4.png';
			$badge_title_two = __('Following Level 4: The user has 100 or more followers', 'real_blog');
		} elseif($user_following >= 50) {
			$badge_url_two = 'https://www.YOUR_DOMAIN.com/wp-content/plugins/userpro/addons/badges/badges/follower_3.png';
			$badge_title_two = __('Following Level 3: The user has 50 or more followers', 'real_blog');
		} elseif($user_following >= 25) {
			$badge_url_two = 'https://www.YOUR_DOMAIN.com/wp-content/plugins/userpro/addons/badges/badges/follower_2.png';
			$badge_title_two = __('Following Level 2: The user has 25 or more followers', 'real_blog');
		} elseif($user_following >= 10) {
			$badge_url_two = 'https://www.YOUR_DOMAIN.com/wp-content/plugins/userpro/addons/badges/badges/follower_1.png';
			$badge_title_two = __('Following Level 1: The user has 10 or more followers', 'real_blog');
		}
	} else {
		$badge_url_two = '';
		$badge_title_two = '';
	}

	// find if that badge exists
	if (is_array($following_users)){
		foreach($following_users as $k => $badge){
			if( isset($badge['badge_id']) ){
				if ( $badge['badge_id'] == $badge_following_id ) {
					unset($following_users[$k]);
				}
			}
		}
		update_user_meta($following_id, '_userpro_badges', $following_users);
	}
	else {
		$following_users = array();
	}

	if($badge_url_two != '') {
		// add new badge to user
		$following_users[] = array(
			'badge_url'		=> $badge_url_two,
			'badge_title'	=> $badge_title_two,
			'badge_id'		=> $badge_following_id
		);
		update_user_meta($following_id, '_userpro_badges', $following_users);
	}
 }
 
 endif;
 ?>