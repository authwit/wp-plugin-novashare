<?php
//render click to tweet block
function novashare_click_to_tweet_block($attributes) {
	return novashare_output_click_to_tweet($attributes);
}

//click to tweet shortcode
function novashare_click_to_tweet($params = array()) {

	//don't run if we aren't in the content
	if(current_action() != 'the_content') {
		return;
	}

	//get parameters
	$args = shortcode_atts(array(
		'tweet'	          => '',
		'theme'	          => '',
		'cta_text'        => '',
		'cta_position'    => '',
		'remove_url' 	  => false,
		'remove_username' => false,
		'hide_hashtags'   => false,
		'accent_color'    => ''
	), $params);

	//filter boolean values
	$args['remove_url'] = filter_var($args['remove_url'], FILTER_VALIDATE_BOOLEAN);
	$args['remove_username'] = filter_var($args['remove_username'], FILTER_VALIDATE_BOOLEAN);
	$args['hide_hashtags'] = filter_var($args['hide_hashtags'], FILTER_VALIDATE_BOOLEAN);

	return novashare_output_click_to_tweet($args);
}
add_shortcode('novashare_tweet', 'novashare_click_to_tweet');

//output click to tweet box
function novashare_output_click_to_tweet($atts) {

	//don't render if in the admin
	if(is_admin()) {
		return;
	}

	//check for tweet
	if(empty($atts['tweet'])) {
		return;
	}
	
	global $post;

	//get plugin options
	$novashare = get_option('novashare');
	$networks = novashare_networks();

	//set our tweet content
	$tweet = $atts['tweet'];
	$display_tweet = $tweet;

	//remove trailing hashtags and store in array
	$hashtags = array();
	$tweet = preg_replace_callback(
    	'/#([\w-]+(?=(?:\s+#[\w-]+)*\s*$))/iu',
        function ($match) use (&$hashtags) {
        	$hashtags[] = $match[1];
        },
        $tweet
    );

	//clean up any trailing whitespace
    $tweet = trim($tweet);

    //update display tweet to match if needed
    if(!empty($atts['hide_hashtags'])) {
    	$display_tweet = $tweet;
	}

	//remove any html tags from link tweet
    $tweet = strip_tags($tweet);

	//maximum tweet length
	$tweet_max = 280;

	//get twitter share link
	$link = novashare_network_link('twitter');

	//remove existing text query parameter
	$link = remove_query_arg('text', $link);

	//remove url from link
	if(!empty($atts['remove_url'])) {
		$link = remove_query_arg('url', $link);
	}
	else {
		//subtract permalink length from max tweet length
		$tweet_max = $tweet_max - 24;
	}

	//remove username from link
	if(!empty($atts['remove_username'])) {
		$link = remove_query_arg('via', $link);
	}
	elseif(!empty($novashare['twitter_username'])) {
		//subtract username from max tweet length
		$tweet_max = $tweet_max - (strlen($novashare['twitter_username']) + 6);
	}

	//apply filter before generating link
	$tweet = apply_filters('novashare_ctt_tweet', $tweet);

	//shorten tweet if it's too long
	if(strlen($atts['tweet']) > $tweet_max) {
		$tweet = substr($tweet, 0, ($tweet_max - strlen($atts['tweet']) - 3)) . '...';
	}

	//add final tweet text to link
	$link = add_query_arg(array('text' => urlencode($tweet)), $link);

	//add trailing hashtags back to link
	if(!empty($hashtags)) {
    	$hashtags = implode(',', $hashtags); 
    	$link = add_query_arg(array('hashtags' => urlencode($hashtags)), $link);
    }

	//grab click to tweet theme
	$theme_prefix = ' ns-ctt-';
	$theme = !empty($atts['theme']) ? $theme_prefix . $atts['theme'] : (!empty($novashare['click_to_tweet']['theme']) ? $theme_prefix . $novashare['click_to_tweet']['theme'] : '');

	//grab click to tweet cta position
	$cta_prefix = ' ns-ctt-cta-';
	$cta_position = !empty($atts['cta_position']) ? $cta_prefix . $atts['cta_position'] : (!empty($novashare['click_to_tweet']['cta_position']) ? $cta_prefix . $novashare['click_to_tweet']['cta_position'] : '');

	//grab call to action text
	$cta_text = !empty($atts['cta_text']) ? $atts['cta_text'] : (!empty($novashare['click_to_tweet']['cta_text']) ? $novashare['click_to_tweet']['cta_text'] : __('Click to Tweet', 'novashare'));

	//load plugin js if needed
	if(!wp_script_is('novashare-js') && !novashare_is_amp()) {
		wp_enqueue_script('novashare-js');
	}

    //start block output
	$output = '';

	//inline css
	global $novashare_click_to_tweet_css;

	//don't print more than once
	if(!$novashare_click_to_tweet_css) {
		$output.= "<style>" . novashare_click_to_tweet_css() . "</style>";
		$novashare_click_to_tweet_css = true;
	}

	//tweet link
	$output.= '<a href="' . $link . '" class="ns-ctt' . $theme . $cta_position . '" target="_blank" rel="nofollow noopener noreferrer"' . (!empty($atts['accent_color']) ? ' style="' . (empty($theme) ? 'background-color:' . $atts['accent_color'] . ';' : '') . 'border-color:' . $atts['accent_color'] . ';"' : '') . '>';

		//display tweet
		$output.= '<span class="ns-ctt-tweet">';
			$output.= $display_tweet;
		$output.= '</span>';

		//cta container
		$output.= '<span class="ns-ctt-cta-container">';
			$output.= '<span class="ns-ctt-cta"' . (!empty($atts['accent_color']) && !empty($theme) ? ' style="color:' . $atts['accent_color'] . ';"' : '') . '>';
				$output.= '<span class="ns-ctt-cta-text">' . $cta_text . '</span>';
			    $output.= '<span class="ns-ctt-cta-icon">' . $networks['twitter']['icon'] . '</span>';
			$output.= '</span>';
		$output.= '</span>';

	$output.= '</a>';
    
    return $output;
}

//return inline click to tweet styles
function novashare_click_to_tweet_css() {

	$novashare = get_option('novashare');

	$accent_color = !empty($novashare['click_to_tweet']['accent_color']) ? $novashare['click_to_tweet']['accent_color'] : '#00abf0';

	return "body .ns-ctt{display:block;position:relative;background:" . $accent_color . ";margin:30px auto;padding:20px 20px 20px 15px;color:#fff;text-decoration:none!important;box-shadow:none!important;-webkit-box-shadow:none!important;-moz-box-shadow:none!important;border:none;border-left:5px solid " . $accent_color . "}body .ns-ctt:hover{color:#fff}body .ns-ctt:visited{color:#fff}body .ns-ctt *{pointer-events:none}body .ns-ctt .ns-ctt-tweet{display:block;font-size:18px;line-height:27px;margin-bottom:10px}body .ns-ctt .ns-ctt-cta-container{display:block;overflow:hidden}body .ns-ctt .ns-ctt-cta{float:right}body .ns-ctt.ns-ctt-cta-left .ns-ctt-cta{float:left}body .ns-ctt .ns-ctt-cta-text{font-size:16px;line-height:16px;vertical-align:middle}body .ns-ctt .ns-ctt-cta-icon{margin-left:10px;display:inline-block;vertical-align:middle}body .ns-ctt .ns-ctt-cta-icon svg{vertical-align:middle;height:18px}body .ns-ctt.ns-ctt-simple{background:0 0;padding:10px 0 10px 20px;color:inherit}body .ns-ctt.ns-ctt-simple-alt{background:#f9f9f9;padding:20px;color:#404040}body .ns-ctt:hover::before{content:'';position:absolute;top:0px;bottom:0px;left:-5px;width:5px;background:rgba(0,0,0,0.25);}body .ns-ctt.ns-ctt-simple .ns-ctt-cta,body .ns-ctt.ns-ctt-simple-alt .ns-ctt-cta{color:" . $accent_color . "}body .ns-ctt.ns-ctt-simple-alt:hover .ns-ctt-cta,body .ns-ctt.ns-ctt-simple:hover .ns-ctt-cta{filter:brightness(75%)}";
}