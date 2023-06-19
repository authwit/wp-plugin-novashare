<?php
/*
Plugin Name: Novashare
Plugin URI: https://novashare.io/
Description: Novashare is a lightweight and fast social media sharing plugin.
Version: 1.4.3
Author: forgemedia
Author URI: https://forgemedia.io/
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
Text Domain: novashare
Domain Path: /languages

Novashare uses Font Awesome free SVG icons released under the CC by 4.0 license.
Go to https://github.com/FortAwesome/Font-Awesome for full license details.

The Yummly logo is property of Yummly, Inc. https://www.yummly.com/
*/

//EDD download details
define('NOVASHARE_STORE_URL', 'https://novashare.io/');
define('NOVASHARE_ITEM_ID', 60);
define('NOVASHARE_ITEM_NAME', 'novashare');
define('NOVASHARE_VERSION', '1.4.3');

//load translations
function novashare_load_textdomain() {
	load_plugin_textdomain('novashare', false, dirname(plugin_basename( __FILE__)) . '/languages/');
}
add_action('plugins_loaded', 'novashare_load_textdomain');

//EDD updater function
function novashare_edd_plugin_updater() {

	//to support auto-updates, this needs to run during the wp_version_check cron job for privileged users
	$doing_cron = defined('DOING_CRON') && DOING_CRON;
	if(!current_user_can('manage_options') && !$doing_cron) {
		return;
	}

	//retrieve our license key from the DB
	$novashare_license = is_multisite() ? get_site_option('novashare_license') : get_option('novashare_license');

	$license = !empty($novashare_license['key']) ? trim($novashare_license['key']) : null;
	
	//setup the updater
	$edd_updater = new Novashare_Plugin_Updater(NOVASHARE_STORE_URL, __FILE__, array(
			'version' 	=> NOVASHARE_VERSION,
			'license' 	=> $license,
			'item_id'   => NOVASHARE_ITEM_ID,
			'author' 	=> 'forgemedia',
			'beta'      => false
		)
	);
}
add_action('init', 'novashare_edd_plugin_updater', 0);

//admin settings page
function novashare_admin() {
	include plugin_dir_path(__FILE__) . '/inc/admin.php';
}

//add our admin menus
if(is_admin()) {
	add_action('admin_menu', 'novashare_menu', 9);
}

//admin menu
function novashare_menu() {
	if(novashare_network_access()) {
		$novashare_settings_page = add_options_page('novashare', 'Novashare', 'manage_options', 'novashare', 'novashare_admin');
	}
}

//enqueue admin scripts
function novashare_admin_scripts($hook) {

	if(novashare_network_access()) {

		//settings page + post editor
	    if(in_array($hook, array('settings_page_novashare', 'settings_page_novashare-migrator')) || in_array($hook, array('post.php', 'post-new.php'))) {

	    	//jquery ui
	    	wp_enqueue_script('jquery-ui-sortable');

	    	//color picker ui
	    	wp_enqueue_style('wp-color-picker');

	    	//image upload
	    	wp_enqueue_media();

	    	//admin js
	    	wp_register_script('novashare-admin-js', plugins_url('/js/admin.js', __FILE__), array('wp-color-picker', 'wp-i18n'), NOVASHARE_VERSION, true);
	    	wp_enqueue_script('novashare-admin-js');
	    	wp_set_script_translations('novashare-admin-js', 'novashare', plugin_dir_path(__FILE__) . 'languages');

			//admin css
	    	wp_register_style('novashare-admin-css', plugins_url('/css/admin-style.css', __FILE__), array(), NOVASHARE_VERSION);
			wp_enqueue_style('novashare-admin-css');
	    }

	    //settings page only
	    if($hook === 'settings_page_novashare') {
	    	$cm_settings['codeEditor'] = wp_enqueue_code_editor(array('type' => 'text/css'));
			wp_localize_script('jquery', 'cm_settings', $cm_settings);
			wp_enqueue_script('wp-theme-plugin-editor');
			wp_enqueue_style('wp-codemirror');
	    }
	}
}
add_action('admin_enqueue_scripts', 'novashare_admin_scripts');

//add our plugin non-admin scripts
function novashare_scripts() {

	$novashare = get_option('novashare');
	$allowed = novashare_is_post_allowed();

	//front end css
	if($allowed || !empty($novashare['global_styles'])) {
		wp_register_style('novashare-css', plugins_url('/css/style.min.css', __FILE__), array(), NOVASHARE_VERSION);
		wp_enqueue_style('novashare-css');
		if(!empty($novashare['custom_css'])) {
			wp_add_inline_style('novashare-css', $novashare['custom_css']);
		}

		//global image pins styles
		if(!empty($novashare['global_styles']) && !empty($novashare['pinterest']['image_pins'])) {

			global $novashare_pinterest_css;
			
			$novashare_pinterest_css = true;
			wp_add_inline_style('novashare-css', novashare_pinterest_image_css());
		}        
	}

	//register js
	wp_register_script('novashare-js', plugins_url('/js/novashare.min.js', __FILE__), array(), NOVASHARE_VERSION);

	//inverse hover inline js
	if(!empty($novashare['inline']['inverse_hover']) || !empty($novashare['floating']['inverse_hover'])) {
		wp_add_inline_script('novashare-js', 'document.addEventListener("DOMContentLoaded",function(){document.querySelectorAll(".ns-inverse-hover .ns-button").forEach(function(e){e.addEventListener("mouseenter",function(a){if(a.target.classList.contains("ns-button")){a.target.classList.add("ns-hover-swap");var n=e.querySelectorAll(".ns-button-icon.ns-border");(n.length?n:e.querySelectorAll(".ns-button-block")).forEach(function(e){e.classList.contains("ns-inverse")?e.classList.remove("ns-inverse"):e.classList.add("ns-inverse")})}},!0),e.addEventListener("mouseout",function(a){if(a.target.classList.contains("ns-button")){a.target.classList.remove("ns-hover-swap");var n=e.querySelectorAll(".ns-button-icon.ns-border");(n.length?n:e.querySelectorAll(".ns-button-block")).forEach(function(e){e.classList.contains("ns-inverse")?e.classList.remove("ns-inverse"):e.classList.add("ns-inverse")})}},!0)})});');
	}

	//show pinterest images inline js
	if(!empty($novashare['pinterest']['share_button_behavior']) && $novashare['pinterest']['share_button_behavior'] == 'pinterest') {
		wp_add_inline_script('novashare-js', 'document.addEventListener("DOMContentLoaded",function(){document.querySelectorAll("img:not(.ns-pinterest-hidden-image)").forEach(function(e){e.setAttribute("data-pin-nopin","1")})});');
	}

	//enqueue js
	if($allowed && !novashare_is_amp()) {
		wp_enqueue_script('novashare-js');
	}

}
add_action('wp_enqueue_scripts', 'novashare_scripts');

//add inline stylesheet for amp reader mode
function novashare_add_amp_css() {

	//make sure shares are enabled for the post
	if(novashare_is_post_allowed()) {
		include 'css/style.min.css';
		$novashare = get_option('novashare');
		if(!empty($novashare['custom_css'])) {
			echo $novashare['custom_css'];
		}       
	}

	//print click to tweet css
	echo novashare_click_to_tweet_css();
}
add_action('amp_post_template_css', 'novashare_add_amp_css');

//add assets to block editor
function novashare_block_editor_assets() {

	$novashare = get_option('novashare');

	wp_register_style('novashare-css', plugins_url('/css/style.min.css', __FILE__), array(), NOVASHARE_VERSION);
	wp_enqueue_style('novashare-css');

	//enqueue our block editor scripts + styles
	wp_enqueue_script('novashare-blocks-js', plugins_url('/js/blocks.js', __FILE__), array('wp-blocks', 'wp-element', 'wp-editor', 'wp-i18n'), NOVASHARE_VERSION);
	wp_set_script_translations('novashare-blocks-js', 'novashare', plugin_dir_path(__FILE__) . 'languages');
	wp_enqueue_style('novashare-blocks-css', plugins_url('/css/blocks.css', __FILE__), array( 'wp-edit-blocks' ), NOVASHARE_VERSION);
	wp_add_inline_style('novashare-blocks-css', novashare_click_to_tweet_css());

	//follow block
	wp_enqueue_script('novashare-blocks-follow-js', plugins_url('/js/blocks-follow.js', __FILE__), array('wp-blocks', 'wp-element', 'wp-editor', 'wp-i18n'), NOVASHARE_VERSION);
	wp_localize_script('novashare-blocks-follow-js', 'novashare', novashare_tinymce_localized_settings());

	if(!empty($novashare['pinterest']['image_attributes']) || !empty($novashare['pinterest']['image_pins'])) {
		wp_enqueue_script('novashare-blocks-pinterest-js', plugins_url('/js/blocks-pinterest.js', __FILE__), array('wp-blocks', 'wp-element', 'wp-editor', 'wp-i18n'), NOVASHARE_VERSION);
		wp_set_script_translations('novashare-blocks-pinterest-js', 'novashare', plugin_dir_path(__FILE__) . 'languages');
		wp_localize_script('novashare-blocks-pinterest-js', 'novashare', novashare_tinymce_localized_settings());
	}

	//pass our plugin options to our block editor script
	wp_localize_script('novashare-blocks-js', 'novashare', novashare_tinymce_localized_settings());
}

//hook assets to editor
add_action('enqueue_block_editor_assets', 'novashare_block_editor_assets');

//register and render gutenberg blocks
function novashare_register_blocks() {

	$novashare = get_option('novashare');

    register_block_type(
        'novashare/click-to-tweet',
        array(
            'attributes' => array(
                'tweet' => array(
                    'type' => 'string'
                ),
                'cta_text' => array(
                	'type' => 'string'
                ),
                'cta_position' => array(
                	'type' => 'string'
                ),
                'remove_url' => array(
                	'type' => 'boolean',
                	'default' => !empty($novashare['click_to_tweet']['remove_url'])
                ),
                'remove_username' => array(
                	'type' => 'boolean',
                	'default' => !empty($novashare['click_to_tweet']['remove_username'])
                ),
                'hide_hashtags' => array(
                	'type' => 'boolean',
                	'default' => !empty($novashare['click_to_tweet']['hide_hashtags'])
                ),
                'accent_color' => array(
                	'type' => 'string',
                	'default' => !empty($novashare['click_to_tweet']['accent_color']) ? $novashare['click_to_tweet']['accent_color'] : '#00abf0'
                )
            ),
            'render_callback' => 'novashare_click_to_tweet_block',
        )
    );

    register_block_type(
        'novashare/follow',
        array(
            'attributes' => array(
            ),
            'render_callback' => 'novashare_follow_block',
        )
    );

    register_block_type(
        'novashare/follow-network',
        array(
            'attributes' => array(
            ),
            'render_callback' => 'novashare_follow_network_block',
        )
    );
}
add_action('init', 'novashare_register_blocks');

//add buttons to TinyMCE editor
function novashare_tinymce_button() {
	if(current_user_can('edit_posts') && current_user_can('edit_pages')) {
        add_filter('mce_buttons', 'novashare_register_tinymce_button');
        add_filter('mce_external_plugins', 'novashare_add_tinymce_button');
        add_action('before_wp_tiny_mce', 'novashare_before_wp_tiny_mce');
    }
}
add_action('admin_init', 'novashare_tinymce_button');

//register click to tweet button
function novashare_register_tinymce_button($buttons) {
	$buttons[] = 'novashare_click_to_tweet';
	return $buttons;
}

//add click to tweet tinymce js
function novashare_add_tinymce_button($plugin_array) {
	$plugin_array['novashare_click_to_tweet'] = plugins_url('/js/tinymce.js', __FILE__);
	return $plugin_array;
}

//print out inline localized settings for tiny mce (classic editor)
function novashare_before_wp_tiny_mce($mce_settings) {
	$novashare = novashare_tinymce_localized_settings();
	echo "<script>var novashare = " . json_encode($novashare) . ";</script>";
}

//return array of tinymce localized settings + translations
function novashare_tinymce_localized_settings() {

	$novashare = get_option('novashare');

	//translations
	$novashare['translations'] = array(
	    'ctt' => array(
	    	'tooltip' => __('Click to Tweet', 'novashare'),
	    	'title' => __('Click to Tweet Shortcode', 'novashare'),
	    	'submit' => __('Insert Shortcode', 'novashare'),
	    	'body' => array(
	    		'tweet' => __('Tweet', 'novashare'),
	    		'theme' => array(
	    			'title' => __('Theme', 'novashare'),
	    			'values' => array(
	    				'default' => __('Default (Blue Background)', 'novashare'),
	    				'simple' => __('Simple (Transparent Background)', 'novashare'),
	    				'simplealt' => __('Simple Alternate (Gray Background)', 'novashare')
	    			)
	    		),
	    		'ctatext' => __('Call to Action Text', 'novashare'),
	    		'ctaposition' => array(
	    			'title' => __('Call to Action Position', 'novashare'),
	    			'values' => array(
	    				'default' => __('Right (Default)', 'novashare'),
	    				'left' => __('Left', 'novashare')
	    			)
	    		),
	    		'removeurl' => array(
	    			'title' => __('Remove Post URL', 'novashare'),
	    			'text' => __('The URL of the current post will not be added to the tweet.', 'novashare')
	    		),
	    		'removeuser' => array(
	    			'title' => __('Remove Username', 'novashare'),
	    			'text' => __('The Twitter username saved in Novashare will not be added to the tweet.', 'novashare')
	    		),
	    		'hidehash' => array(
	    			'title' => __('Hide Hashtags', 'novashare'),
	    			'text' => __('Trailing hashtags will be hidden from the display box.', 'novashare')
	    		),
	    		'accentcolortext' => __('Accent Color', 'novashare'),
	    		'charcount' => __('Characters Remaining', 'novashare')
	    	)
	    )
	);

	//follow networks
	$novashare['networks']['follow'] = novashare_networks('follow');

	//show deprecated
	$novashare['show_deprecated'] = apply_filters('novashare_show_deprecated', false);

	return $novashare;
}

//check multisite and verify access
function novashare_network_access() {
	if(is_multisite()) {
		$novashare_network = get_site_option('novashare_network');
		if((!empty($novashare_network['access']) && $novashare_network['access'] == 'super') && !is_super_admin()) {
			return false;
		}
	}
	return true;
}

//plugins table action links
function novashare_action_links($actions, $plugin_file) {
	if(plugin_basename(__FILE__) == $plugin_file) {

		if(is_network_admin()) {
			$settings_url = network_admin_url('settings.php?page=novashare');
		}
		else {
			$settings_url = admin_url('options-general.php?page=novashare');
		}

		$settings_link = array('settings' => '<a href="' . $settings_url . '">' . __('Settings', 'novashare') . '</a>');
		$actions = array_merge($settings_link, $actions);
	}
	return $actions;
}
add_filter('plugin_action_links', 'novashare_action_links', 10, 5);

//plugins table meta links
function novashare_meta_links($links, $file) {
	if(strpos($file, 'novashare.php' ) !== false) {

		//support link
		$novashare_links = array('<a href="https://novashare.io/docs/" target="_blank">' . __('Support', 'novashare') . '</a>');

		$links = array_merge($links, $novashare_links);
	}
	return $links;
}
add_filter('plugin_row_meta', 'novashare_meta_links', 10, 2);

//display message with plugin update if theres no valid license
function novashare_plugin_update_message() {

	$license = is_network_admin() ? get_site_option('novashare_license') : get_option('novashare_license');

	if(empty($license['status']) || $license['status'] !== 'valid') {
		echo ' <strong><a href="' . esc_url(admin_url('options-general.php?page=novashare&tab=license')) . '">' . __('Enter valid license key for automatic updates.', 'novashare') . '</a></strong>';
	}
}
add_action('in_plugin_update_message-novashare/novashare.php', 'novashare_plugin_update_message', 10, 2);

//install table structure
function novashare_install() {

	global $wpdb;

	$novashare_version = get_option('novashare_version');

	//insert/update custom table
	novashare_install_custom_table($wpdb->prefix);

	//hide hashtags migration
	if($novashare_version < '1.2.3') {
		$novashare = get_option('novashare');
		if(is_array($novashare)) {
			$novashare['click_to_tweet']['hide_hashtags'] = 1;
			update_option('novashare', $novashare);
		}
	}

	//update version
	if($novashare_version != NOVASHARE_VERSION) {
		update_option('novashare_version', NOVASHARE_VERSION, false);
	}

	//update network version if needed
	if(is_multisite()) {
		if(get_site_option('novashare_version') != NOVASHARE_VERSION) {
			update_site_option('novashare_version', NOVASHARE_VERSION, false);
		}
	}
}

//query to insert/update custom table
function novashare_install_custom_table($prefix) {

	global $wpdb;

	$charset_collate = $wpdb->get_charset_collate();

	$sql = "CREATE TABLE {$prefix}novashare_meta (
		id BIGINT(20) NOT NULL AUTO_INCREMENT,
		post_id BIGINT(20),
		meta_key VARCHAR(255) DEFAULT '' NOT NULL,
		meta_value LONGTEXT,
		PRIMARY KEY (id)
	) $charset_collate;";

	require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
	dbDelta($sql);
}

//when new subsite is activated 
function novashare_activate_blog($blog_id) {
	if(!function_exists('is_plugin_active_for_network')) {
		require_once ABSPATH . 'wp-admin/includes/plugin.php';
	}

	if($blog_id instanceof WP_Site) {
		$blog_id = (int) $blog_id->blog_id;
	}

	if(is_plugin_active_for_network('novashare/novashare.php')) {

		global $wpdb;

		switch_to_blog($blog_id);

		//insert/update custom table for blog
		novashare_install_custom_table($wpdb->prefix);

		//update version
		if(get_option('novashare_version') != NOVASHARE_VERSION) {
			update_option('novashare_version', NOVASHARE_VERSION, false);
		}

		restore_current_blog();
	}
}
add_action('wp_initialize_site', 'novashare_activate_blog', 99);
add_action('activate_blog', 'novashare_activate_blog');

//check version for update
function novashare_version_check() {
	$install_flag = false;
	if(is_multisite()) {
		if(get_site_option('novashare_version') != NOVASHARE_VERSION) {
	    	$install_flag = true;
	    }
	}
	if(get_option('novashare_version') != NOVASHARE_VERSION) {
    	$install_flag = true;
    }
	if($install_flag) {
		novashare_install();
	}
}
add_action('plugins_loaded', 'novashare_version_check');

//uninstall plugin + delete options
function novashare_uninstall() {

	global $wpdb;

	//deactivate license if needed
	novashare_deactivate_license();

	//plugin options
	$novashare_options = array(
		'novashare',
		'novashare_tools',
		'novashare_license',
		'novashare_version'
	);

	if(is_multisite()) {
		$novashare_network = get_site_option('novashare_network');
		if(!empty($novashare_network['clean_uninstall']) && $novashare_network['clean_uninstall'] == 1) {
			delete_site_option('novashare_network');

			$sites = array_map('get_object_vars', get_sites(array('deleted' => 0)));
			if(is_array($sites) && $sites !== array()) {
				foreach($sites as $site) {
					foreach($novashare_options as $option) {
						delete_blog_option($site['blog_id'], $option);
					}

					//delete meta table for subsite
					$blog_prefix = $wpdb->get_blog_prefix($site['blog_id']);
	    			$wpdb->query("DROP TABLE IF EXISTS {$blog_prefix}novashare_meta");
				}
			}

			//remove stored version
			delete_site_option('novashare_version');
		}
	}
	else {
		$novashare_tools = get_option('novashare_tools');
		if(!empty($novashare_tools['clean_uninstall']) && $novashare_tools['clean_uninstall'] == 1) {
			foreach($novashare_options as $option) {
				delete_option($option);
			}

			//delete meta table
         	$wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}novashare_meta");
		}
	}
}
register_uninstall_hook(__FILE__, 'novashare_uninstall');

//subsite was deleted
function novashare_uninitialize_site($site_id) {
	global $wpdb;

	//delete our custom table for that site
	$wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}novashare_meta");
}
add_action('wp_uninitialize_site', 'novashare_uninitialize_site');

//all plugin file includes
include plugin_dir_path(__FILE__) . '/inc/settings.php';
include plugin_dir_path(__FILE__) . '/inc/functions.php';
include plugin_dir_path(__FILE__) . '/inc/functions_pinterest.php';
include plugin_dir_path(__FILE__) . '/inc/click_to_tweet.php';
include plugin_dir_path(__FILE__) . '/inc/share_counts.php';
include plugin_dir_path(__FILE__) . '/inc/share_counts_recovery.php';
include plugin_dir_path(__FILE__) . '/inc/meta.php';
include plugin_dir_path(__FILE__) . '/inc/widget.php';
include plugin_dir_path(__FILE__) . '/inc/functions_network.php';

//load EDD custom updater class
include(dirname(__FILE__) . '/Novashare_Plugin_Updater.php');