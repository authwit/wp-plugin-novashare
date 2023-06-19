<?php
class novashare_follow_widget extends WP_Widget {

	//register the widget
	function __construct() {
		parent::__construct(
			'novashare_follow_widget',
			esc_html__('Follow Widget', 'novashare'),
			array('description' => __('Add Novashare follow buttons for your social network profiles.', 'novashare'))
		);

		//enqueue admin scripts
		add_action('admin_enqueue_scripts', array($this, 'novashare_follow_widget_enqueue_admin_scripts'));

		//print admin inline script
		add_action('admin_footer-widgets.php', array($this, 'novashare_follow_widget_print_inline_scripts'), 9999);

		//front end scripts
		add_action('wp_enqueue_scripts', array($this, 'novashare_follow_widget_enqueue_scripts'));
	}

	//front end display
	public function widget($args, $instance) {

		echo $args['before_widget'];

		//print title
		echo (!empty($instance['title'])) ? $args['before_title'] . apply_filters('widget_title', $instance['title']) . $args['after_title'] : '';

		if(!empty($instance['selected_networks'])) {

			//unique widget id
			$instance['id'] = !empty($args['widget_id']) ? esc_attr($args['widget_id']) : '';

			echo novashare_print_follow_buttons($instance);
		}

		echo $args['after_widget'];
	}

	//widget admin form
	public function form($instance) {

		//prep form values
		$values = array(
			'title',
			'shape',
			'alignment',
			'button_size',
			'button_margin',
			'button_color',
			'button_hover_color',
			'icon_color',
			'icon_hover_color',
			'new_tab',
			'selected_networks'
		);

		foreach($values as $value) {
			if(!isset($instance[$value]) || (empty($instance[$value]) && $instance[$value] !== 0)) {
				$instance[$value] = '';
			}
		}

		//title
		echo "<p style='margin-bottom: 0px;'>";
			echo "<label for='" . $this->get_field_id('title') . "'>" . __('Title', 'novashare') . ":</label>";
			echo "<input class='widefat' id='" . $this->get_field_id('title') . "' name='" . $this->get_field_name('title') . "' type='text' value='" . esc_attr($instance['title']) . "'>";
		echo "</p>";

		echo "<div style='display: flex; justify-content: space-between;'>";

			//icon shape
			echo "<p style='flex: 1 0 0px;'>";
				echo "<label for='" . $this->get_field_id('shape') . "'>" . __('Button Shape', 'novashare') . ":</label>";
				echo "<select class='widefat' id='" . $this->get_field_id('shape') . "' name='" . $this->get_field_name('shape') . "'>";
					echo "<option value='' " . selected($instance['shape'], '' ) . ">" . __('Squared', 'novashare' ) . "</option>";
					echo "<option value='rounded' " . selected($instance['shape'], 'rounded' ) . ">" . __('Rounded', 'novashare' ) . "</option>";
					echo "<option value='circular' " . selected($instance['shape'], 'circular' ) . ">" . __('Circular', 'novashare' ) . "</option>";
				echo "<select>";
			echo "</p>";

			//spacer
			echo "<span style='flex: 0 1 10px;'></span>";

			//alignment
			echo "<p style='flex: 1 0 0px;'>";
				echo "<label for='" . $this->get_field_id('alignment') . "'>" . __('Button Alignment', 'novashare') . ":</label>";
				echo "<select class='widefat' id='" . $this->get_field_id('alignment') . "' name='" . $this->get_field_name('alignment') . "'>";
					echo "<option value='' " . selected($instance['alignment'], '') . ">" . __('Left', 'novashare' ) . "</option>";
					echo "<option value='right' " . selected($instance['alignment'], 'right') . ">" . __('Right', 'novashare' ) . "</option>";
					echo "<option value='center' " . selected($instance['alignment'], 'center') . ">" . __('Center', 'novashare' ) . "</option>";
				echo "<select>";
			echo "</p>";

		echo "</div>";

		//icon size
		echo "<p>";
			echo "<label>";
				echo "<input class='widefat' style='display: inline-block; max-width: 65px;' id='" . $this->get_field_id('button_size') . "' name='" . $this->get_field_name('button_size') . "' type='text' placeholder='50' value='" . $instance['button_size'] . "'>";
				echo "<span class='novashare-pixel-label' style='display: inline-block; height: 29px; line-height: 30px; border-left: 1px solid #7e8993; margin-left: -27px; margin-right: 6px; padding: 0px 6px; border-radius: 0px 3px 3px 0px;'>px</span>";
				_e('Button Size', 'novashare');
			echo "</label>";
		echo "</p>";

		//button margin
		echo "<p>";
			echo "<label>";
				echo "<input class='widefat' style='display: inline-block; max-width: 65px;' id='" . $this->get_field_id('button_margin') . "' name='" . $this->get_field_name('button_margin') . "' type='text' placeholder='10' value='" . $instance['button_margin'] . "'>";
				echo "<span class='novashare-pixel-label' style='display: inline-block; height: 29px; line-height: 30px; border-left: 1px solid #7e8993; margin-left: -27px; margin-right: 6px; padding: 0px 6px; border-radius: 0px 3px 3px 0px;'>px</span>";
				_e('Button Margin', 'novashare');
			echo "</label>";
		echo "</p>";

		echo "<hr style='margin: 15px auto;' />";

		//background color
		echo "<p>";
			echo "<input class='widefat novashare-color-picker' id='" . $this->get_field_id('button_color') . "' name='" . $this->get_field_name('button_color') . "' type='text' value='" . $instance['button_color'] . "'>";
			echo "<label>" . __('Button Color', 'novashare') . "</label>";
		echo "</p>";

		//background hover color
		echo "<p>";
			echo "<input class='widefat novashare-color-picker' id='" . $this->get_field_id('button_hover_color') . "' name='" . $this->get_field_name('button_hover_color') . "' type='text' value='" . $instance['button_hover_color'] . "'>";
			echo "<label>" . __('Button Hover Color', 'novashare') . "</label>";
		echo "</p>";

		//icon color
		echo "<p>";
			echo "<input class='widefat novashare-color-picker' id='" . $this->get_field_id('icon_color') . "' name='" . $this->get_field_name('icon_color') . "' type='text' value='" . $instance['icon_color'] . "'>";
			echo "<label>" . __('Icon Color', 'novashare') . "</label>";
		echo "</p>";

		//icon hover color
		echo "<p>";
			echo "<input class='widefat novashare-color-picker' id='" . $this->get_field_id('icon_hover_color') . "' name='" . $this->get_field_name('icon_hover_color') . "' type='text' value='" . $instance['icon_hover_color'] . "'>";
			echo "<label>" . __('Icon Hover Color', 'novashare') . "</label>";
		echo "</p>";

		//new tab
		echo "<p>";
			echo "<label>";
				echo "<input id='" . $this->get_field_id('new_tab') . "' type='checkbox' name='" . $this->get_field_name('new_tab') . "' value='1' " . checked(1, $instance['new_tab'], false) . "/>";
				_e('Open Links in New Tab', 'novashare');
			echo "</label>";
		echo "</p>";

		echo "<hr style='margin: 15px auto;' />";

		//selected networks
		echo "<div class='novashare-follow-networks-wrapper'>";

			echo "<ul class='novashare-follow-networks-container novashare-sortable' style='padding: 0px; overflow: auto;'>";

				if(empty($instance['selected_networks'])) {
					$instance['selected_networks'] = array('' => '');
				}

				//get array of follow networks
				$follow_networks = novashare_networks('follow');

				foreach($instance['selected_networks'] as $network => $value) {

					echo "<li style='display: flex; align-items: center; margin-bottom: 10px;'>";

					//sortable move handle
					echo "<span class='novashare-sortable-handle dashicons dashicons-move' style='margin-right: 5px; cursor: pointer;'></span>";

						echo "<span style='flex-grow: 1;'>";

							//network select
							echo "<select class='widefat novashare-choose-network'>";
								echo "<option value=''>" . __('Select a Network', 'novashare') . "</option>";
								foreach($follow_networks as $follow_network_key => $follow_network_value) {
									echo "<option value='" . $follow_network_key . "' " . selected($network, $follow_network_key) . ">" . $follow_network_value['name'] . "</option>";
								}
							echo "</select>";

							//network user input
							echo "<input class='widefat novashare-network-value' rel='" . $this->get_field_name('selected_networks') . "' name='" . $this->get_field_name('selected_networks') . "[" . $network . "]' type='text' value='" . esc_attr($value) . "' style='margin-top: 5px;" . (empty($network) ? " display: none;" : "") . "'>";
						echo "</span>";

						//remove network button
						echo "<span class='dashicons dashicons-trash novashare-remove-selected-network' style='margin-left: 5px; cursor: pointer;'></span>";
						
					echo "</li>";
				}

			echo "</ul>";

			//add network button
			echo "<button class='button novashare-add-selected-network' style='margin-bottom: 10px;'>" . __('Add Network', 'novashare') . "</button>";

		echo "</div>";
	}

	//save form data
	public function update($new_instance, $old_instance) {

		//sanitize values
		$new_instance['title'] = strip_tags($new_instance['title']);
		$new_instance['button_size'] = intval($new_instance['button_size']);
		if($new_instance['button_size'] === 0) {
			$new_instance['button_size'] = '';
		}
		$new_instance['button_margin'] = !empty($new_instance['button_margin']) || $new_instance['button_margin'] === 0 ? intval($new_instance['button_margin']) : '';
		$new_instance['button_color'] = novashare_sanitize_hex_value($new_instance['button_color']);
		$new_instance['button_hover_color'] = novashare_sanitize_hex_value($new_instance['button_hover_color']);
		$new_instance['icon_color'] = novashare_sanitize_hex_value($new_instance['icon_color']);
		$new_instance['icon_hover_color'] = novashare_sanitize_hex_value($new_instance['icon_hover_color']);

		if(!empty($new_instance['selected_networks'])) {
			foreach($new_instance['selected_networks'] as $network => $value) {

				if($network == 'email' && is_email($value)) {
					$new_instance['selected_networks'][$network] = sanitize_email($value);
				}
				elseif($network == 'messenger' || $network == 'phone' || $network == 'skype' || $network == 'line') {
					$new_instance['selected_networks'][$network] = strip_tags($value);
				}
				else {
					$new_instance['selected_networks'][$network] = esc_url($value);
				}
			}
		}

		//return final values
		return $new_instance;
	}

	//enqueue admin scripts
	function novashare_follow_widget_enqueue_admin_scripts() {

		//make sure were on widgets screen
		$screen = get_current_screen();

		//print_r($screen);
		if($screen->base !== 'widgets' && $screen->base !== 'customize') {
			//return;
		}

	    wp_enqueue_script('jquery-ui-sortable');
		wp_enqueue_style('wp-color-picker');
		wp_enqueue_script('wp-color-picker');
		wp_enqueue_script('underscore');
	}

	function novashare_follow_widget_print_inline_scripts() {
		?>
		<script>
			(function($) {

				$(document).ready(function() {

					//search for color pickers
					$('#widgets-right .widget:has(.novashare-color-picker)').each(function() {
						initNovashareColorPicker($(this));
					});

					//search for sortables
					$('#widgets-right .widget:has(.novashare-sortable)').each(function() {
						initNovashareSortable($(this));
					});

					//selected network change
					$(document).on("change", ".novashare-choose-network", function() {
						var select = $(this);
						var val = $(this).find(':selected').val();

						//block existing select value
						var existingSelect = $(this).closest('.novashare-follow-networks-container').find('select option[value="' + val + '"]:selected');
						if(existingSelect.length > 1) {
							alert('<?php _e('That network is already in use.','novashare'); ?>')
					        $(this).val($.data(this, 'current'));
					        return false;
					    }

						var $input = $(this).closest('li').find('input');

						//set input placeholder
						if(val == 'email') {
							$input.attr('placeholder', 'email@example.com or https://');
						}
						else if(val == 'phone') {
							$input.attr('placeholder', '(123)-456-7890');
						} 
						else if(val == 'messenger') {
							$input.attr('placeholder', '<?php _e('Page Name', 'novashare'); ?>');
						}
						else if(val == 'skype') {
							$input.attr('placeholder', '<?php _e('Username', 'novashare'); ?>');
						}
						else if(val == 'line') {
							$input.attr('placeholder', 'LINE ID');
						}
						else if(val == '' ) {
							$input.attr('placeholder', '');
						}
						else {
							$input.attr('placeholder', 'https://');
						}

						$input.attr('name', $input.attr('rel') + '[' + val + ']');

						if(val == '') {
							$input.hide();
						}
						else {
							$input.show();
						}
					});

					//add network removed
					$(document).on("click", ".novashare-add-selected-network", function(e) {
						e.preventDefault();
						
						var $container = $(this).closest('.novashare-follow-networks-wrapper').find('.novashare-follow-networks-container');

					   	var $row = $container.find('li').last().clone();

					   	$row.find('select').val('');

					   	$row.find('input').val('');
					   	$row.find('input').removeAttr('name placeholder');
					   	$row.find('input').hide();

					   	$container.append($row);
					});

					//selected network removed
					$(document).on("click", ".novashare-remove-selected-network", function() {

						var $container = $(this).closest('.novashare-follow-networks-container');

						if($container.find('li').length == 1) {
							var $row = $(this).closest('li');

						   	$row.find('select').val('');

						   	$row.find('input').val('');
						   	$row.find('input').removeAttr('name placeholder');
						   	$row.find('input').hide();
						}
						else {
							$(this).closest('li').remove();
						}

						$container.find('input:first').trigger('change');
					});
				});

				//initialize widget color pickers
				function initNovashareColorPicker(widget) {
					widget.find('.novashare-color-picker').wpColorPicker({
						change: function(e, ui) {
						  	$(e.target).val(ui.color.toString());
					  		$(e.target).trigger('change');
						},
						clear: function(e, ui) {
						  	$(e.target).trigger('change');
						}
					});
				}

				//initialize widget sortables
				function initNovashareSortable(widget) {
				    widget.find(".novashare-sortable").sortable({
				    	handle: ".novashare-sortable-handle",
				    	delay: 150,
				    	stop: function(e, ui) {
				    		$(e.target).find('select:first').trigger('change');
						}
				    });
				}

				//trigger widget form update
				$(document).on('widget-added widget-updated', triggerNovashareFormUpdate);

				function triggerNovashareFormUpdate(event, widget) {

					//re-initialize jquery ui elements
					initNovashareColorPicker(widget);
					initNovashareSortable(widget);
				}

			}(jQuery));

		</script>
		<?php
	}

	//enqueue from end scripts
	function novashare_follow_widget_enqueue_scripts() {

		//make sure widget is active
	    if(is_active_widget(false, false, $this->id_base, true)) {

	    	//enqueue main novashare stylesheet
	      	if(!wp_style_is('novashare-css')) {
				wp_register_style('novashare-css', plugins_url('novashare/css/style.min.css'), array(), NOVASHARE_VERSION);
				wp_enqueue_style('novashare-css');
			}
		}           
  	}
}

//register widgets
add_action('widgets_init', 'novashare_register_widgets');

function novashare_register_widgets() {
    register_widget('novashare_follow_widget');
}

//follow button output
function novashare_print_follow_buttons($instance) {

	$output = novashare_follow_inline_styles($instance);

	$alignment = !empty($instance['alignment']) ? ' ns-align-' . $instance['alignment'] : '';

	//get container classes
	$button_class = "";
	if(!empty($instance['shape'])) {
		$button_class.= " ns-" . $instance['shape'];
	}

	//print follow buttons
	$output.= "<div class='ns-buttons" . (!empty($instance['location']) ? ' ns-' . $instance['location'] : '') . " " . $instance['id'] . " ns-no-print'>";
		$output.= "<div class='ns-buttons-wrapper" . $alignment . "'>";

			$networks = novashare_networks('follow');
			$target = !empty($instance['new_tab']) ? "_blank" : "_self";

			//remove mobile networks on desktop
			$mobile_networks = apply_filters('novashare_mobile_networks', array());
			if(!empty($mobile_networks) && is_array($mobile_networks) && !wp_is_mobile()) {
				foreach($mobile_networks as $mobile_network) {
					if(isset($instance['selected_networks'][$mobile_network])) {
						unset($instance['selected_networks'][$mobile_network]);
					}
				}
			}

			//set subscribe global link if needed
			if(isset($instance['selected_networks']['subscribe']) && empty($instance['selected_networks']['subscribe'])) {
				$novashare = get_option('novashare');
				$instance['selected_networks']['subscribe'] = $novashare['subscribe_link'] ?? '';
			}

			foreach($instance['selected_networks'] as $network => $value) {

				if(!empty($value)) {

					//prefix specific network values
					if(is_email($value)) {
						$new_value = 'mailto:' . $value;
					}
					elseif($network == 'phone') {
						$new_value = 'tel:' . $value;
					}
					elseif($network == 'messenger') {
						$new_value = 'https://m.me/' . $value;
					}
					elseif($network == 'skype') {
						$new_value = 'skype:' . $value;
					}
					elseif($network == 'line') {
						$new_value = 'https://line.me/R/home/public/profile?id=' . $value;
					}
					else {
						$new_value = esc_url($value);
					}

					$new_value = apply_filters('novashare_follow_network_link', $new_value, $network);

					//print network button
					$output.= "<a href='" . $new_value . "' aria-label='" . $networks[$network]['name'] . "' target='" . $target . "' class='ns-button ns-follow-button " . $network . $button_class . "' rel='nofollow noopener noreferrer'>";
						$output.= "<span class='ns-button-wrapper ns-button-block'>";
							$output.= "<span class='ns-button-icon ns-button-block'>";
								$output.= $networks[$network]['icon'];
							$output.= "</span>";
						$output.= "</span>";
					$output.= "</a>";
				}
			}
		$output.= "</div>";
	$output.= "</div>";

	return $output;
}

function novashare_print_follow_button($attributes) {

	if(!empty($attributes['link'])) {

		//prefix specific network values
		if(is_email($attributes['link'])) {
			$new_value = 'mailto:' . $attributes['link'];
		}
		elseif($attributes['network'] == 'phone') {
			$new_value = 'tel:' . $attributes['link'];
		}
		elseif($attributes['network'] == 'messenger') {
			$new_value = 'https://m.me/' . $attributes['link'];
		}
		elseif($attributes['network'] == 'skype') {
			$new_value = 'skype:' . $attributes['link'];
		}
		elseif($attributes['network'] == 'line') {
			$new_value = 'https://line.me/R/home/public/profile?id=' . $attributes['link'];
		}
		else {
			$new_value = esc_url($attributes['link']);
		}

		$new_value = apply_filters('novashare_follow_network_link', $new_value, $attributes['network']);

		$networks = novashare_networks('follow');

		//print network button
		$output = "<a href='" . $new_value . "' aria-label='" . $networks[$attributes['network']]['name'] . "'" . (!empty($attributes['newTab']) ? ' target="_blank"' : '') . " class='ns-button ns-follow-button " . $attributes['network'] . (!empty($attributes['buttonShape']) ? ' ns-' . $attributes['buttonShape'] : '') . "' rel='nofollow noopener noreferrer'>";
			$output.= "<span class='ns-button-wrapper ns-button-block'>";
				$output.= "<span class='ns-button-icon ns-button-block'>";
					$output.= $networks[$attributes['network']]['icon'];
				$output.= "</span>";
			$output.= "</span>";
		$output.= "</a>";

		return $output;
	}
}

//follow buttons shortcode
function novashare_follow_shortcode($params) {

	//stylesheet is not enqueued
	if(!wp_style_is('novashare-css') && !novashare_is_amp()) {
		return novashare_global_styles_message();
	}

	//no networks given
	if(empty($params['networks'])) {
		return;
	}

	//sort networks
	$sorted_networks = array();
	$network_strings = explode(',', $params['networks']);
	foreach($network_strings as $network_string) {
		$temp = explode('=', $network_string);
	  	$sorted_networks[$temp[0]] = !empty($temp[1]) ? $temp[1] : '';
	}

	//start instance
	$instance = array();

	//id
	global $novashare_shortcode_count;
	if(empty($novashare_shortcode_count)) {
		$novashare_shortcode_count = 1;
	}
	$instance['id'] = 'ns-shortcode-' . $novashare_shortcode_count;
	$novashare_shortcode_count++;

	//parameters
	$instance['location'] = 'shortcode';
	$instance['shape'] = !empty($params['button_shape']) ? $params['button_shape'] : '';
	$instance['button_color'] = !empty($params['button_color']) ? $params['button_color'] : '';
	$instance['button_hover_color'] = !empty($params['button_hover_color']) ? $params['button_hover_color'] : '';
	$instance['alignment'] = !empty($params['button_alignment']) ? $params['button_alignment'] : '';
	$instance['button_size'] = !empty($params['button_size']) ? intval($params['button_size']) : '';
	$instance['icon_color'] = !empty($params['icon_color']) ? $params['icon_color'] : '';
	$instance['icon_hover_color'] = !empty($params['icon_hover_color']) ? $params['icon_hover_color'] : '';
	$instance['new_tab'] = isset($params['new_tab']) ? filter_var($params['new_tab'], FILTER_VALIDATE_BOOLEAN) : '';
	$instance['selected_networks'] = $sorted_networks;

	//print follow buttons
	return novashare_print_follow_buttons($instance);
}
add_shortcode('novashare_follow', 'novashare_follow_shortcode');

//follow buttons shortcode
function novashare_follow_block($attributes, $content) {

	//stylesheet is not enqueued
	if(!wp_style_is('novashare-css') && !novashare_is_amp()) {
		return novashare_global_styles_message();
	}
	
	//start instance
	$instance = array();
	$instance['id'] = 'ns-block-' . $attributes['id'];
	$instance['button_size'] = $attributes['buttonSize'] ?? '';
	$instance['button_margin'] = $attributes['buttonMargin'] ?? '';
	$instance['button_color'] = $attributes['buttonColor'] ?? '';
	$instance['button_hover_color'] = $attributes['buttonHoverColor'] ?? '';
	$instance['icon_color'] = $attributes['iconColor'] ?? '';
	$instance['icon_hover_color'] = $attributes['iconHoverColor'] ?? '';

	//print follow buttons
	return novashare_follow_inline_styles($instance) . $content;
}

//return follow network block output
function novashare_follow_network_block($attributes) {
	return novashare_print_follow_button($attributes);
}

//sanitize hex value
function novashare_sanitize_hex_value($value) {
	if($value === '') {
		return '';
	}

	//make sure we have a valid hex value
	if(preg_match('|^#([A-Fa-f0-9]{3}){1,2}$|', $value)) {
		return $value;
	}

	return null;
}

//return inline styles for specific follow button instance
function novashare_follow_inline_styles($instance) {

	//prep saved values
	$button_size = !empty($instance['button_size']) && (int) $instance['button_size'] !== 50 ? (int) $instance['button_size'] : '';
	$button_margin = isset($instance['button_margin']) && (!empty($instance['button_margin']) || $instance['button_margin'] === 0) ? (int) $instance['button_margin'] : 10;
	$alignment = !empty($instance['alignment']) ? ' ns-align-' . $instance['alignment'] : '';

	//start inline styles
	$inline_styles = "";

	//inline icon size
	if(!empty($button_size)) {
		$inline_styles.= "
			body ." . $instance['id'] . " .ns-button, body ." . $instance['id'] . " .ns-button-icon {
				height: " . $button_size . "px;
				width: " . $button_size . "px;
				min-width: " . $button_size . "px;
			}
		";
	}

	//inline button margin
	if($button_margin != 10) {
		if(!empty($instance['alignment'])) {
			if($instance['alignment'] == 'right') {
				$inline_styles.= "body ." . $instance['id'] . " .ns-button {
					margin: 0px 0px " . $button_margin . "px " . $button_margin . "px;
				}";
			}
			elseif($instance['alignment'] == 'center') {
				 $inline_styles.= "body ." . $instance['id'] . " .ns-button {
					margin: 0px " . ($button_margin/2) . "px " . $button_margin . "px " . ($button_margin/2) . "px;
				}";
			}
		}
		else {
			$inline_styles.= "body ." . $instance['id'] . " .ns-button {
				margin: 0px " . $button_margin . "px " . $button_margin . "px 0px;
			}";
		}
	}

	//inline primary colors
	if(!empty($instance['button_color']) || !empty($instance['button_color'])) {
		$inline_styles.= "
			body ." . $instance['id'] . " .ns-button .ns-button-icon, body ." . $instance['id'] . " .ns-button .ns-button-icon:focus, body ." . $instance['id'] . " .ns-button .ns-button-icon:active, body ." . $instance['id'] . " .ns-button .ns-button-icon:visited {
				" . (!empty($instance['button_color']) ? "background: " . $instance['button_color'] . ";" : "") . "
				" . (!empty($instance['icon_color']) ? "color: " . $instance['icon_color'] . ";" : "") . "
			}
		";
	}

	//inline hover colors
	if(!empty($instance['button_hover_color']) || !empty($instance['icon_hover_color'])) {
		$inline_styles.= "
			body ." . $instance['id'] . " .ns-button:hover .ns-button-icon {
				" . (!empty($instance['button_hover_color']) ? "background: " . $instance['button_hover_color'] . "; box-shadow: none;" : "") . "
				" . (!empty($instance['icon_hover_color']) ? "color: " . $instance['icon_hover_color'] . ";" : "") . "
			}
		";
	}

	//print inline styles
	$output = "<style>" . $inline_styles . "</style>";

	return $output;
}

//return message to enable global styles
function novashare_global_styles_message() {

	$output = '<div style="padding: 10px 12px; margin-bottom: 10px; border: 1px solid #e2e4e7; border-left: 4px solid #4D4595; font-size: 13px;">Enable <a href="https://novashare.io/docs/shortcode/#global-styles" target="_blank" rel="nofollow noopener noreferrer" style="color: #4D4595;">Global Styles</a> in Novashare to display this block.</div>';

	return $output;
}