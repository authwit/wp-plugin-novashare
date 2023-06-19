<?php
//selected tab
$tab = $_GET['tab'] ?? (is_network_admin() ? 'network' : 'options');

//settings wrapper
echo '<div id="novashare-admin" class="wrap">';

	//hidden h2 for admin notice placement
	echo '<h2 style="display: none;"></h2>';

	//flex container
	echo '<div id="novashare-admin-container">';

		echo '<div>';

			//header
			echo '<div id="novashare-admin-header" class="novashare-admin-block">';

				echo '<div id="novashare-logo-bar">';

					//logo
					echo '<svg id="novashare-logo" viewBox="0 0 486 80"><g transform="matrix(1,0,0,1,-6.21734,-8.30819)"><g transform="matrix(0.131863,0,0,0.131863,248.911,48.1967)"><g transform="matrix(1,0,0,1,-1840.5,-302.5)"><g transform="matrix(4.16667,0,0,4.16667,-2334.93,-3853.34)"><g><path d="M826.219,973.874C819.274,973.975 813.061,975.961 808.128,979.527L808.128,975.015L793.717,975.015L793.717,1045.98L808.254,1045.98L808.254,1006.95C808.254,996.061 815.579,988.387 826.066,988.285C831.918,988.178 836.584,989.808 839.651,992.845C842.656,995.821 844.18,1000.18 844.18,1005.8L844.18,1045.98L858.717,1045.98L858.717,1005.68C858.717,986.357 845.985,973.874 826.219,973.874Z" style="fill:#4D4595;"/><path d="M902.116,1032.45C891.375,1032.45 880.538,1025.7 880.538,1010.62C880.538,997.321 889.412,988.031 902.116,988.031C914.82,988.031 923.692,997.321 923.692,1010.62C923.692,1025.7 912.855,1032.45 902.116,1032.45ZM902.116,973.874C881.263,973.874 866.129,989.328 866.129,1010.62C866.129,1031.62 881.263,1046.86 902.116,1046.86C922.968,1046.86 938.103,1031.62 938.103,1010.62C938.103,989.328 922.968,973.874 902.116,973.874Z" style="fill:#4D4595;"/><path d="M976.463,1023.66L956.427,975.015L940.537,975.015L970.27,1045.98L982.656,1045.98L1012.39,975.015L996.498,975.015L976.463,1023.66Z" style="fill:#4D4595;"/><path d="M1050.26,1032.58C1037.83,1032.58 1028.81,1023.29 1028.81,1010.49C1028.81,997.772 1037.83,988.537 1050.26,988.537C1065.36,988.537 1072.25,999.952 1072.25,1010.56C1072.25,1016.4 1070.3,1021.81 1066.76,1025.79C1062.82,1030.23 1057.11,1032.58 1050.26,1032.58ZM1071.71,975.015L1071.71,981.528C1065.9,976.901 1058.36,974.253 1050.26,974.253C1029.74,974.253 1014.27,989.834 1014.27,1010.49C1014.27,1031.3 1029.74,1046.99 1050.26,1046.99C1058.44,1046.99 1065.78,1044.4 1071.71,1039.55L1071.71,1045.98L1086.24,1045.98L1086.24,975.015L1071.71,975.015Z" style="fill:#4D4595;"/><path d="M1129.97,1003.09L1127.76,1002.71C1119.73,1001.3 1113.07,999.768 1112.94,995.401C1112.9,994.034 1113.34,992.93 1114.32,991.929C1116.68,989.526 1121.74,988.105 1127.67,988.158C1134.14,988.158 1139.19,989.087 1144.45,993.713L1147.48,996.378L1156.89,986.345L1153.85,983.497C1147.14,977.196 1138.33,974.001 1127.72,974.001C1117.86,973.909 1109.3,976.787 1104.17,981.91C1100.51,985.573 1098.61,990.342 1098.66,995.702C1098.82,1012.02 1114.81,1014.68 1126.51,1016.63C1138.31,1018.53 1143.55,1020.16 1143.42,1024.79C1143.15,1031.93 1132.58,1032.7 1128.05,1032.7C1121.59,1032.7 1113.07,1030.38 1108.52,1023.84L1105.85,1019.99L1095.1,1029.54L1097.05,1032.55C1102.95,1041.59 1114.49,1046.99 1127.93,1046.99C1146.01,1046.99 1157.46,1038.73 1157.82,1025.51C1158.59,1007.89 1140.68,1004.88 1129.97,1003.09Z" style="fill:#4D4595;"/><path d="M1200.12,974.127C1192.99,974.23 1186.74,976.23 1181.77,979.906L1181.77,948.896L1167.49,948.896L1167.49,1045.98L1182.02,1045.98L1182.02,1007.45C1182.02,996.419 1189.66,988.411 1200.18,988.411C1210.94,988.411 1216.17,994.141 1216.17,1005.93L1216.17,1045.98L1230.59,1045.98L1230.59,1005.93C1230.59,986.314 1218.93,974.127 1200.12,974.127Z" style="fill:#4D4595;"/><path d="M1275,1032.58C1262.57,1032.58 1253.55,1023.29 1253.55,1010.49C1253.55,997.772 1262.57,988.537 1275,988.537C1290.11,988.537 1296.99,999.952 1296.99,1010.56C1296.99,1016.4 1295.04,1021.81 1291.5,1025.79C1287.56,1030.23 1281.86,1032.58 1275,1032.58ZM1296.45,981.528C1290.65,976.901 1283.11,974.253 1275,974.253C1254.49,974.253 1239.01,989.834 1239.01,1010.49C1239.01,1031.3 1254.49,1046.99 1275,1046.99C1283.19,1046.99 1290.53,1044.4 1296.45,1039.55L1296.45,1045.98L1310.99,1045.98L1310.99,975.015L1296.45,975.015L1296.45,981.528Z" style="fill:#4D4595;"/><path d="M1352.16,974.381C1345.64,974.381 1339.82,976.156 1335.28,979.372L1335.18,975.015L1321.12,975.015L1321.12,1045.98L1335.65,1045.98L1335.65,1005.3C1335.65,996 1342.92,988.639 1352.16,988.537C1355.3,988.537 1358.15,989.311 1360.63,990.836L1364.35,993.128L1371.17,980.73L1367.76,978.661C1362.83,975.672 1357.55,974.228 1352.16,974.381Z" style="fill:#4D4595;"/><path d="M1387.74,1003.8C1390.34,994.201 1398.14,988.031 1408.28,988.031C1420.1,988.031 1427.5,993.716 1429.14,1003.8L1387.74,1003.8ZM1435.33,984.845C1428.99,977.852 1419.39,974.001 1408.28,974.001C1387.76,974.001 1372.29,989.69 1372.29,1010.49C1372.29,1031.57 1387.43,1046.86 1408.28,1046.86C1420.55,1046.86 1432.17,1041.62 1438.6,1033.18L1441.06,1029.94L1430.11,1021.18L1427.53,1024.6C1424.04,1029.22 1416.12,1032.45 1408.28,1032.45C1399.67,1032.45 1390.59,1027.81 1387.74,1017.57L1442.99,1017.57L1443.36,1013.82C1444.53,1001.95 1441.75,991.929 1435.33,984.845Z" style="fill:#4D4595;"/><path d="M665.014,1007.92C663.741,1008.34 662.477,1008.75 661.202,1009.16C660.454,1009.41 659.695,1009.65 658.945,1009.88C655.969,1007.48 651.874,1006.58 647.976,1007.87C641.852,1009.87 638.509,1016.47 640.517,1022.59C642.524,1028.71 649.118,1032.06 655.242,1030.05C659.19,1028.76 661.982,1025.57 662.928,1021.83C664.935,1021.2 666.938,1020.55 668.941,1019.9C671.479,1019.07 704.677,1008.09 733.536,993.783C735.154,1025.62 715.462,1055.86 683.701,1066.26C658.114,1074.64 631.229,1068.01 612.577,1051.17C608.938,1047.91 605.619,1044.25 602.688,1040.25C578.821,1044.22 563.989,1043.72 560.382,1041.05C561.645,1036.98 572.241,1028.5 591.768,1018.32C608.099,1009.78 630.677,1000.02 659.327,990.641C661.32,989.991 663.333,989.338 665.348,988.705C668.324,991.109 672.42,992.008 676.328,990.724C682.452,988.717 685.795,982.124 683.787,975.999C681.779,969.865 675.186,966.532 669.061,968.53C665.123,969.816 662.34,973.003 661.393,976.742C659.387,977.364 657.394,978.014 655.41,978.664C652.795,979.52 618.352,990.903 589.092,1005.61C585.215,972.076 605.259,939.335 638.556,928.424C665.883,919.474 694.719,927.64 713.412,947.093C716.669,950.452 719.494,953.999 722.067,958.034C745.623,954.164 760.378,954.892 763.94,957.515C762.663,961.651 751.783,970.299 731.696,980.703C715.434,989.13 693.139,998.71 665.014,1007.92Z" style="fill:#4D4595;"/></g></g></g></g></g></svg>';

					//menu toggle
					echo '<a href="#" id="novashare-menu-toggle"><span class="dashicons dashicons-menu"></span></a>';
				echo '</div>';

				//menu
				echo '<div id="novashare-menu">';

					if(is_network_admin()) {
						//network
						echo '<a href="?page=novashare&tab=network" class="' . ($tab == 'network' || '' ? 'active' : '') . '" title="' . __('Network', 'novashare') . '"><span class="dashicons dashicons-networking"></span><span class="perfmatters-menu-label">' . __('Network', 'novashare') . '</span></a>';
					}
					else {

						//options
						echo '<a href="?page=novashare"><span class="dashicons dashicons-admin-settings"></span><span class="novashare-menu-label">' . __('Options', 'novashare') . '</span></a>';
						echo '<div class="novashare-subnav' . ($tab !== 'options' ? ' hidden' : '') . '">';
							echo '<a href="#options-inline" id="inline-section" rel="inline"' . ($tab == 'options' ? ' class="active"' : '') . '><span class="dashicons dashicons-align-left"></span><span class="novashare-menu-label">' . __('Inline Content', 'novashare') . '</span></a>';
							echo '<a href="#options-floating" id="floating-section" rel="floating"><span class="dashicons dashicons-laptop"></span><span class="novashare-menu-label">' . __('Floating Bar', 'novashare') . '</span></a>';
							echo '<a href="#options-share" id="share-section" rel="share"><span class="dashicons dashicons-share"></span><span class="novashare-menu-label">' . __('Share Button', 'novashare') . '</span></a>';
							echo '<a href="#options-click-to-tweet" id="click-to-tweet-section" rel="click-to-tweet"><span class="dashicons dashicons-twitter"></span><span class="novashare-menu-label">' . __('Click to Tweet', 'novashare') . '</span></a>';
							echo '<a href="#options-pinterest" id="pinterest-section" rel="pinterest"><span class="dashicons dashicons-pinterest"></span><span class="novashare-menu-label">' . __('Pinterest', 'novashare') . '</span></a>';
							echo '<a href="#options-config" id="config-section" rel="config"><span class="dashicons dashicons-admin-generic"></span><span class="novashare-menu-label">' . __('Configuration', 'novashare') . '</span></a>';
						echo '</div>';

						//tools
						echo '<a href="?page=novashare&tab=tools"' . ($tab == 'tools' ? ' class="active"' : '') . '><span class="dashicons dashicons-admin-tools"></span><span class="novashare-menu-label">' . __('Tools', 'novashare') . '</span></a>';
					}

					//license
					if(!is_plugin_active_for_network('novashare/novashare.php') || is_network_admin()) {
						echo '<a href="?page=novashare&tab=license"' . ($tab == 'license' ? ' class="active"' : '') . '><span class="dashicons dashicons-admin-network"></span><span class="novashare-menu-label">' . __('License', 'novashare') . '</span></a>';
					}

					//support
					echo '<a href="?page=novashare&tab=support"' . ($tab == 'support' ? ' class="active"' : '') . '><span class="dashicons dashicons-editor-help"></span><span class="novashare-menu-label">' . __('Support', 'novashare') . '</span></a>';
				echo '</div>';
			echo '</div>';
		echo '</div>';

		echo '<div style="flex-grow: 1;">';
			echo '<div class="novashare-admin-block">';

				//version number
				echo '<span style="position: absolute; top: 20px; right: 20px; line-height: 24px; color: #fff; opacity: 0.5;" class="novashare-mobile-hide">' . __('Version', 'novashare') . ' ' . NOVASHARE_VERSION . '</span>';

				//options tab
				if($tab == 'options') {

					//get options
					$novashare = get_option('novashare');

					echo '<form method="post" action="options.php" id="novashare-options-form">';

						//options subnav
						echo '<input type="hidden" name="section" id="subnav-section" />';

							settings_fields('novashare');

							//inline content
							echo '<section id="options-inline" class="section-content">';
								novashare_settings_section('novashare', 'inline', 'dashicons-align-left');
								novashare_settings_section('novashare', 'inline_display');
								novashare_settings_section('novashare', 'inline_design');
								novashare_settings_section('novashare', 'inline_share_counts');
								novashare_settings_section('novashare', 'inline_cta');
							echo '</section>';

							//floating bar
							echo '<section id="options-floating" class="section-content hide">';
								novashare_settings_section('novashare', 'floating', 'dashicons-laptop');
								novashare_settings_section('novashare', 'floating_display');
								novashare_settings_section('novashare', 'floating_design');
								novashare_settings_section('novashare', 'floating_mobile', '', 'floating-hide_below_breakpoint' . (!empty($novashare['floating']['hide_below_breakpoint']) ? ' hidden' : ''));
								novashare_settings_section('novashare', 'floating_share_counts');
							echo '</section>';

							//share button
							echo '<section id="options-share" class="section-content hide">';
								novashare_settings_section('novashare', 'share', 'dashicons-share');
								novashare_settings_section('novashare', 'share_cta');
								novashare_settings_section('novashare', 'share_design');
							echo '</section>';
							
							//click to tweet
							echo '<section id="options-click-to-tweet" class="section-content hide">';
								novashare_settings_section('novashare', 'click_to_tweet', 'dashicons-twitter');
							echo '</section>';

							//pinterest
							echo '<section id="options-pinterest" class="section-content hide">';
								novashare_settings_section('novashare', 'pinterest', 'dashicons-pinterest');
								novashare_settings_section('novashare', 'pinterest_image_pins');
							echo '</section>';
							
							//configuration
							echo '<section id="options-config" class="section-content hide">';
								novashare_settings_section('novashare', 'config', 'dashicons-admin-generic');
								novashare_settings_section('novashare', 'config_meta');
								novashare_settings_section('novashare', 'config_share_counts');
								novashare_settings_section('novashare', 'config_ga');
								novashare_settings_section('novashare', 'config_link_shortening');
								novashare_settings_section('novashare', 'config_share_count_recovery');
							echo '</section>';
							
							submit_button();

					echo '</form>';

					//display correct section based on URL anchor
					echo '<script>!function(a){var t=a.trim(window.location.hash);if(t){a("#novashare-options-form").attr("action","options.php"+t);var e=a("#novashare-menu .novashare-subnav a.active");a(e).removeClass("active");var o=a(t+"-section");a(o).addClass("active"),a(a(e).attr("href")).addClass("hide"),a(a(o).attr("href")).removeClass("hide")}}(jQuery);</script>';

				//tools tab
				}
				elseif($tab == 'tools') {

					echo '<form method="post" action="options.php" enctype="multipart/form-data">';

						settings_fields('novashare_tools');
						novashare_settings_section('novashare_tools', 'novashare_tools', 'dashicons-admin-tools');

						//add migrator settings if needed
						if(function_exists('novashare_migrator_settings')) {
							novashare_settings_section('novashare_tools', 'migrate');
						}

						submit_button();

					echo '</form>';

				}
				elseif($tab == 'license') {

					//license custom form output
					require_once('license.php');

				}
				elseif($tab == 'support') {

					//support output
					require_once('support.php');
				}
				elseif($tab == 'network') {

					//network output
					require_once('network.php');
				}

			echo '</div>';
		echo '</div>';
	echo '</div>';
echo '</div>';