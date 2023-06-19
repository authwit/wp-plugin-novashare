<?php
//add meta boxes
function novashare_add_meta_boxes() {

	$novashare = get_option('novashare');

	if(!empty($novashare['hide_meta'])) {
		return;
	}

	global $wpdb;
	global $post;

	//get public custom post types
	$custom_post_types = get_post_types(array('public' => true, '_builtin' => false));

	//add the built in post types we want
	$post_types = array_merge(array('post' => 'post', 'page' => 'page'), $custom_post_types);

	//get existing details row
	$details = maybe_unserialize($wpdb->get_var($wpdb->prepare("SELECT meta_value FROM {$wpdb->prefix}novashare_meta WHERE post_id = %d AND meta_key = 'details'", $post->ID)));

	$args = array('novashare' => $details);

	//add single post novashare meta boxes
    add_meta_box('novashare_meta_details', 'Novashare: ' . __('Details', 'novashare'), 'novashare_details_meta_box', $post_types, 'normal', 'high', $args);
    add_meta_box('novashare_meta_recovery', 'Novashare: ' . __('Share Count Recovery', 'novashare'), 'novashare_recovery_meta_box', $post_types, 'normal', 'high', $args);
}
add_action('add_meta_boxes', 'novashare_add_meta_boxes', 1);

//load novashare details meta box
function novashare_details_meta_box($post, $params) {

	$novashare = get_option('novashare');

	$details = !empty($params['args']['novashare']) ? $params['args']['novashare'] : [];

	//noncename needed to verify where the data originated
	echo "<input type='hidden' name='novashare_meta_noncename' id='novashare_meta_noncename' value='" . wp_create_nonce(plugin_basename(__FILE__)) . "' />";

	echo "<div class='novashare-details-halves'>";

		echo "<div class='novashare-details-half'>";

			//social media image
			echo "<div class='novashare-details-input-container'>";

				if(!empty($details['social_image'])) {
					$social_thumbnail_url = wp_get_attachment_thumb_url($details['social_image']);
					$social_full_url = wp_get_attachment_url($details['social_image']);
				}

				echo "<label>" . __("Social Media Image", 'novashare') . " (1200x630)</label>";
				echo "<div class='novashare-image-upload'>";
					echo "<div class='novashare-image-upload-input'>";
						echo "<input type='hidden' id='novashare_social_image' name='novashare[social_image]' value='" . (!empty($details['social_image']) ? $details['social_image'] : '') . "' />";
						echo "<input type='text' id='novashare_social_image_url'  value='" . (!empty($social_full_url) ? $social_full_url : '') . "' class='widefat novashare-image-upload' placeholder='" . __("Upload an image for social media.", 'novashare') . "' disabled />";
					echo "</div>";
					echo "<div class='novashare-image-upload-preview" . (empty($social_thumbnail_url) ? " hidden" : "") . "'><a title='Remove'><span class='dashicons dashicons-no'></span>" . (!empty($social_thumbnail_url) ? "<img src='" . $social_thumbnail_url . "' />" : '') . "</a></div>";
					echo "<a class='novashare-image-upload-button button button-secondary' value='novashare_social_image' frame_title='" . __('Select an Image', 'novashare') . "'>" . __('Upload', 'novashare') . "</a>";
				echo "</div>";
			echo "</div>";

			//social media title
			echo "<div class='novashare-details-input-container novashare-details-char-count'>";
				echo "<label for='novashare_social_title'>" . __("Social Media Title", 'novashare') . "</label>";
				echo "<input type='text' name='novashare[social_title]' id='novashare_social_title' value='" . (!empty($details['social_title']) ? esc_html($details['social_title']) : '')  . "' class='widefat' maxlength='70' placeholder='" . __("Write a custom title for social media.", 'novashare') . "' />";
			echo "</div>";

			//social media description
			echo "<div class='novashare-details-input-container novashare-details-char-count'>";
				echo "<label for='novashare_social_description'>" . __("Social Media Description", 'novashare') . "</label>";
				echo "<textarea name='novashare[social_description]' id='novashare_social_description' class='widefat' style='height: 80px;' maxlength='200' placeholder='" . __("Write a custom description for social media.", 'novashare') . "' />" . (!empty($details['social_description']) ? esc_html($details['social_description']) : '') . "</textarea>";
			echo "</div>";

		echo "</div>";

		echo "<div class='novashare-details-half'>";

			//pinterest image
			echo "<div class='novashare-details-input-container'>";

				if(!empty($details['pinterest_image'])) {
					$pinterest_thumbnail_url = wp_get_attachment_thumb_url($details['pinterest_image']);
					$pinterest_full_url = wp_get_attachment_url($details['pinterest_image']);
				}

				echo "<label for='novashare_pinterest_image'>" . __("Pinterest Image", 'novashare') . " (800x1200)</label>";
				echo "<div class='novashare-image-upload'>";
					echo "<div class='novashare-image-upload-input'>";
						echo "<input type='hidden' id='novashare_pinterest_image' name='novashare[pinterest_image]' value='" . (!empty($details['pinterest_image']) ? $details['pinterest_image'] : '') . "' />";
						echo "<input type='text' id='novashare_pinterest_image_url' value='" . (!empty($pinterest_full_url) ? $pinterest_full_url : '') . "' class='widefat novashare-image-upload' placeholder='" . __("Upload an image for Pinterest.", 'novashare') . "' disabled />";
					echo "</div>";
					echo "<div class='novashare-image-upload-preview" . (empty($pinterest_thumbnail_url) ? " hidden" : "") . "'><a title='Remove'><span class='dashicons dashicons-no'></span>" . (!empty($pinterest_thumbnail_url) ? "<img src='" . $pinterest_thumbnail_url . "' />" : '') . "</a></div>";
					echo "<a class='novashare-image-upload-button button button-secondary' value='novashare_pinterest_image' frame_title='" . __('Select an Image', 'novashare') . "'>" . __('Upload', 'novashare') . "</a>";
				echo "</div>";
			echo "</div>";

			//deprecated fields, moved to open graph
			echo '<div' . (!apply_filters('novashare_show_deprecated', false) ? ' style="display: none;"' : '') . '>';

				//pinterest title
				echo "<div class='novashare-details-input-container novashare-details-char-count'>";
					echo "<label for='novashare_pinterest_title'>" . __("Pinterest Title", 'novashare') . " (" . __('Deprecated', 'novashare') . ")</label>";
					echo "<input type='text' name='novashare[pinterest_title]' id='novashare_pinterest_title' value='" . (!empty($details['pinterest_title']) ? esc_html($details['pinterest_title']) : '')  . "' class='widefat' maxlength='70' placeholder='" . __("Write a custom title for Pinterest.", 'novashare') . "' />";
				echo "</div>";

				//pinterest description
				echo "<div class='novashare-details-input-container novashare-details-char-count'>";
					echo '<label for="novashare_pinterest_description">' . __('Pinterest Description', 'novashare') . ' (' . __('Deprecated', 'novashare') . ')</label>';
					echo "<textarea name='novashare[pinterest_description]' id='novashare_pinterest_description' class='widefat' style='height: 80px;' maxlength='500' placeholder='" . __("Write a custom description for Pinterest.", 'novashare') . "' />" . (!empty($details['pinterest_description']) ? esc_html($details['pinterest_description']) : '') . "</textarea>";
				echo "</div>";

			echo "</div>";

			//pinterest hidden images
			echo '<div class="novashare-details-input-container">';
				echo '<label style="display: block; margin-bottom: 4px;">' . __('Pinterest Hidden Images', 'novashare') . '</label>';
				echo '<div id="novashare-pinterest-hidden-images-container" style="display: flex; flex-wrap: wrap;">';

					if(!empty($details['pinterest_hidden_images'])) {

						foreach($details['pinterest_hidden_images'] as $attachment_id) {

							$thumbnail_url = wp_get_attachment_thumb_url($attachment_id);

							if($thumbnail_url) {
								echo '<div id="novashare-pinterest-hidden-image-' . $attachment_id . '" class="novashare-pinterest-hidden-image">';
									echo '<input type="hidden" name="novashare[pinterest_hidden_images][]" value="' . $attachment_id . '">';
									echo '<img src="' . $thumbnail_url . '">';
									echo '<span class="dashicons dashicons-no"></span>';
								echo '</div>';
							}
						}
					}

				echo '</div>';
				echo "<a id='novashare-pinterest-hidden-images-upload' class='button button-secondary' value='novashare_hidden_pinterest_images' frame_title='" . __('Select Images', 'novashare') . "'>" . __('Add Images', 'novashare') . "</a>";
			echo '</div>';
		echo "</div>";
	echo "</div>";

	echo "<div class='novashare-details-halves'>";

		echo "<div class='novashare-details-half'>";

			echo '<div id="novashare-details-checkboxes">';

				//hide inline for post
				echo '<label for="novashare_hide_inline">';
					echo '<input type="checkbox" name="novashare[hide_inline]" id="novashare_hide_inline" '; if(!empty($details['hide_inline']) && $details['hide_inline'] == "1"){echo "checked";} echo ' value="1" class="widefat" />';
					echo __('Hide Inline Content', 'novashare');
				echo '</label>';

				//hide floating for post
				echo '<label for="novashare_hide_floating">';
					echo '<input type="checkbox" name="novashare[hide_floating]" id="novashare_hide_floating" '; if(!empty($details['hide_floating']) && $details['hide_floating'] == "1"){echo "checked";} echo ' value="1" class="widefat" />';
					echo __('Hide Floating Bar', 'novashare');
				echo '</label>';

				//disable pinterest image pins for post
				echo '<label for="novashare_disable_image_pins"' . (empty($novashare['pinterest']['image_pins']) ? ' style="display: none;"' : '') . '>';
					echo '<input type="checkbox" name="novashare[disable_image_pins]" id="novashare_disable_image_pins" '; if(!empty($details['disable_image_pins']) && $details['disable_image_pins'] == "1"){echo "checked";} echo ' value="1" class="widefat" />';
					echo __('Disable Image Pins', 'novashare');
				echo '</label>';

			echo "</div>";

		echo "</div>";

		echo "<div class='novashare-details-half' style='text-align: right;'>";

			//refresh sharecounts button
			echo "<div class='novashare-refresh-share-counts'>";

				echo "<span class='spinner'></span>";
				echo "<span class='dashicons dashicons-yes'></span>";
				echo "<a class='button button-secondary' id='novashare-refresh-share-counts' value='1'>" . __('Refresh Share Counts', 'novashare') . "</a>";
				echo wp_nonce_field('novashare_refresh_post_share_counts', 'novashare_refresh_post_share_counts', false, false);

			echo "</div>";

			//documentation link
			echo "<a href='https://novashare.io/docs/post-meta-details/' class='novashare-details-tooltip' title='" . __('View Documentation', 'novashare') . "' target='_blank'>?</a>";

		echo "</div>";
	echo "</div>";
}

//load share count recovery meta box
function novashare_recovery_meta_box($post, $params) {

	$details = !empty($params['args']['novashare']) ? $params['args']['novashare'] : [];

	//section description
	echo "<p>" . __("Add previous URLs to recover social share counts. For example, changing a slug on a URL.", 'novashare') . "</p>";

	//recovery urls container
	echo "<div id='novashare-post-recovery-urls'>";

		$recovery_url_count = 0;

		if(!empty($details['recovery_urls'])) {

			foreach($details['recovery_urls'] as $url) {

                //print saved url
                echo "<div class='novashare-post-recovery-url'>";
                    echo "<input type='text' id='novashare-recovery-url-" . $recovery_url_count . "' name='novashare[recovery_urls][]' value='" . esc_html($url) . "' placeholder='https://domain.com/sample-post/' />";
                    echo "<a href='#' class='button button-secondary novashare-delete-recovery-url' title='" . __('Remove', 'novashare') . "'>" . __('Remove', 'novashare') . "</a>";
                echo "</div>";

                $recovery_url_count++;
            }
		}

	echo "</div>";

	//add new recovery url
	echo "<a href='#' id='novashare-add-recovery-url' rel='" . $recovery_url_count . "' class='button button-secondary'" . ($recovery_url_count > 4 ? " style='display: none;'" : "") . ">" . __('Add URL', 'novashare') . "</a>";

	//documentation link
	echo "<a href='https://novashare.io/docs/recover-post-url-social-share-counts' class='novashare-details-tooltip' style='float: right;' title='" . __('View Documentation', 'novashare') . "' target='_blank'>?</a>";

	//maximum reached message
	echo "<div id='novashare-post-recovery-max'" . ($recovery_url_count < 4 ? " style='display: none;'" : "") . ">" . __("You've reached the maximum amount of recovery URLs.", 'novashare') . "</div>";
}

//save novashare details meta box data
function novashare_save_meta($post_id, $post) {
	
	//verify this came from the our screen and with proper authorization, because save_post can be triggered at other times
	if(empty($_POST['novashare_meta_noncename']) || !wp_verify_nonce($_POST['novashare_meta_noncename'], plugin_basename(__FILE__))) {
		return $post->ID;
	}

	//Is the user allowed to edit the post or page?
	if(!current_user_can('edit_post', $post->ID)) {
		return $post->ID;
	}
		
	//save the new data
	if(isset($_POST['novashare'])) {

		$novashare_filtered = array_filter($_POST['novashare']);

		if(isset($novashare_filtered['recovery_urls'])) {
			$novashare_filtered['recovery_urls'] = array_filter($novashare_filtered['recovery_urls']);
		}

		global $wpdb;

		//get existing details row id
		$details_id = $wpdb->get_var($wpdb->prepare("SELECT id FROM {$wpdb->prefix}novashare_meta WHERE post_id = %d AND meta_key = 'details'", $post->ID));

		//prevent adding empty row before data was entered
		if(!empty($novashare_filtered) || !empty($details_id)) {

			//update/insert new details row
			$wpdb->replace($wpdb->prefix . 'novashare_meta', array(
					'id'         => $details_id,
					'post_id'    => $post->ID,
					'meta_key'   => 'details',
					'meta_value' => maybe_serialize(stripslashes_deep(array_filter($novashare_filtered)))
				),
				array(
					'%d',
					'%d',
					'%s',
					'%s'
				)
			);
		}
	}
}
add_action('save_post', 'novashare_save_meta', 1, 2);

//ajax function to refresh share counts
function novashare_refresh_post_share_counts() {

	if(empty($_POST['action']) || empty($_POST['nonce']) || empty($_POST['post_id'])) {
		return;
	}

	if($_POST['action'] != 'novashare_refresh_post_share_counts') {
		return;
	}

	if(!wp_verify_nonce($_POST['nonce'], 'novashare_refresh_post_share_counts')) {
		return;
	}

	$post_id = (int)$_POST['post_id'];
	$post = get_post($post_id);

	if(!in_array($post->post_status, array('future', 'draft', 'pending', 'trash', 'auto-draft'))) {

		global $wpdb;

		//clear existing post share counts
		$wpdb->update($wpdb->prefix . 'novashare_meta', array(
				'meta_value' => ''
			),
			array(
				'post_id'  => $post_id,
				'meta_key' => 'share_counts'
			)
		);

		//clear existing post recovery share counts
		$wpdb->update($wpdb->prefix . 'novashare_meta', array(
				'meta_value' => ''
			),
			array(
				'post_id'  => $post_id,
				'meta_key' => 'recovery_share_counts'
			)
		);

		//clear existing post recovery urls
		$wpdb->update($wpdb->prefix . 'novashare_meta', array(
				'meta_value' => ''
			),
			array(
				'post_id'  => $post_id,
				'meta_key' => 'recovery_urls'
			)
		);

		novashare_update_post_share_counts($post_id);
	}

	wp_die();
}
add_action('wp_ajax_novashare_refresh_post_share_counts', 'novashare_refresh_post_share_counts');

//print meta tags on front end
function novashare_opengraph_meta_tags() {

	global $wpdb;
	global $post;

	if(!$post || !is_singular()) {
		return;
	}

	//get plugin settings
	$novashare = get_option('novashare');

	if(empty($novashare['open_graph'])) {
		return;
	}

	//get existing details row
	$details = novashare_get_post_details($post->ID);

	//grab title + description
	$novashare_title = apply_filters('novashare_meta_title', !empty($details['social_title']) ? $details['social_title'] : $post->post_title);
	$novashare_description = wp_strip_all_tags(!empty($details['social_description']) ? $details['social_description'] : (strlen(strip_shortcodes($post->post_content)) > 200 ? substr(strip_shortcodes($post->post_content), 0, 197) . "..." : strip_shortcodes($post->post_content)));

	$novashare_image = '';
	if(!empty($details['social_image'])) {
		$novashare_image = wp_get_attachment_image_src($details['social_image'], 'full');
	}
	elseif(!empty($novashare['default_social_image']) && !has_post_thumbnail()) {
		$novashare_image = wp_get_attachment_image_src($novashare['default_social_image'], 'full');
	}

	$output = '';

	$seo_plugin = false;

	//Yoast SEO changes
	if(defined('WPSEO_VERSION')) {

		$seo_plugin = true;

		//replace title
		add_filter('wpseo_opengraph_title', function($title) use ($details) {
			return !empty($details['social_title']) ? apply_filters('novashare_meta_title', $details['social_title']) : $title;
		}, 10, 1);

		//replace description
		add_filter('wpseo_opengraph_desc', function($description) use ($details) {
			return !empty($details['social_description']) ? $details['social_description'] : $description;
		}, 10, 1);

		//remove original image tags
		if($novashare_image && is_array($novashare_image)) {

			//depcrecated
			add_filter('wpseo_opengraph_image', '__return_false');

			//current
			add_filter('wpseo_twitter_image', '__return_false');
			add_filter('wpseo_frontend_presenter_classes', function($filter) {
				if(($key = array_search('Yoast\WP\SEO\Presenters\Open_Graph\Image_Presenter', $filter)) !== false) {
					unset($filter[$key]);
				}
				return $filter;
			});
		}

		//replace twitter title
		add_filter('wpseo_twitter_title', function($title) use ($details) {
			return !empty($details['social_title']) ? apply_filters('novashare_meta_title', $details['social_title']) : $title;
		}, 10, 1);

		//replace twitter description
		add_filter('wpseo_twitter_description', function($description) use ($details) {
			return !empty($details['social_description']) ? $details['social_description'] : $description;
		}, 10, 1);
	}
	
	//All in One SEO changes 
	if(defined('AIOSEO_VERSION')) {

		$seo_plugin = true;

		add_filter('aioseo_facebook_tags', function($meta) use ($details, $novashare_image) {

			//og title
			if(isset($meta['og:title']) || !empty($details['social_title'])) {
				$meta['og:title']  = !empty($details['social_title']) ? apply_filters('novashare_meta_title', $details['social_title']) : $meta['og:title'];
			}

			//og description
			if(isset($meta['og:description']) || !empty($details['social_description'])) {
			    $meta['og:description'] = !empty($details['social_description']) ? $details['social_description'] : $meta['og:description'];
			}

		    //remove original image tags
			if($novashare_image && is_array($novashare_image)) {
				unset($meta['og:image'], $meta['og:image:secure_url'], $meta['og:image:width'], $meta['og:image:height']);
			}

			return $meta;
		}, 10, 1);

		add_filter('aioseo_twitter_tags', function($meta) use($details, $novashare_image) {

			//twitter title
			if(isset($meta['twitter:title']) || !empty($details['social_title'])) {
				$meta['twitter:title'] = !empty($details['social_title']) ? apply_filters('novashare_meta_title', $details['social_title']) : $meta['twitter:title'];
			}

			//twitter description
			if(isset($meta['twitter:description']) || !empty($details['social_description'])) {
				$meta['twitter:description'] = !empty($details['social_description']) ? $details['social_description'] : $meta['twitter:description'];
			}

			//remove original image tag
			if($novashare_image && is_array($novashare_image)) {
				unset($meta['twitter:image']);
			}

			return $meta;
		}, 10, 1);
	}

	//Yoast SEO changes
	if(defined('SEOPRESS_VERSION')) {

		$seo_plugin = true;

		//replace title
		add_filter('seopress_titles_title', function($title) use ($details) {
			return !empty($details['social_title']) ? apply_filters('novashare_meta_title', $details['social_title']) : $title;
		}, 10, 1);

		//replace description
		add_filter('seopress_titles_desc', function($description) use ($details) {
			return !empty($details['social_description']) ? $details['social_description'] : $description;
		}, 10, 1);

		//remove original image tags
		if($novashare_image && is_array($novashare_image)) {
			add_filter('seopress_social_og_thumb', '__return_false');
			add_filter('seopress_social_twitter_card_thumb', '__return_false');
		}
	}

	//print basic tags if we need to
	if(!$seo_plugin) {

		$output.= '<meta property="og:locale" content="' . esc_attr(get_locale()) . '" />' . PHP_EOL;
		$output.= '<meta property="og:type" content="article" />' .PHP_EOL;
		$output.= '<meta property="og:title" content="' . esc_attr(sanitize_text_field($novashare_title)) . '" />' . PHP_EOL;
		$output.= '<meta property="og:description" content="' . esc_attr(sanitize_text_field($novashare_description)) . '" />' . PHP_EOL;
		$output.= '<meta property="og:url"	content="' . apply_filters('novashare_post_permalink', get_the_permalink($post)) . '" />' . PHP_EOL;
		$output.= '<meta property="og:site_name" content="' . get_bloginfo('name') . '" />' . PHP_EOL;
		$output.= '<meta property="og:updated_time" content="' . date('c', strtotime($post->post_modified)) . '" />' . PHP_EOL;
		$output.= '<meta property="article:published_time" content="' . date('c', strtotime($post->post_date)) . '" />' . PHP_EOL;
		$output.= '<meta property="article:modified_time" content="' . date('c', strtotime($post->post_modified)) . '" />' . PHP_EOL;

		//facebook
		if($novashare['facebook_app_id']) {
			$output.= '<meta property="fb:app_id" content="' . esc_attr($novashare['facebook_app_id']) . '" />' . PHP_EOL;
		}

		//twitter
		$output.= '<meta name="twitter:card" content="summary_large_image" />' . PHP_EOL;
		$output.= '<meta name="twitter:title" content="' . esc_attr(sanitize_text_field($novashare_title)) . '" />' . PHP_EOL;
		$output.= '<meta name="twitter:description" content="' . esc_attr(sanitize_text_field($novashare_description)) . '" />' . PHP_EOL;
		if($novashare['twitter_username']) {
			$output.= '<meta name="twitter:creator" content="' . '@' . esc_attr($novashare['twitter_username']) . '" />' . PHP_EOL;
		}

		//grab featured image
		if(empty($novashare_image)) {
			$thumbnail_id = get_post_thumbnail_id();
			if(!empty($thumbnail_id)) {
				$novashare_image = wp_get_attachment_image_src($thumbnail_id, 'full');
			}
		}
	}

	//image tags
	if($novashare_image && is_array($novashare_image)) {
		$output.= '<meta property="og:image" content="' . esc_attr($novashare_image[0]) . '" />' . PHP_EOL;
		$parsed_image = parse_url($novashare_image[0]);
		if($parsed_image['scheme'] == 'https') {
			$output.= '<meta property="og:image:secure_url" content="' . esc_attr($novashare_image[0]) . '" />' . PHP_EOL;
		}
		if($novashare_image[1]) {
			$output.= '<meta property="og:image:width" content="' . esc_attr($novashare_image[1]) . '" />' . PHP_EOL;
		}
		if($novashare_image[2]) {
			$output.= '<meta property="og:image:height" content="' . esc_attr($novashare_image[2]) . '" />' . PHP_EOL;
		}
		$output.= '<meta name="twitter:image" content="' . esc_attr($novashare_image[0]) . '" />' . PHP_EOL;
	}

	//output wrapped tags
	if(!empty($output)) {
		$output = apply_filters('novashare_opengraph_meta_tags', $output);
		add_action('wp_head', function() use($output) {
			echo PHP_EOL . '<!-- Novashare v.' . NOVASHARE_VERSION . ' https://novashare.io/ -->' . PHP_EOL . $output . '<!-- / Novashare -->' . PHP_EOL;
		}, 1);
	}
}
add_action('template_redirect', 'novashare_opengraph_meta_tags', 1);

//modify oembed response data
function novashare_oembed_response_data() {

	if(is_admin()) {
		return;
	}

	//main ombed response
	add_filter('oembed_response_data', function($data, $post, $width, $height) {

		if($post) {

			$meta = novashare_get_post_meta($post);

			if($meta) {

				//title
				$data['title'] = $meta['title'] ? $meta['title'] : $data['title'];

				//thumbnail image
				if(isset($meta['image'])) {
					$data['thumbnail_url'] = $meta['image'][0] ?? $data['thumbnail_url'] ?? '';
					$data['thumbnail_width'] = $meta['image'][1] ?? $data['thumbnail_width'] ?? '';
					$data['thumbnail_height'] = $meta['image'][2] ?? $data['thumbnail_height'] ?? '';
				}
			}
		}

		return $data;

	}, 10, 4);

	//embed image thumbnail id
	add_filter('embed_thumbnail_id', function($thumbnail_id) {

		global $post;

		if($post->ID) {
			$meta = novashare_get_post_meta($post);
			$thumbnail_id = $meta['image_id'] ? $meta['image_id'] : $thumbnail_id;
		}

		return $thumbnail_id;

	}, 10, 1);

	//embed image thumbnail size
	add_filter('embed_thumbnail_image_size', function($image_size, $thumbnail_id) {

		$image_size = "large";

		return $image_size;

	}, 10, 2);

	//embed image thumbnail shape
	add_filter('embed_thumbnail_image_shape', function($shape, $thumbnail_id) {

		$shape = "rectangular";

		return $shape;

	}, 10, 2);

	//embed title
	add_filter('the_title', function($title, $id = null) {

		if(is_embed()) {

			global $post;

			if($post->ID) {
				$meta = novashare_get_post_meta($post);
				$title = $meta['title'] ? $meta['title'] : $title;
			}
	    }

	    return $title;

	}, 10, 2);

	//embed excerpt
	add_filter('the_excerpt_embed', function($output) {

		global $post;

		if($post->ID) {
			$meta = novashare_get_post_meta($post);
			$output = $meta['description'] ? $meta['description'] : $output;
		}

		return $output;

	}, 10, 2);
}
add_action('init', 'novashare_oembed_response_data', 1);

//get correct meta data for post
function novashare_get_post_meta($post) {

	global $wpdb;
	global $novashare_post_meta;

	if($novashare_post_meta && is_array($novashare_post_meta)) {
		return $novashare_post_meta;
	}

	if($post->ID) {

		//get post details row
		$details = novashare_get_post_details($post->ID);

		$novashare_post_meta = array();

		//grab title + description
		$novashare_post_meta['title'] = apply_filters('novashare_meta_title', !empty($details['social_title']) ? $details['social_title'] : $post->post_title);
		$novashare_post_meta['description'] = wp_strip_all_tags(!empty($details['social_description']) ? $details['social_description'] : (strlen(strip_shortcodes($post->post_content)) > 200 ? substr(strip_shortcodes($post->post_content), 0, 197) . "..." : strip_shortcodes($post->post_content)));

		//grab image
		$novashare_image = '';
		if(isset($details['social_image'])) {
			$novashare_post_meta['image'] = wp_get_attachment_image_src($details['social_image'], 'full');
			$novashare_post_meta['image_id'] = $details['social_image'];
		}

		return $novashare_post_meta;
	}

	return;
}

//get novashare meta details for post 
function novashare_get_post_details($post_id) {

	if($post_id) {

		global $novashare_post_details;

		if(isset($novashare_post_details[$post_id]) || (is_array($novashare_post_details) && array_key_exists($post_id, $novashare_post_details))) {
			return $novashare_post_details[$post_id];
		}

		global $wpdb;

		$novashare_post_details[$post_id] = maybe_unserialize($wpdb->get_var($wpdb->prepare("SELECT meta_value FROM {$wpdb->prefix}novashare_meta WHERE post_id = %d AND meta_key = 'details'", $post_id)));

		return $novashare_post_details[$post_id];
	}
}