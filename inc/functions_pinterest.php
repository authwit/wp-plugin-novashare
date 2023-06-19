<?php
//initialize pinterest image pin admin functionality
function novashare_pinterest_image_pins_admin() {

	$novashare = get_option('novashare');
	if(!empty($novashare['pinterest']['image_attributes']) || !empty($novashare['pinterest']['image_pins'])) {

		//admin functions
		add_filter('attachment_fields_to_edit', 'novashare_add_pinterest_attachment_fields', 10, 2);
		add_action('edit_attachment', 'novashare_save_pinterest_attachment_fields');
		add_filter('image_send_to_editor', 'novashare_send_pinterest_attachment_fields', 20, 2);
		add_filter('wp_prepare_attachment_for_js', 'novashare_prepare_pinterest_attachment_fields', 10, 3);
		add_action('print_media_templates', 'novashare_print_media_templates_pinterest');
	}
}
add_action('admin_init', 'novashare_pinterest_image_pins_admin');

//add pinterest image pin filters
function novashare_pinterest_image_pins() {

	if(!is_admin() && is_singular()) {

		$novashare = get_option('novashare');

		if(!empty($novashare['pinterest']['image_pins'])) {

			global $post;
			
			if($post && !empty($novashare['pinterest']['post_types']) && in_array($post->post_type, $novashare['pinterest']['post_types'])) {

				//get post details row
				$details = novashare_get_post_details($post->ID);

				if(empty($details['disable_image_pins'])) {
					add_filter('the_content', 'novashare_add_pinterest_images', 11);
					add_filter('post_thumbnail_html', 'novashare_add_pinterest_images', 1);
					add_action('amp_post_template_css', function() { echo novashare_pinterest_image_css(); });
				}
			}
		}

		//image attributes
		if(!empty($novashare['pinterest']['image_attributes']) || !empty($novashare['pinterest']['image_pins'])) {
			add_filter('wp_get_attachment_image_attributes', 'novashare_add_pinterest_image_attributes', 10, 2);
		}

		//hidden images
		add_filter('the_content', 'novashare_add_hidden_images', 11);
		add_filter('perfmatters_critical_image_exclusions', function($exclusions) {
	   		$exclusions[] = 'ns-pinterest-hidden-image';
		   	return $exclusions;
		});
	}
}
add_action('wp', 'novashare_pinterest_image_pins');

//add pinterest attachment fields to media library images
function novashare_add_pinterest_attachment_fields($form_fields, $post) {

	//make sure its an image attachment
    if(strpos($post->post_mime_type, 'image') === false) {
    	return $form_fields;
    }

    //get existing meta values
    $pin_title = get_post_meta($post->ID, 'novashare_pin_title', true);
    $pin_description = get_post_meta($post->ID, 'novashare_pin_description', true);
    $pin_repin_id = get_post_meta($post->ID, 'novashare_pin_repin_id', true);
    $pin_nopin = get_post_meta($post->ID, 'novashare_pin_nopin', true);

    //depcrecated
    if(apply_filters('novashare_show_deprecated', false)) {
    	$form_fields['novashare_pin_title'] = array(
	    	'input' => 'text',
	    	'value' => (!empty($pin_title) ? esc_attr($pin_title) : ''),
	    	'label' => 'Novashare: ' . __("Pin Title", 'novashare') . ' (' . __('Deprecated', 'novashare') . ')'
	    );

	    $form_fields['novashare_pin_description'] = array(
	    	'input'	=> 'textarea',
	        'value' => (!empty( $pin_description) ? esc_attr($pin_description) : ''),
	        'label' => 'Novashare: ' . __("Pin Description", 'novashare') . ' (' . __('Deprecated', 'novashare') . ')'
	    );
    }

    $form_fields['novashare_pin_repin_id'] = array(
    	'input' => 'text',
    	'value' => (!empty($pin_repin_id) ? esc_attr($pin_repin_id) : ''),
    	'label' => 'Novashare: ' . __("Pin Repin ID", 'novashare')
    );

    $form_fields['novashare_pin_nopin'] = array(
    	'input'	=> 'html',
    	'label' => 'Novashare: ' . __("Disable Pinning", 'novashare'),
    	'html'  => '<input type="hidden" name="attachments[' . $post->ID . '][novashare_pin_nopin]" value="0" /><input type="checkbox" id="attachments-' . $post->ID . '-pin_nopin" name="attachments[' . $post->ID . '][novashare_pin_nopin]" value="1"' . (!empty($pin_nopin) ? ' checked="checked"' : '') . ' />',
    );

    return $form_fields;
}

//save pinterest attachment fields from media library image
function novashare_save_pinterest_attachment_fields($attachment_id) {

	if(isset($_REQUEST['attachments'][$attachment_id]['novashare_pin_title'])) {
        update_post_meta($attachment_id, 'novashare_pin_title', wp_kses_post($_REQUEST['attachments'][$attachment_id]['novashare_pin_title']));
    }

    if(isset($_REQUEST['attachments'][$attachment_id]['novashare_pin_description'])) {
        update_post_meta($attachment_id, 'novashare_pin_description', wp_kses_post($_REQUEST['attachments'][$attachment_id]['novashare_pin_description']));
    }

    if(isset($_REQUEST['attachments'][$attachment_id]['novashare_pin_repin_id'])) {
        update_post_meta($attachment_id, 'novashare_pin_repin_id', sanitize_text_field($_REQUEST['attachments'][$attachment_id]['novashare_pin_repin_id']));
    }

    if(isset( $_REQUEST['attachments'][$attachment_id]['novashare_pin_nopin'])) {
        update_post_meta( $attachment_id, 'novashare_pin_nopin', $_REQUEST['attachments'][$attachment_id]['novashare_pin_nopin']);
    }
}

//add pinterest attachment fields to image html in editor
function novashare_send_pinterest_attachment_fields($html, $attachment_id) {

	//get attachment meta details
	$pin_title = get_post_meta($attachment_id, 'novashare_pin_title', true);
	$pin_description = get_post_meta($attachment_id, 'novashare_pin_description', true);
	$pin_repin_id = get_post_meta($attachment_id, 'novashare_pin_repin_id', true);
	$pin_nopin = get_post_meta($attachment_id, 'novashare_pin_nopin', true);

	if(!empty($pin_title)) {
		$html = str_replace('<img ', '<img data-pin-title="' . esc_attr(wp_unslash($pin_title)) . '" ', $html);
	}

	if(!empty($pin_description)) {
		$html = str_replace('<img ', '<img data-pin-description="' . esc_attr(wp_unslash($pin_description)) . '" ', $html);
	}

	if(!empty($pin_repin_id)) {
		$html = str_replace('<img ', '<img data-pin-id="' . esc_attr(wp_unslash($pin_repin_id)) . '" ', $html);
	}

	if(!empty($pin_nopin)) {
		$html = str_replace('<img ', '<img data-pin-nopin="true" ', $html);
	}

	return $html;
}

//adds pinterest attachment fields to JSON encoded version of image
function novashare_prepare_pinterest_attachment_fields($response, $attachment, $meta) {

	$response['novashare_pin_title'] = get_post_meta($attachment->ID, 'novashare_pin_title', true);
	$response['novashare_pin_description'] = get_post_meta($attachment->ID, 'novashare_pin_description', true);
	$response['novashare_pin_repin_id'] = get_post_meta($attachment->ID, 'novashare_pin_repin_id', true);
	$response['novashare_pin_nopin'] = get_post_meta($attachment->ID, 'novashare_pin_nopin', true);

	return $response;
}

//add pinterest inputs to image details edit module
function novashare_print_media_templates_pinterest() { 
	$show_deprecated = apply_filters('novashare_show_deprecated', false);
	?>
    <script>
        jQuery(function($) {

        	//get image details container
        	var $imageDetails = $("#tmpl-image-details");

		    if($imageDetails.length > 0 && window.pagenow !== void 0 && window.pagenow != "widgets" && window.pagenow != "customize") {

		    	//get container html
		    	var $imageDetailsHTML = $imageDetails.html();

		    	//setup pinterest inputs
		        var novashare_pin_title = "<div<?php echo !$show_deprecated ? " style='display: none;'" : ''; ?>><label class='setting novashare-pin-title'><span>Novashare: <?php _e("Pin Title", 'novashare'); ?> (<?php _e('Deprecated', 'novashare'); ?>)</span><input type='text' data-setting='novashare_pin_title' value='{{data.model.novashare_pin_title}}' /></label></div>";

		        var novashare_pin_description = "<div<?php echo !$show_deprecated ? " style='display: none;'" : ''; ?>><label class='setting novashare-pin-description'><span>Novashare: <?php _e("Pin Description", 'novashare'); ?> (<?php _e('Deprecated', 'novashare'); ?>)</span><textarea data-setting='novashare_pin_description'>{{data.model.novashare_pin_description}}</textarea></label></div>";

		        var novashare_pin_repin_id = "<label class='setting novashare-pin-repin-id'><span>Novashare: <?php _e("Pin Repin ID", 'novashare'); ?></span><input type='text' data-setting='novashare_pin_repin_id' value='{{data.model.novashare_pin_repin_id}}'' /></label>";

		        var novashare_pin_nopin = "<label class='setting novashare-pin-nopin'><span>Novashare: <?php _e("Disable Pinning", 'novashare'); ?></span><input type='checkbox' data-setting='novashare_pin_nopin' value='1' <# if ( data.model.novashare_pin_nopin ) { #> checked='checked' <# } #> style='margin-top: 8px;' /></label>";

		        var novashare_pinterest_fields = novashare_pin_title + novashare_pin_description + novashare_pin_repin_id + novashare_pin_nopin;

		        //search for alt text field first and prepend inputs
				if($imageDetails.text().indexOf('<span class="setting alt-text') != -1) {
					$imageDetails.text($imageDetailsHTML.replace(/(<span class="setting alt-text)/, novashare_pinterest_fields + "$1"))
				}
				//search for caption field second and prepend inputs
				else if($imageDetails.text().indexOf('<span class="setting caption') != -1) {
					$imageDetails.text($imageDetailsHTML.replace(/(<span class="setting caption)/, novashare_pinterest_fields + "$1"));
				}
		    }
		    //populate inputs with existing image attributes
	    	typeof wp != "undefined" && wp.media !== void 0 && wp.media.events !== void 0 && (wp.media.events.on("editor:image-edit", function(e) {
	            (e.metadata.novashare_pin_title = e.editor.$(e.image).attr("data-pin-title")),
                (e.metadata.novashare_pin_description = e.editor.$(e.image).attr("data-pin-description")),
                (e.metadata.novashare_pin_repin_id = e.editor.$(e.image).attr("data-pin-id")),
                (e.metadata.novashare_pin_nopin = e.editor.$(e.image).attr("data-pin-nopin") ? "1" : "");
	        }),
	        //populate image attributes with updated input values
	        wp.media.events.on("editor:image-update", function(e) {
	            e.editor.$(e.image).attr("data-pin-title", e.metadata.novashare_pin_title),
                e.editor.$(e.image).attr("data-pin-description", e.metadata.novashare_pin_description),
                e.editor.$(e.image).attr("data-pin-id", e.metadata.novashare_pin_repin_id),
                e.metadata.novashare_pin_nopin ? e.editor.$(e.image).attr("data-pin-nopin", "true") : e.editor.$(e.image).removeAttr("data-pin-nopin");
	        }));
		});
    </script>
    <?php
}

//add pinterest hover buttons to images
function novashare_add_pinterest_images($content) {

	global $wp_current_filter;

	//bail if the_content is being requested by something else
    if(!empty($wp_current_filter) && is_array($wp_current_filter)) {
    	if(count(array_intersect($wp_current_filter, apply_filters('novashare_pinterest_image_excluded_filters', array('wp_head', 'widget_text_content', 'get_the_excerpt')))) > 0) {
	     	return $content;
		}

		//nested the_content hook
		$filter_counts = array_count_values($wp_current_filter);
		if(!empty($filter_counts['the_content']) && $filter_counts['the_content'] > 1) {
			return $content;
		}
    }

	global $post;

	//setup image regex pattern
	$imgPattern  = '/<img([^>]*)>/i';
	$attrPattern = '/ ([-\w]+)[ ]*=[ ]*([\"\']?)(.*?)\2/i';

	//match images in content
	preg_match_all($imgPattern, $content, $images, PREG_SET_ORDER);

	if(!empty($images)) {

		$novashare = get_option('novashare');

		//post permalink
		$permalink = apply_filters('novashare_post_permalink', get_the_permalink($post->ID));

		//add Google UTM tracking
		if(!empty($novashare['google_utm']) && !empty($novashare['google_utm_source']) && !empty($novashare['google_utm_medium']) && !empty($novashare['google_utm_name'])) {
			$google_utm = array(
				'utm_source' => (trim($novashare['google_utm_source']) === '{{network}}' ? 'pinterest' : trim($novashare['google_utm_source'])),
				'utm_medium' => trim($novashare['google_utm_medium']),
				'utm_campaign' => trim($novashare['google_utm_name'])
			);
			$permalink = add_query_arg($google_utm, $permalink);
		}

		//encode final permalink
		$permalink = rawurlencode($permalink);

		//get post details row
		$details = novashare_get_post_details($post->ID);

		//track how many images are modified
		$replaced_image_count = 0;

		$pin_button_class = '';
		if(!empty($novashare['pinterest']['pin_button_shape'])) {
		 	$pin_button_class.= ' ns-' . $novashare['pinterest']['pin_button_shape'];
		}

		//loop through image matches
		foreach($images as $img) {

			//skip if exluded attribute was found
			if(apply_filters('novashare_pinterest_image_excluded', novashare_pinterest_image_excluded($img[1], novashare_pinterest_image_excluded_atts()))) {
				continue;
			}

			//get image attributes array
			$atts = novashare_get_atts_array($img[1]);

			if(isset($atts['data-pin-nopin'])) {
				continue;
			}

			foreach(array('width', 'height') as $dimension) {
				if(!empty($atts[$dimension]) && $atts[$dimension] < apply_filters('novashare_pinterest_image_minimum_dimension', 150)) {
					continue 2;
				}
			}

			//set src
			if(!empty($atts['data-pin-media'])) {
				$src = $atts['data-pin-media'];
			}
			elseif(!empty($atts['src']) && !empty(pathinfo(parse_url($atts['src'], PHP_URL_PATH), PATHINFO_EXTENSION))) {
				$src = $atts['src'];
			}
			elseif(!empty($atts['data-src']) && !empty(pathinfo(parse_url($atts['data-src'], PHP_URL_PATH), PATHINFO_EXTENSION))) {
				$src = $atts['data-src'];
			}

			//skip image if no src was found
			if(empty($src)) {
				continue;
			}

			//set title
			$title = !empty($atts['data-pin-title']) ? $atts['data-pin-title'] : (!empty($atts['data-pin-description']) ? $atts['data-pin-description'] : (!empty($details['pinterest_title']) ? $details['pinterest_title'] : (!empty($details['social_title']) ? $details['social_title'] : $post->post_title)));

			//build share link
			if(empty($atts['data-pin-id'])) {
				$link = "https://pinterest.com/pin/create/button/?url=" . $permalink . "&media=" . $src . "&description=" . rawurlencode(apply_filters('novashare_meta_title', $title));
			}
			else {
				$link = "https://www.pinterest.com/pin/" . $atts['data-pin-id'] . "/repin/x/";
			}

			//image wrapper
			$new_image = "<span class='ns-pinterest-image'>";

				//original image
				$new_image.= $img[0];

				//pin button
				$new_image.= "<span class='ns-pinterest-image-button" . $pin_button_class . "' data-novashare-href='" . $link . "' rel='nofollow noopener noreferrer'" . (novashare_is_amp() ? " on='tap:AMP.navigateTo(url=\"" . $link . "\",target=\"_blank\")' role='button' tabindex='0'" : "") . ">";
					$networks = novashare_networks();
					$new_image.= $networks['pinterest']['icon'];
					if(empty($novashare['pinterest']['hide_pin_button_labels'])) {
						$new_image.= "<span style='margin-left: 4px;'>" . apply_filters('novashare_pin_button_text', __("Pin", 'novashare')) . "</span>";
					}
				$new_image.= "</span>";
			$new_image.= "</span>";

			//replace image with wrapped image in content
			$content = str_replace($img[0], $new_image, $content);

			global $novashare_pinterest_css;

			//don't print more than once
			if(!$novashare_pinterest_css) {

				$novashare_pinterest_css = true;

				//add inline styles for buttons
				$content = "<style>" . novashare_pinterest_image_css() . "</style>" . $content;
			}

			$replaced_image_count++;
		}

		//load plugin js if needed
		if($replaced_image_count > 0 && !wp_script_is('novashare-js') && !novashare_is_amp()) {
			wp_enqueue_script('novashare-js');
		}
	}

	return $content;
}

//add pinterest attributes to image
function novashare_add_pinterest_image_attributes($attr, $attachment = null) {

	if(!isset($attr['data-pin-title'])) {
		$title = get_post_meta($attachment->ID, 'novashare_pin_title', true);
		if(!empty($title)) {
			$attr['data-pin-title'] = $title;
		}
	}
	if(!isset($attr['data-pin-description'])) {
		$description = get_post_meta($attachment->ID, 'novashare_pin_description', true);
		if(!empty($description)) {
			$attr['data-pin-description'] = $description;
		}
	}
	if(!isset($attr['data-pin-id'])) {
		$repin_id = get_post_meta($attachment->ID, 'novashare_pin_repin_id', true);
		if(!empty($repin_id)) {
			$attr['data-pin-id'] = $repin_id;
		}
	}
	if(!isset($attr['data-pin-nopin'])) {
		$nopin = get_post_meta($attachment->ID, 'novashare_pin_nopin', true);
		if(!empty($nopin)) {
			$attr['data-pin-nopin'] = true;
		}
	}
	return $attr;
}

//return inline pinterest image styles
function novashare_pinterest_image_css() {
	$novashare = get_option('novashare');
	$css = "body .ns-pinterest-image{display:block;position:relative;margin:0;padding:0;line-height:0}figure>.ns-pinterest-image{height:100%;width:100%}body .wp-block-image .ns-pinterest-image+figcaption{display:block}body .ns-pinterest-image-button{opacity:0;transition:.3s;position:absolute;height:18px;max-height:18px;width:auto!important;padding:10px;cursor:pointer;background:" . ($novashare['pinterest']['pin_button_color'] ?? '#c92228') . ";color:" . ($novashare['pinterest']['pin_icon_color'] ?? '#fff') . ";font-size:16px;line-height:18px;z-index:1;text-decoration:none;box-sizing:content-box;";
	if(!empty($novashare['pinterest']['pin_button_position'])) {
		if($novashare['pinterest']['pin_button_position'] == 'topright') {
			$css.= "top:10px;right:10px";
		}
		elseif($novashare['pinterest']['pin_button_position'] == 'bottomright') {
			$css.= "bottom:10px;right:10px";
		}
		elseif($novashare['pinterest']['pin_button_position'] == 'bottomleft') {
			$css.= "bottom:10px;left:10px";
		}
		elseif($novashare['pinterest']['pin_button_position'] == 'center') {
			$css.= "top:50%;left:50%;transform:translate(-50%,-50%)";
		}
	}
	else {
		$css.= "top:10px;left:10px";
	}	
	$css.= "}body .ns-pinterest-image-button:hover{box-shadow:inset 0 0 0 50px rgba(0,0,0,0.1);}body .ns-pinterest-image-button:visited{color:#fff}body .ns-pinterest-image:hover .ns-pinterest-image-button{opacity:1}body .ns-pinterest-image-button svg{width:18px;height:18px;vertical-align:middle;pointer-events:none}";
	if(!wp_style_is('novashare-css')) {
		$css.= ".ns-rounded{border-radius:5px;-webkit-border-radius:5px;-moz-border-radius:5px}.ns-circular{border-radius:30px;-webkit-border-radius:30px;-moz-border-radius:30px}";
	}
	return $css;
}

//get excluded attributes
function novashare_pinterest_image_excluded_atts() {

	//base exclusions
	$attributes = array(); 

	//get exclusions added from settings
	$novashare = get_option('novashare');
	if(!empty($novashare['pinterest']['excluded_images']) && is_array($novashare['pinterest']['excluded_images'])) {
		$attributes = array_unique(array_merge($attributes, $novashare['pinterest']['excluded_images']));
	}

    return apply_filters('novashare_pinterest_image_excluded_attributes', $attributes);
}

//check for excluded attributes in attributes string
function novashare_pinterest_image_excluded($string, $excluded) {
    if(!is_array($excluded)) {
        (array) $excluded;
    }

    if(empty($excluded)) {
        return false;
    }

    foreach($excluded as $exclude) {
        if(strpos($string, $exclude) !== false) {
            return true;
        }
    }

    return false;
}

//add hidden images to the content
function novashare_add_hidden_images($content) {

	global $wp_current_filter;

	//bail if the_content is being requested by something else
    if(!empty($wp_current_filter) && is_array($wp_current_filter)) {
    	if(count(array_intersect($wp_current_filter, apply_filters('novashare_pinterest_image_excluded_filters', array('wp_head', 'get_the_excerpt', 'widget_text_content', 'p3_content_end')))) > 0) {
	     	return $content;
		}

		//nested the_content hook
		$filter_counts = array_count_values($wp_current_filter);
		if(!empty($filter_counts['the_content']) && $filter_counts['the_content'] > 1) {
			return $content;
		}
    }

    global $novashare_hidden_image_print;

	if($novashare_hidden_image_print) {
		return $content;
	}

	global $post;

	//get post details row
	$details = novashare_get_post_details($post->ID);

	$output = '';
	$hidden_images = array();

	//add single pinterest image
	if(!empty($details['pinterest_image'])) {
		$hidden_images[] = $details['pinterest_image'];
	}

	//add pinterest hidden images
	if(!empty($details['pinterest_hidden_images'])) {
		$hidden_images = array_merge($hidden_images, $details['pinterest_hidden_images']);
	}

	//filter
	$hidden_images = apply_filters('novashare_pinterest_hidden_images', array_filter($hidden_images));

	//add to output
	if(!empty($hidden_images)) {
		foreach($hidden_images as $attachment_id) {
			$output.= novashare_get_hidden_image($attachment_id);
		}
		$novashare_hidden_image_print = true;
	}

	//add to content
	if(!empty($output)) {
		$output = '<div style="display: none;">' . $output . '</div>';
		$content = $output . $content;

		$novashare = get_option('novashare');

		//load hidden images delayed for gallery
		if(!empty($novashare['pinterest']['share_button_behavior'])) {
			add_action('wp_footer', 'novashare_print_hidden_image_delay_js');
		}
	}

	return $content;
}

function novashare_get_hidden_image($attachment_id) {
	$output = '<img class="ns-pinterest-hidden-image no-lazy" src="' . wp_get_attachment_image_url($attachment_id, 'thumbnail') . '" data-pin-media="' . wp_get_attachment_image_url($attachment_id, 'full') . '" alt="Pinterest ' . __('Hidden Image', 'novashare') . '" loading="lazy">';
	return $output;
}

//inline js to load hidden images on interaction
function novashare_print_hidden_image_delay_js() {
	echo '<script id="ns-hidden-images">const novashareInteractions=["keydown","mousemove","wheel","touchmove","touchstart","touchend"];function novashareTriggerHiddenImages(){document.querySelectorAll(".ns-pinterest-hidden-image").forEach((function(e){e.removeAttribute("loading"),e.parentNode.style.cssText="position:absolute;height:0;width:0;"})),novashareInteractions.forEach((function(e){window.removeEventListener(e,novashareTriggerHiddenImages,{passive:!0})}))}novashareInteractions.forEach((function(e){window.addEventListener(e,novashareTriggerHiddenImages,{passive:!0})}));</script>';
}

//convert atts string to array of names => values
function novashare_get_atts_array($atts_string) {
	if(!empty($atts_string)) {
		$atts_array = array_map(
			function(array $attribute) {
				return $attribute['value'];
			},
			wp_kses_hair($atts_string, wp_allowed_protocols())
		);

		return $atts_array;
	}
	return false;
}