<?php
if(isset($_POST['novashare_apply_defaults'])) {
	check_admin_referer('novashare-network-apply');
	if(isset($_POST['novashare_network_apply_blog']) && is_numeric($_POST['novashare_network_apply_blog'])) {
		$blog = get_blog_details($_POST['novashare_network_apply_blog']);
		if($blog) {

			//apply default settings to selected blog
			if(is_multisite()) {
				$novashare_network = get_site_option('novashare_network');

				if(!empty($novashare_network['default'])) {

					if($blog->blog_id != $novashare_network['default']) {

						$option_names = array(
							'novashare',
							'novashare_extras'
						);

						foreach($option_names as $option_name) {

							//clear selected blog previous option
							delete_blog_option($blog->blog_id, $option_name);

							//grab new option from default blog
							$new_option = get_blog_option($novashare_network['default'], $option_name);

							//update selected blog with default option
							update_blog_option($blog->blog_id, $option_name, $new_option);

						}

						//Default Settings Updated Notice
						echo "<div class='notice updated is-dismissible'><p>" . __('Default settings applied!', 'novashare') . "</p></div>";
					}
					else {
						//Can't Apply to Network Default
						echo "<div class='notice error is-dismissible'><p>" . __('Select a site that is not already the Network Default.', 'novashare') . "</p></div>";
					}
				}
				else {
					//Network Default Not Set
					echo "<div class='notice error is-dismissible'><p>" . __('Network Default not set.', 'novashare') . "</p></div>";
				}
			}
		}
		else {
			//Blog Not Found Notice
			echo "<div class='notice error is-dismissible'><p>" . __('Error: Blog Not Found.', 'novashare') . "</p></div>";
		}
	}
}

//options updated
if(isset($_GET['updated'])) {
	echo "<div class='notice updated is-dismissible'><p>" . __('Options saved.', 'novashare') . "</p></div>";
}

//main network options form
echo "<form method='POST' action='edit.php?action=novashare_update_network_options'>";
	settings_fields('novashare_network');
	novashare_settings_section('novashare_network', 'novashare_network', 'dashicons-networking');
	submit_button();
echo "</form>";

//apply defaults custom form
echo "<form method='POST' style='clear: both; margin: 30px auto 10px;'>";
	echo '<div>';
		echo "<h2>" . __('Apply Default Settings', 'novashare') . "</h2>";
		wp_nonce_field('novashare-network-apply', '_wpnonce', true, true);
		echo "<p>" . __('Select a site from the dropdown and click to apply the settings from your network default (above).', 'novashare') . "</p>";

		echo "<select name='novashare_network_apply_blog' style='margin-right: 10px;'>";
			$sites = array_map('get_object_vars', get_sites(array('deleted' => 0, 'number' => 1000)));
			if(is_array($sites) && $sites !== array()) {
				echo "<option value=''>" . __('Select a Site', 'novashare') . "</option>";
				foreach($sites as $site) {
					echo "<option value='" . $site['blog_id'] . "'>" . $site['blog_id'] . ": " . $site['domain'] . $site['path'] . "</option>";
				}
			}
		echo "<select>";

		echo "<input type='submit' name='novashare_apply_defaults' value='" . __('Apply Default Settings', 'novashare') . "' class='button button-secondary' />";
	echo '</div>';
echo "</form>";