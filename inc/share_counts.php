<?php
//refresh share counts for current post on load
function novashare_refresh_share_counts() {

	$novashare = get_option('novashare');

	//make sure shares are enabled for the post
	if(novashare_is_post_allowed(true)) {

		global $post;

		//post modified time
		$post_time = mysql2date('U', (!empty($post->post_modified) ? $post->post_modified : $post->post_date), false);

		//curren time
		$current_time = time();

		//refresh rates
		$refresh_rates = array(
			'high' => array(
				'max'  => 43200, //12 hours
				'sets' => array(
					array('modified' => 604800, 'rate' => 7200), //7 days / 2 hours
					array('modified' => 2419200, 'rate' => 21600) //28 days / 6 hours
				)
			),
			'medium' => array(
				'max' => 86400, //24 hours
				'sets' => array(
					array('modified' => 604800, 'rate' => 21600), //7 days / 6 hours
					array('modified' => 2419200, 'rate' => 43200) //28 days / 12 hours
				)
			),
			'low' => array(
				'max' => 172800, //48 hours
				'sets' => array(
					array('modified' => 604800, 'rate' => 43200), //7 days / 12 hours
					array('modified' => 2419200, 'rate' => 86400) //28 days / 24 hours
				)
			)
		);

		//get specific rates array
		$rates = !empty($novashare['refresh_rate']) ? $refresh_rates[$novashare['refresh_rate']] : $refresh_rates['high'];

		//filter the rates if we need to
		$rates = apply_filters('novashare_filter_refresh_rates', $rates);

		if(!empty($rates['sets'])) {

			//make sure custom refresh rates are sorted correctly
			if(has_filter('novashare_filter_refresh_rates')) {
				usort($rates['sets'], function($item1, $item2) {
				    if($item1['modified'] == $item2['modified']) {
				    	return 0;
				    } 
				    return $item1['modified'] < $item2['modified'] ? -1 : 1;
				});
			}

			//loop through rate sets and stop at first match
			foreach($rates['sets'] as $key => $value) {
				if($current_time - $post_time <= $value['modified']) {
					$refresh = $value['rate'];
					break;
				}
			}
		}

		//set rate to max if no match was found
		if(empty($refresh) && !empty($rates['max'])) {
			$refresh = $rates['max'];
		}

		$post_id = is_singular() ? $post->ID : (is_home() ? (is_front_page() ? 0 : get_option('page_for_posts')) : null);

		//run the updater on load
		novashare_update_post_share_counts($post_id, $refresh);
	}
}
add_action('wp_head', 'novashare_refresh_share_counts', 10);

//update share counts for given post
function novashare_update_post_share_counts($post, $refresh = null) {

	if(!isset($post)) {
		return;
	}

	global $wpdb;
	global $novashare_failed_response;

	//set global flag to mark any failed api requests
	$novashare_failed_response = false;

	//get share counts updated time
	$share_counts_updated = $wpdb->get_row($wpdb->prepare("SELECT id, meta_value FROM {$wpdb->prefix}novashare_meta WHERE post_id = %d AND meta_key = 'share_counts_updated'", $post));

	//check time since last update
	if(empty($share_counts_updated->meta_value) || !$refresh || ((int)$share_counts_updated->meta_value < time() - $refresh)) {

		//get share counts
		$share_counts = novashare_post_share_counts($post);

		//update post share counts
		if(!empty($share_counts)) {

			//get existing row id if it exists
			$share_counts_row = $wpdb->get_var($wpdb->prepare("SELECT id FROM {$wpdb->prefix}novashare_meta WHERE post_id = %d AND meta_key = 'share_counts'", $post));

			//update/insert new share counts
			$wpdb->replace($wpdb->prefix . 'novashare_meta', array(
					'id'         => $share_counts_row,
					'post_id'    => $post,
					'meta_key'   => 'share_counts',
					'meta_value' => maybe_serialize($share_counts)
				),
				array(
					'%d',
					'%d',
					'%s',
					'%s'
				)
			);

			//overwrite existing global to make sure our local data is up to date
			global $novashare_post_share_counts;
			$novashare_post_share_counts[$post] = $share_counts;
		}

		//update/insert share counts update time
		$wpdb->replace($wpdb->prefix . 'novashare_meta', array(
				'id'         => (!empty($share_counts_updated->id) ? $share_counts_updated->id : ''),
				'post_id'    => $post,
				'meta_key'   => 'share_counts_updated',
				'meta_value' => time()
			),
			array(
				'%d',
				'%d',
				'%s',
				'%d'
			)
		);
	}
}

//return share counts for post
function novashare_post_share_counts($post) {

	if(!isset($post)) {
		return;
	}

	global $wpdb;

	$novashare = get_option('novashare');

	//get post share counts
	$share_counts = novashare_get_post_share_counts($post);
	if(empty($share_counts)) {
		$share_counts = array();
	}

	//get post permalink
	$permalink = !empty($post) ? get_permalink($post) : (is_home() ? get_post_type_archive_link('post') : '');

	$permalink = apply_filters('novashare_post_permalink', $permalink);

	//get active networks
	$active_networks = novashare_active_networks();

	//current share counts
	$fresh_share_counts = array();

	//loop through active networks
	foreach($active_networks as $key => $network) {

		//make sure network supports share counts
		if(!in_array($network,  array('twitter','facebook','pinterest','buffer','reddit','tumblr','vkontakte','yummly'))) {
			continue;
		}

		//networks that treat http + https as separate urls
		$http_https_networks = array('facebook', 'pinterest');

		//get share counts for both http + https
		if(in_array($network, $http_https_networks) && !empty($novashare['combine_http_https'])) {

			//get http + https urls
			$https_check = strpos(strtolower($permalink), 'https');
			$recovery_url_http = $https_check === 0 ? substr_replace($permalink, 'http', 0, 5) : $permalink;
			$recovery_url_https = $https_check === 0 ? $permalink : substr_replace($permalink, 'https', 0, 4);
			
			//pull share counts
			$share_count_http = novashare_network_share_count($recovery_url_http, $network);
			$share_count_https = novashare_network_share_count($recovery_url_https, $network);
			$combined_share_count = (int)$share_count_http + (int)$share_count_https;

			//set share count to combined share count
			if(!empty($combined_share_count) && $combined_share_count > 0) {
				$share_count = $combined_share_count;
			}
			else {
				$share_count = false;
			}

		}
		//only get urls given protocol 
		else {

			//get the share count
			$share_count = novashare_network_share_count($permalink, $network);
		}

		if($share_count === false) {
			continue;
		}

		//add the share count
		$fresh_share_counts[$network] = $share_count;
	}

	//filter the share counts here for share count recovery
	novashare_add_recovered_share_counts($fresh_share_counts, $active_networks, $post);

	//assign share counts
	if(!empty($fresh_share_counts)) {
		foreach($fresh_share_counts as $network => $share_count) {
			if(isset($share_counts[$network])) {
				if($share_count > (int)$share_counts[$network]) {
					$share_counts[$network] = $share_count;
				}
			}
			else {
				$share_counts[$network] = $share_count;
			}
		}
	}

	//remove share counts for inactive networks
	if(!empty($share_counts)) {
		foreach($share_counts as $network => $share_count) {
			if(!in_array($network, $active_networks) || !is_numeric($share_count) || $share_count == 0) {
				unset($share_counts[$network]);
			}
		}
	}

	return $share_counts;
}

//get stored share counts for post id
function novashare_get_post_share_counts($post_id) {

	if(!isset($post_id) || is_preview()) {
		return;
	}

	global $novashare_post_share_counts;

	if(isset($novashare_post_share_counts[$post_id]) || (is_array($novashare_post_share_counts) && array_key_exists($post_id, $novashare_post_share_counts))) {
		return $novashare_post_share_counts[$post_id];
	}

	global $wpdb;

	$novashare_post_share_counts[$post_id] = maybe_unserialize($wpdb->get_var($wpdb->prepare("SELECT meta_value FROM {$wpdb->prefix}novashare_meta WHERE post_id = %d AND meta_key = 'share_counts'", $post_id)));

	return $novashare_post_share_counts[$post_id];
}

//pull share count for permalink + network
function novashare_network_share_count($permalink = '', $network = '') {

	if(empty($permalink) || empty($network)) {
		return false;
	}

	$novashare = get_option('novashare');
	$permalink = urlencode($permalink);

	switch($network) {

		case 'twitter': {
			if(!empty($novashare['twitter_counts'])) {
				if(empty($novashare['twitter_count_service'])) {
					$url = 'https://counts.twitcount.com/counts.php?url=' . $permalink;
				}
				else {
					$url = 'https://opensharecount.com/count.json?url=' . $permalink;
				}
			}
			break;
		}
		case 'facebook': {
			if(!empty($novashare['facebook_app_access_token'])) {
				$url = 'https://graph.facebook.com/v12.0/?id=' . $permalink . '&access_token=' . urlencode($novashare['facebook_app_access_token'])  . '&fields=engagement';
			}
			break;
		}
		case 'pinterest': {
			$url = 'https://widgets.pinterest.com/v1/urls/count.json?source=6&url=' . $permalink;
			break;
		}
		case 'buffer': {
			$url = 'https://api.bufferapp.com/1/links/shares.json?url=' . $permalink;
			break;
		}
		case 'reddit': {
			$url = 'https://www.reddit.com/api/info.json?url=' . $permalink;
			break;
		}
		case 'tumblr': {
			$url = 'https://api.tumblr.com/v2/share/stats?url=' . $permalink;
			break;
		}
		case 'vkontakte': {
			$url = 'https://vk.com/share.php?act=count&index=1&url=' . $permalink;
			break;
		}
		case 'yummly': {
			$url = 'https://www.yummly.com/services/yum-count?url=' . $permalink;
			break;
		}
		default: {
			break;
		}
	}

	if(!empty($url)) {

		//get api response
		$response = wp_remote_get($url, array('timeout' => 5));

		//response wasn't successful
		if(wp_remote_retrieve_response_code($response) != 200) {

			//set failed response global to prevent storing recovery urls
			global $novashare_failed_response;
			$novashare_failed_response = true;
		}
		//proceed and parse the share count response
		else {

			$body = json_decode(wp_remote_retrieve_body($response), true);

			switch($network) {

				case 'facebook': {
					$facebook_share_count = 0;
					if(!empty($body['engagement']['share_count'])) {
						$facebook_share_count = $facebook_share_count + $body['engagement']['share_count'];
					}
					if(!empty($body['engagement']['reaction_count'])) {
						$facebook_share_count = $facebook_share_count + $body['engagement']['reaction_count'];
					}
					if(!empty($body['engagement']['comment_count'])) {
						$facebook_share_count = $facebook_share_count + $body['engagement']['comment_count'];
					}
					if($facebook_share_count > 0) {
						$share_count = $facebook_share_count;
					}

					break;
				}
				case 'pinterest': {
					$body = wp_remote_retrieve_body($response);
					$start = strpos($body, '(');
					$end = strpos($body, ')', $start + 1);
					$length = $end - $start;
					$body = json_decode(substr($body, $start + 1, $length - 1), true);
					if(!empty($body['count'])) {
						$share_count = $body['count'];
					}

					break;
				}
				case 'buffer': {
					if(!empty($body['shares'])) {
						$share_count = $body['shares'];
					}

					break;
				}
				case 'reddit': {
					$reddit_share_count = 0;
					if(!empty($body['data']['children'])) {
						foreach($body['data']['children'] as $child) {
							if(!empty( $child['data']['score'])) {
								$reddit_share_count = $reddit_share_count + $child['data']['score'];
							}
						}	
					}
					if($reddit_share_count > 0) {
						$share_count = $reddit_share_count;
					}

					break;
				}
				case 'tumblr': {
					if(!empty($body['response']['note_count'])) {
						$share_count = $body['response']['note_count'];
					}

					break;
				}
				case 'vkontakte': {
					$body = wp_remote_retrieve_body($response);
					$start = strpos($body, '(');
					$end = strpos($body, ')', $start + 1);
					$length = $end - $start;
					$vk_shares = array_map('trim', explode(',', substr($body, $start + 1, $length - 1)));

					if(!empty($vk_shares[1])) {
						$share_count = $vk_shares[1];
					}

					break;
				}
				default: {
					if(!empty($body['count'])) {
						$share_count = $body['count'];
					}

					break;
				}
			}
			if(!empty($share_count)) {
				return $share_count;
			}
		}
	}
	return false;
}

//check if social shares are allowed to display on specific post
function novashare_is_post_allowed($counts = false) {

	if($counts && is_preview()) {
		return false;
	}

	global $post;

	$novashare = get_option('novashare');

	if(is_singular() && isset($post) && !empty($post->ID) && !empty($post->post_type)) {

		$post_types = array();
		$active_locations = array();

		//loop through all locations
		$locations = array('inline', 'floating');

		foreach($locations as $location) {
			if(!empty($novashare[$location]['enabled']) && !empty($novashare[$location]['post_types']) && (!$counts || !empty($novashare[$location]['total_share_count']) || !empty($novashare[$location]['network_share_counts']))) {
				$post_types = array_merge($post_types, $novashare[$location]['post_types']);
				array_push($active_locations, $location);
			}
		}

		if((!empty($post_types) && in_array($post->post_type, $post_types)) || (!empty($novashare['floating']['enabled']) && !empty($novashare['floating']['home_page']) && is_front_page())) {

			//get existing details row
			$details = novashare_get_post_details($post->ID);

			//make sure locations aren't hidden for post
			foreach($active_locations as $active_location) {
				if(empty($details['hide_' . $active_location])) {
					return true;
				}
			}
		}
	}
	elseif(!empty($novashare['floating']['enabled'])) {

		if(is_home() && !empty($novashare['floating']['posts_page']) && (!$counts || !empty($novashare['floating']['total_share_count']) || !empty($novashare['floating']['network_share_counts']))) {
			return true;
		}

		if(is_archive() && !empty($novashare['floating']['archives']) && $counts == false) {
			return true;
		}
	}
	
	return false;
}