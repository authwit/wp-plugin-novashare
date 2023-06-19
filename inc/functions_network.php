<?php
//register network menu and settings
function novashare_network_admin_menu() {

	//add network settings menu item
    add_submenu_page('settings.php', 'Novashare Network Settings', 'Novashare', 'manage_network_options', 'novashare', 'novashare_admin');

    //create site option if not found
    if(get_site_option('novashare_network') == false) {    
        add_site_option('novashare_network', true);
    }
 
    add_settings_section('novashare_network', 'Network', '__return_false', 'novashare_network');
   
   	//network access
	add_settings_field(
		'access',
		novashare_title(__('Network Access', 'novashare'), 'access', 'https://novashare.io/docs/wordpress-multisite/'),
		'novashare_network_access_callback', 
		'novashare_network', 
		'novashare_network',
		array(
			'tooltip' => __('Choose who has access to manage Novashare plugin settings.', 'novashare')
		)
	);

	//network default
	add_settings_field(
		'default', 
		novashare_title(__('Network Default', 'novashare'), 'default', 'https://novashare.io/docs/wordpress-multisite/'),
		'novashare_network_default_callback', 
		'novashare_network', 
		'novashare_network',
		array(
			'tooltip' => __('Choose a subsite that you want to pull default settings from.', 'novashare')
		)
	);

	//clean uninstall
    add_settings_field(
        'clean_uninstall', 
        novashare_title(__('Clean Uninstall', 'novashare'), 'clean_uninstall', 'https://novashare.io/docs/clean-uninstall/'), 
        'novashare_print_input', 
        'novashare_network', 
        'novashare_network', 
        array(
            'id' => 'clean_uninstall',
            'option' => 'novashare_network',
            'tooltip' => __('When enabled, this will cause all Novashare options data to be removed from your database when the plugin is uninstalled.', 'novashare')
        )
    );

	register_setting('novashare_network', 'novashare_network');
}
add_filter('network_admin_menu', 'novashare_network_admin_menu');

//network access callback
function novashare_network_access_callback($args) {
	$novashare_network = get_site_option('novashare_network');

	echo "<select name='novashare_network[access]' id='access'>";
		echo "<option value=''>" . __('Site Admins (Default)', 'novashare') . "</option>";
		echo "<option value='super' " . ((!empty($novashare_network['access']) && $novashare_network['access'] == 'super') ? "selected" : "") . ">" . __('Super Admins Only', 'novashare') . "</option>";
	echo "<select>";

	//tooltip
    if(!empty($args['tooltip'])) {
        novashare_tooltip($args['tooltip']);
    }
}

//network default callback
function novashare_network_default_callback($args) {
	$novashare_network = get_site_option('novashare_network');

	echo "<select name='novashare_network[default]' id='default'>";
		$sites = array_map('get_object_vars', get_sites(array('deleted' => 0, 'number' => 1000)));
		if(is_array($sites) && $sites !== array()) {
			echo "<option value=''>" . __('None', 'novashare') . "</option>";
			foreach($sites as $site) {
				echo "<option value='" . $site['blog_id'] . "' " . ((!empty($novashare_network['default']) && $novashare_network['default'] == $site['blog_id']) ? "selected" : "") . ">" . $site['blog_id'] . ": " . $site['domain'] . $site['path'] . "</option>";
			}
		}
	echo "<select>";

	//tooltip
    if(!empty($args['tooltip'])) {
        novashare_tooltip($args['tooltip']);
    }
}

//update novashare network options
function novashare_update_network_options() {

	//verify post referring page
  	check_admin_referer('novashare_network-options');
 
	//get registered options
	global $new_whitelist_options;
	$options = $new_whitelist_options['novashare_network'];

	//loop through registered options
	foreach($options as $option) {
		if(isset($_POST[$option])) {

			//update site option
			update_site_option($option, $_POST[$option]);
		}
	}

	//redirect to network settings page
	wp_redirect(add_query_arg(array('page' => 'novashare', 'updated' => 'true'), network_admin_url('settings.php')));

	exit;
}
add_action('network_admin_edit_novashare_update_network_options',  'novashare_update_network_options');