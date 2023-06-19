<?php
//save license key
if(isset($_POST['novashare_save_license']) && isset($_POST['novashare_license_key'])) {

	//save license option
	if(is_network_admin()) {
		update_site_option('novashare_license', array('key' => trim($_POST['novashare_license_key'])));
	}
	else {
		update_option('novashare_license', array('key' => trim($_POST['novashare_license_key'])));
	}

	if(is_multisite()) {

		//check license info
		$license_info = novashare_check_license();

		if(!empty($license_info->activations_left) && $license_info->activations_left == 'unlimited') {
			
			//activate after save
			novashare_activate_license();
		}
	}
	else {
		//activate after save
		novashare_activate_license();
	}
}

//activate license
if(isset($_POST['novashare_activate_license'])) {
	novashare_activate_license();
}

//deactivate license
if(isset($_POST['novashare_deactivate_license'])) {
	novashare_deactivate_license();
}

//remove license
if(isset($_POST['novashare_remove_license'])) {

	//deactivate before removing
	novashare_deactivate_license();

	//remove license option
	if(is_network_admin()) {
		delete_site_option('novashare_license');
	}
	else {
		delete_option('novashare_license');
	}
}

//get stored license data
$novashare_license = is_network_admin() ? get_site_option('novashare_license') : get_option('novashare_license');

//set license key
$license = !empty($novashare_license['key']) ? trim($novashare_license['key']) : null;

//start custom license form
echo "<form method='post' action=''>";

	echo '<div class="novashare-settings-section">';

		//tab header
		echo "<h2><span class='dashicons dashicons-admin-network'></span>" . __('License', 'novashare') . "</h2>";

		echo "<table class='form-table'>";
			echo "<tbody>";

				//license key
				echo "<tr>";
					echo "<th>" . novashare_title(__('License Key', 'novashare'), (empty($license) ? 'novashare_license_key' : false), 'https://novashare.io/docs/troubleshooting-license-key-activation/') . "</th>";
					echo "<td>";

						echo "<input id='novashare_license_key' name='novashare_license_key' type='text' class='regular-text' value='" . (!empty($license) ? substr($license, 0, 4) . '**************************' : '') . "' style='margin-right: 10px;' maxlength='50' />";

						if(empty($license)) {
							//save license button
							echo "<input type='submit' name='novashare_save_license' class='button button-primary' value='" . __('Save License', 'novashare') . "'>";
						}
						else {
							//remove license button
							echo "<input type='submit' class='button novashare-button-warning' name='novashare_remove_license' value='" . __('Remove License', 'novashare') . "' />";
						}

						novashare_tooltip(__('Save or remove your license key.', 'novashare'));

					echo "</td>";
				echo "</tr>";

				if(!empty($license)) {

					//force disable styles on license input
					echo "<style>
					input[name=\"novashare_license_key\"] {
						background: rgba(255,255,255,.5);
					    border-color: rgba(222,222,222,.75);
					    box-shadow: inset 0 1px 2px rgba(0,0,0,.04);
					    color: rgba(51,51,51,.5);
					    pointer-events: none;
					}
					</style>";

					//check license info
					$license_info = novashare_check_license();

					if(!empty($license_info)) {

						//activate/deactivate license
						if(!empty($license_info->license) && $license_info->license != 'invalid') {
							echo "<tr>";
								echo "<th>" . __('Activate License', 'novashare') . "</th>";
								echo "<td>";
									if($license_info->license == 'valid') {
										echo "<input type='submit' class='button-secondary' name='novashare_deactivate_license' value='" . __('Deactivate License', 'novashare') . "' style='margin-right: 10px;' />";
										echo "<span style='color:green;line-height:30px;'><span class='dashicons dashicons-cloud'style='line-height:30px;'></span> " . __('License is activated.', 'novashare') . "</span>";
									} 
									elseif(!is_multisite() || (!empty($license_info->activations_left) && $license_info->activations_left == 'unlimited')) {
										echo "<input type='submit' class='button-secondary' name='novashare_activate_license' value='" . __('Activate License', 'novashare') . "' style='margin-right: 10px;' />";
										echo "<span style='color:red;line-height:30px;'><span class='dashicons dashicons-warning'style='line-height:30px;'></span> " . __('License is not activated.', 'novashare') . "</span>";
									}
									else {
										echo "<span style='color:red;display: block;'>" . __('Unlimited License needed for use in a multisite environment. Please contact support to upgrade.', 'novashare') . "</span>";
									}
								echo "</td>";
							echo "</tr>";
						}

						//license status (active/expired)
						if(!empty($license_info->license)) {
							echo "<tr>";
								echo "<th>" . __('License Status', 'novashare') . "</th>";
								echo "<td" . ($license_info->license == "expired" ? " style='color: red;'" : "") . ">";
									echo ucfirst($license_info->license);
									if($license_info->license == "expired") {
										echo "<br />";
										echo "<a href='https://novashare.io/checkout/?edd_license_key=" . $license . "&download_id=60' class='button-primary' style='margin-top: 10px;' target='_blank'>" . __('Renew Your License for Updates + Support!', 'novashare') . "</a>";
									}
								echo "</td>";
							echo "</tr>";
						}

						//licenses used
						if(!empty($license_info->site_count) && !empty($license_info->license_limit) && !is_network_admin()) {
							echo "<tr>";
								echo "<th>" . __('Licenses Used', 'novashare') . "</th>";
								echo "<td>" . $license_info->site_count . "/" . $license_info->license_limit . "</td>";
							echo "</tr>";
						}

						//expiration date
						if(!empty($license_info->expires)) {
							echo "<tr>";
								echo "<th>" . __('Expiration Date', 'novashare') . "</th>";
								echo "<td>" . ($license_info->expires != 'lifetime' ? date("F d, Y", strtotime($license_info->expires)) : __('Lifetime', 'novashare')) . "</td>";
							echo "</tr>";
						}
					}
				}

			echo "</tbody>";
		echo "</table>";
	echo '</div>';
echo "</form>";