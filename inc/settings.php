<?php
//register settings + options
function novashare_settings() {

    $novashare = get_option('novashare');

    //apply defaults if nothing is set
	if(get_option('novashare') == false) {	
		add_option('novashare', apply_filters('novashare_default_options', novashare_default_options()));
	}

    //inline content section
    add_settings_section('inline', __('Inline Content', 'novashare'), '__return_false', 'novashare');

    //enabled
    add_settings_field(
        'enabled', 
        novashare_title(__('Enable Inline Content', 'novashare'), 'inline-enabled', 'https://novashare.io/docs/inline-content-share-buttons/'),
        'novashare_print_input', 
        'novashare', 
        'inline', 
        array(
            'section' => 'inline',
            'id'      => 'enabled',
            'tooltip' => __('Display social share buttons inline above or below your content. Default: Disabled', 'novashare')
        )
    );

    //social networks
    add_settings_field(
        'social_networks', 
        novashare_title(__('Social Networks', 'novashare'), false, 'https://novashare.io/docs/social-networks/'),
        'novashare_print_social_networks', 
        'novashare', 
        'inline', 
        array(
            'section' => 'inline',
            'id'      => 'social_networks',
            'tooltip' => __('Choose which inline social share buttons to display. Click on a square to enable or disable that specific network. Drag and drop squares to arrange the order in which they will display. Default: Twitter, Facebook, LinkedIn', 'novashare')
        )
    );

    //inline display section
    add_settings_section('inline_display', __('Display', 'novashare'), '__return_false', 'novashare');

    //post types
    add_settings_field(
        'post_types',
        novashare_title(__('Post Types', 'novashare'), false, 'https://novashare.io/docs/post-types/'),
        'novashare_print_post_types',
        'novashare',
        'inline_display',
        array(
            'section' => 'inline',
            'id'      => 'post_types',
            'tooltip' => __('Choose which post types display inline social share buttons. Default: Posts', 'novashare')
        )
    );

    //show in feeds
    add_settings_field(
        'feeds', 
        novashare_title(__('Show in Feeds', 'novashare'), 'inline-feeds', 'https://novashare.io/docs/post-feeds/'),
        'novashare_print_input', 
        'novashare', 
        'inline_display', 
        array(
            'section' => 'inline',
            'id'      => 'feeds',
            'tooltip' => __('Show your inline social share buttons in post feeds. Default: Disabled', 'novashare')
        )
    );

    //position
    add_settings_field(
        'position', 
        novashare_title(__('Button Position', 'novashare'), 'inline-position', 'https://novashare.io/docs/inline-content-share-buttons-position/'),
        'novashare_print_input', 
        'novashare', 
        'inline_display', 
        array(
            'section' => 'inline',
            'id'      => 'position',
            'input'   => 'select',
            'options' => array(
                ''        => __("Above Content", 'novashare'),
                'below'   => __("Below Content", 'novashare'),
                'both'    => __("Above and Below Content", 'novashare'),
                'neither' => __("Don't Add to Content (shortcode)", 'novashare')
            ),
            'tooltip' => __('Choose where to display your inline social share buttons. Default: Above Content', 'novashare')
        )
    );

    //mobile breakpoint
    add_settings_field(
        'breakpoint', 
        novashare_title(__('Mobile Breakpoint', 'novashare'), 'inline-breakpoint', 'https://novashare.io/docs/breakpoints/#mobile'), 
        'novashare_print_input', 
        'novashare', 
        'inline_display', 
        array(
            'section'     => 'inline',
            'id'          => 'breakpoint',
            'input'       => 'text',
            'validate'    => '[0-9.pPxX]',
            'maxlength'   => 25,
            'placeholder' => '1200px',
            'tooltip'     => __('Set the width in pixels (px) where you want the inline mobile breakpoint to occur. Default: 1200px', 'novashare')
        )
    );

    //hide above breakpoint
    add_settings_field(
        'hide_above_breakpoint', 
        novashare_title(__('Hide Above Breakpoint', 'novashare'), 'inline-hide_above_breakpoint', 'https://novashare.io/docs/breakpoints/#hide-above'), 
        'novashare_print_input', 
        'novashare', 
        'inline_display', 
        array(
            'section' => 'inline',
            'id'      => 'hide_above_breakpoint',
            'tooltip' => __('Hide your inline social share buttons when the browser’s viewport is wider than your mobile breakpoint. Default: Disabled', 'novashare')
        )
    );

    //hide below breakpoint
    add_settings_field(
        'hide_below_breakpoint', 
        novashare_title(__('Hide Below Breakpoint', 'novashare'), 'inline-hide_below_breakpoint', 'https://novashare.io/docs/breakpoints/#hide-below'),
        'novashare_print_input', 
        'novashare', 
        'inline_display', 
        array(
            'section' => 'inline',
            'id'      => 'hide_below_breakpoint',
            'tooltip' => __('Hide your inline social share buttons when the browser’s viewport is narrower than your mobile breakpoint. Default: Disabled', 'novashare')
        )
    );

    //inline design section
    add_settings_section('inline_design', __('Design', 'novashare'), '__return_false', 'novashare');

    //button style
    add_settings_field(
        'style', 
        novashare_title(__('Button Style', 'novashare'), 'inline-style', 'https://novashare.io/docs/button-appearance/#style'),
        'novashare_print_input', 
        'novashare', 
        'inline_design', 
        array(
            'section' => 'inline',
            'id'      => 'style',
            'input'   => 'select',
            'options' => array(
                '' => __("Solid", 'novashare'),
                'inverse' => __("Inverse", 'novashare'),
                'solid-inverse-border' => __("Bordered Label", 'novashare'),
                'full-inverse-border' => __("Bordered Button", 'novashare'),
                'solid-inverse' => __("Minimal Label", 'novashare'),
                'full-inverse' => __("Minimal", 'novashare')
            ),
            'tooltip' => __('Change the style of your inline social share buttons. Default: Solid', 'novashare')
        )
    );

    //button layout
    add_settings_field(
        'layout', 
        novashare_title(__('Button Layout', 'novashare'), 'inline-layout', 'https://novashare.io/docs/button-appearance/#layout'),
        'novashare_print_input', 
        'novashare', 
        'inline_design', 
        array(
            'section' => 'inline',
            'id'      => 'layout',
            'input'   => 'select',
            'options' => array(
                '' => __("Auto Width", 'novashare'),
                '1-col' => __("1 Column", 'novashare'),
                '2-col' => __("2 Columns", 'novashare'),
                '3-col' => __("3 Columns", 'novashare'),
                '4-col' => __("4 Columns", 'novashare'),
                '5-col' => __("5 Columns", 'novashare'),
                '6-col' => __("6 Columns", 'novashare')
            ),
            'tooltip' => __('Change the layout of your inline social share buttons.', 'novashare')
        )
    );

    //alignment
    add_settings_field(
        'alignment', 
        novashare_title(__('Button Alignment', 'novashare'), 'inline-alignment', 'https://novashare.io/docs/inline-content-share-buttons-alignment/'),
        'novashare_print_input', 
        'novashare', 
        'inline_design', 
        array(
            'section' => 'inline',
            'id'      => 'alignment',
            'input'   => 'select',
            'options' => array(
                ''        => __("Left", 'novashare'),
                'right'   => __("Right", 'novashare'),
                'center'  => __("Center", 'novashare')
            ),
            'class'   => (!empty($novashare['inline']['layout']) ? ' hidden' : ''),
            'tooltip' => __('Choose how to align your inline social share buttons. Default: Left', 'novashare')
        )
    );

    //button size
    add_settings_field(
        'size', 
        novashare_title(__('Button Size', 'novashare'), 'inline-size', 'https://novashare.io/docs/button-appearance/#size'),
        'novashare_print_input', 
        'novashare', 
        'inline_design', 
        array(
            'section' => 'inline',
            'id'      => 'size',
            'input'   => 'select',
            'options' => array(
                'small' => __("Small", 'novashare'),
                ''      => __("Medium", 'novashare'),
                'large' => __("Large", 'novashare')
            ),
            'tooltip' => __('Change the size of your inline social share buttons. Default: Medium', 'novashare')
        )
    );

    //button shape
    add_settings_field(
        'shape', 
        novashare_title(__('Button Shape', 'novashare'), 'inline-shape', 'https://novashare.io/docs/button-appearance/#shape'),
        'novashare_print_input', 
        'novashare', 
        'inline_design', 
        array(
            'section' => 'inline',
            'id'      => 'shape',
            'input'   => 'select',
            'options' => array(
                ''         => __("Squared", 'novashare'),
                'rounded'  => __("Rounded", 'novashare'),
                'circular' => __("Circular", 'novashare')
            ),
            'tooltip' => __('Change the shape of your inline social share buttons. Default: Squared', 'novashare')
        )
    );

    //button color
    add_settings_field(
        'button_color',
        novashare_title(__('Button Color', 'novashare'), false, 'https://novashare.io/docs/button-appearance/#color'),
        'novashare_print_input',
        'novashare',
        'inline_design',
        array(
            'section' => 'inline',
            'id'      => 'button_color',
            'input'   => 'color',
            'tooltip' => __('Change the background color of your inline social share buttons.', 'novashare')
        )
    );

    //button hover color
    add_settings_field(
        'button_hover_color',
        novashare_title(__('Button Hover Color', 'novashare'), false, 'https://novashare.io/docs/button-appearance/#hover-color'),
        'novashare_print_input',
        'novashare',
        'inline_design',
        array(
            'section' => 'inline',
            'id'      => 'button_hover_color',
            'input'   => 'color',
            'tooltip' => __('Change the hover background color of your inline social share buttons.', 'novashare')
        )
    );

    //icon color
    add_settings_field(
        'icon_color',
        novashare_title(__('Icon Color', 'novashare'), false, 'https://novashare.io/docs/button-appearance/#icon-color'),
        'novashare_print_input',
        'novashare',
        'inline_design',
        array(
            'section' => 'inline',
            'id'      => 'icon_color',
            'input'   => 'color',
            'tooltip' => __('Change the icon color of your inline social share buttons.', 'novashare'),
            'class'   => 'inline-inverse_hover' . (!empty($novashare['inline']['inverse_hover']) ? ' hidden' : '')
        )
    );

    //icon hover color
    add_settings_field(
        'icon_hover_color',
        novashare_title(__('Icon Hover Color', 'novashare'), false, 'https://novashare.io/docs/button-appearance/#icon-hover-color'),
        'novashare_print_input',
        'novashare',
        'inline_design',
        array(
            'section' => 'inline',
            'id'      => 'icon_hover_color',
            'input'   => 'color',
            'tooltip' => __('Change the hover icon color of your inline social share buttons.', 'novashare'),
            'class'   => 'inline-inverse_hover' . (!empty($novashare['inline']['inverse_hover']) ? ' hidden' : '')
        )
    );

    //inverse hover
    add_settings_field(
        'inverse_hover', 
        novashare_title(__('Inverse on Hover', 'novashare'), 'inline-inverse_hover', 'https://novashare.io/docs/button-appearance/#inverse-hover'), 
        'novashare_print_input', 
        'novashare', 
        'inline_design', 
        array(
            'section'     => 'inline',
            'id'          => 'inverse_hover',
            'tooltip'     => __('Swap to an inverse button style on hover. This function will add additional inline JavaScript to any page where Novashare buttons are present.', 'novashare'),
            'class'       => 'novashare-input-controller'
        )
    );

    //button margin
    add_settings_field(
        'button_margin', 
        novashare_title(__('Button Margin', 'novashare'), 'inline-button_margin', 'https://novashare.io/docs/button-appearance/#button-margin'), 
        'novashare_print_input', 
        'novashare', 
        'inline_design', 
        array(
            'section'     => 'inline',
            'id'          => 'button_margin',
            'input'       => 'text',
            'placeholder' => '10px',
            'tooltip'     => __('Change the margin in pixels (px) around your inline social social share buttons.', 'novashare')
        )
    );

    //show labels
    add_settings_field(
        'labels', 
        novashare_title(__('Show Labels', 'novashare'), 'inline-labels', 'https://novashare.io/docs/labels/#show'), 
        'novashare_print_input', 
        'novashare', 
        'inline_design', 
        array(
            'section' => 'inline',
            'id'      => 'labels',
            'class'   => 'novashare-input-controller',
            'tooltip' => __('Display network labels on your inline social share buttons. Default: Disabled', 'novashare')
        )
    );

    //hide labels on mobile
    add_settings_field(
        'hide_labels_mobile', 
        novashare_title(__('Hide Labels on Mobile', 'novashare'), 'inline-hide_labels_mobile', 'https://novashare.io/docs/labels/#hide'), 
        'novashare_print_input', 
        'novashare', 
        'inline_design', 
        array(
            'section' => 'inline',
            'id'      => 'hide_labels_mobile',
            'class'   => 'inline-labels' . (empty($novashare['inline']['labels']) ? ' hidden' : ''),
            'tooltip' => __('Hide network labels on your inline social share buttons on mobile. Default: Disabled', 'novashare')
        )
    );

    //inline share counts section
    add_settings_section('inline_share_counts', __('Share Counts', 'novashare'), '__return_false', 'novashare');

    //show total share count
    add_settings_field(
        'total_share_count', 
        novashare_title(__('Total Share Count', 'novashare'), 'inline-total_share_count', 'https://novashare.io/docs/enable-share-counts/#total'), 
        'novashare_print_input', 
        'novashare', 
        'inline_share_counts', 
        array(
            'section' => 'inline',
            'id'      => 'total_share_count',
            'class'   => 'novashare-input-controller',
            'tooltip' => __('Display the total share count with your inline social share buttons. Default: Enabled', 'novashare')
        )
    );

    //total share count position
    add_settings_field(
        'total_share_count_position', 
        novashare_title(__('Total Share Count Position', 'novashare'), 'inline-total_share_count_position', 'https://novashare.io/docs/enable-share-counts/#position'),
        'novashare_print_input', 
        'novashare', 
        'inline_share_counts', 
        array(
            'section' => 'inline',
            'id'      => 'total_share_count_position',
            'input'   => 'select',
            'options' => array(
                'before' => __("Before", 'novashare'),
                ''       => __("After", 'novashare')
            ),
            'class'   => 'inline-total_share_count' . (empty($novashare['inline']['total_share_count']) ? ' hidden' : ''),
            'tooltip' => __('Change the position of your inline total share count display. Default: After', 'novashare')
        )
    );

    //total share count color
    add_settings_field(
        'total_share_count_color',
        novashare_title(__('Total Share Count Color', 'novashare'), false, 'https://novashare.io/docs/enable-share-counts/#color'),
        'novashare_print_input',
        'novashare',
        'inline_share_counts',
        array(
            'section' => 'inline',
            'id'      => 'total_share_count_color',
            'input'   => 'color',
            'class'   => 'inline-total_share_count' . (empty($novashare['inline']['total_share_count']) ? ' hidden' : ''),
            'tooltip' => __('Change the text color of your inline total share count display.', 'novashare')
        )
    );

    //show network share counts
    add_settings_field(
        'network_share_counts', 
        novashare_title(__('Network Share Counts', 'novashare'), 'inline-network_share_counts', 'https://novashare.io/docs/enable-share-counts/#network'), 
        'novashare_print_input', 
        'novashare', 
        'inline_share_counts', 
        array(
            'section' => 'inline',
            'id'      => 'network_share_counts',
            'tooltip' => __('Display individual network share counts when hovering over each button. Default: Enabled', 'novashare')
        )
    );

    //inline ctall to action section
    add_settings_section('inline_cta', __('Call to Action', 'novashare'), '__return_false', 'novashare');

    //call to action text
    add_settings_field(
        'cta_text', 
        novashare_title(__('Text', 'novashare'), 'inline-cta_text', 'https://novashare.io/docs/call-to-action/#text'), 
        'novashare_print_input', 
        'novashare', 
        'inline_cta', 
        array(
            'section' => 'inline',
            'id'      => 'cta_text',
            'input'   => 'text',
            'placeholder' => 'Share with your friends!',
            'tooltip' => __('Set the call to action text displayed above your inline social share buttons.', 'novashare')
        )
    );

    //call to action font size
    add_settings_field(
        'cta_font_size', 
        novashare_title(__('Font Size', 'novashare'), 'inline-cta_font_size', 'https://novashare.io/docs/call-to-action/#font-size'), 
        'novashare_print_input', 
        'novashare', 
        'inline_cta', 
        array(
            'section' => 'inline',
            'id'      => 'cta_font_size',
            'input'   => 'text',
            'placeholder' => '20px',
            'tooltip' => __('Change the font size of your call to action text.', 'novashare')
        )
    );

    //call to action text color
    add_settings_field(
        'cta_text_color', 
        novashare_title(__('Font Color', 'novashare'), 'inline-cta_text_color', 'https://novashare.io/docs/call-to-action/#font-color'), 
        'novashare_print_input', 
        'novashare', 
        'inline_cta', 
        array(
            'section' => 'inline',
            'id'      => 'cta_text_color',
            'input'   => 'color',
            'tooltip' => __('Change the color of your call to action text.', 'novashare')
        )
    );

    //floating bar section
    add_settings_section('floating', __('Floating Bar', 'novashare'), '__return_false', 'novashare');

    //enabled
    add_settings_field(
        'enabled', 
        novashare_title(__('Enable Floating Bar', 'novashare'), 'floating-enabled', 'https://novashare.io/docs/floating-bar-share-buttons/'),
        'novashare_print_input', 
        'novashare', 
        'floating', 
        array(
            'section' => 'floating',
            'id'      => 'enabled',
            'tooltip' => __('Display social share buttons on a floating bar. Default: Disabled', 'novashare')
        )
    );

    //social networks
    add_settings_field(
        'social_networks', 
        novashare_title(__('Social Networks', 'novashare'), false, 'https://novashare.io/docs/social-networks/'), 
        'novashare_print_social_networks', 
        'novashare', 
        'floating', 
        array(
            'section' => 'floating',
            'id'      => 'social_networks',
            'tooltip' => __('Choose which floating social share buttons to display. Click on a square to enable or disable that specific network. Drag and drop squares to arrange the order in which they will display. Default: Twitter, Facebook, LinkedIn', 'novashare')
        )
    );

    //floating display section
    add_settings_section('floating_display', __('Display', 'novashare'), '__return_false', 'novashare');

    //post types
    add_settings_field(
        'post_types',
        novashare_title(__('Post Types', 'novashare'), false, 'https://novashare.io/docs/post-types/'),
        'novashare_print_post_types',
        'novashare',
        'floating_display',
        array(
            'section' => 'floating',
            'id'      => 'post_types',
            'tooltip' => __('Choose which post types display floating social share buttons. Default: Posts', 'novashare')
        )
    );

    //posts page
    add_settings_field(
        'posts_page', 
        novashare_title(__('Show on Posts Page', 'novashare'), 'floating-posts_page', 'https://novashare.io/docs/post-types/#posts-page'),
        'novashare_print_input', 
        'novashare', 
        'floating_display', 
        array(
            'section' => 'floating',
            'id'      => 'posts_page',
            'tooltip' => __('Show your floating social share buttons on your posts page. Default: Disabled', 'novashare')
        )
    );

    //archives
    add_settings_field(
        'archives', 
        novashare_title(__('Show on Archives', 'novashare'), 'floating-archives', 'https://novashare.io/docs/post-types/#archives'),
        'novashare_print_input', 
        'novashare', 
        'floating_display', 
        array(
            'section' => 'floating',
            'id'      => 'archives',
            'tooltip' => __('Show your floating social share buttons on archive pages. Share counts are not supported on archives. Default: Disabled', 'novashare')
        )
    );

    //homepage
    if(get_option('show_on_front') == 'page' && get_option('page_on_front')) {
        add_settings_field(
            'home_page', 
            novashare_title(__('Show on Homepage', 'novashare'), 'floating-home_page', 'https://novashare.io/docs/post-types/#homepage'),
            'novashare_print_input',
            'novashare', 
            'floating_display', 
            array(
                'section' => 'floating',
                'id'      => 'home_page',
                'tooltip' => __('Show your floating social share buttons on your homepage. Default: Disabled', 'novashare')
            )
        );
    }

    //position
    add_settings_field(
        'position', 
        novashare_title(__('Button Position', 'novashare'), 'floating-position', 'https://novashare.io/docs/floating-bar-share-buttons-position/'),
        'novashare_print_input', 
        'novashare', 
        'floating_display', 
        array(
            'section' => 'floating',
            'id'      => 'position',
            'input'   => 'select',
            'options' => array(
                ''     => __("Default", 'novashare'),
                'none' => __("Don't Add to Content (shortcode)", 'novashare')
            ),
            'tooltip' => __('Choose where to display your floating social share buttons.', 'novashare')
        )
    );

    //alignment
    add_settings_field(
        'alignment', 
        novashare_title(__('Button Alignment', 'novashare'), 'floating-alignment', 'https://novashare.io/docs/floating-content-share-buttons-alignment/'),
        'novashare_print_input', 
        'novashare', 
        'floating_display', 
        array(
            'section' => 'floating',
            'id'      => 'alignment',
            'input'   => 'select',
            'options' => array(
                ''        => __("Left", 'novashare'),
                'right'   => __("Right", 'novashare')
            ),
            'tooltip' => __('Choose how to align your floating social share buttons. Default: Left', 'novashare')
        )
    );

    //mobile breakpoint
    add_settings_field(
        'breakpoint', 
        novashare_title(__('Mobile Breakpoint', 'novashare'), 'floating-breakpoint', 'https://novashare.io/docs/breakpoints/#mobile'), 
        'novashare_print_input', 
        'novashare',
        'floating_display', 
        array(
            'section'     => 'floating',
            'id'          => 'breakpoint',
            'input'       => 'text',
            'validate'    => '[0-9.pPxX]',
            'maxlength'   => 25,
            'placeholder' => '1200px',
            'tooltip'     => __('Set the width in pixels (px) where you want the floating mobile breakpoint to occur. Default: 1200px', 'novashare')
        )
    );

    //hide above breakpoint
    add_settings_field(
        'hide_above_breakpoint', 
        novashare_title(__('Hide Above Breakpoint', 'novashare'), 'floating-hide_above_breakpoint', 'https://novashare.io/docs/breakpoints/#hide-above'), 
        'novashare_print_input', 
        'novashare', 
        'floating_display', 
        array(
            'section' => 'floating',
            'id'      => 'hide_above_breakpoint',
            'tooltip' => __('Hide your floating social share buttons when the browser’s viewport is wider than your mobile breakpoint. Default: Disabled', 'novashare')
        )
    );

    //hide below breakpoint
    add_settings_field(
        'hide_below_breakpoint', 
        novashare_title(__('Hide Below Breakpoint', 'novashare'), 'floating-hide_below_breakpoint', 'https://novashare.io/docs/breakpoints/#hide-below'), 
        'novashare_print_input', 
        'novashare', 
        'floating_display', 
        array(
            'section' => 'floating',
            'id'      => 'hide_below_breakpoint',
            'class'   => 'novashare-input-controller',
            'tooltip' => __('Hide your floating social share buttons when the browser’s viewport is narrower than your mobile breakpoint. Default: Disabled', 'novashare')
        )
    );

    //top offset
    add_settings_field(
        'top_offset', 
        novashare_title(__('Top Offset', 'novashare'), 'floating-top_offset', 'https://novashare.io/docs/floating-offsets/#top'), 
        'novashare_print_input', 
        'novashare',
        'floating_display', 
        array(
            'section'     => 'floating',
            'id'          => 'top_offset',
            'input'       => 'text',
            'validate'    => '[0-9.pPxX%]',
            'maxlength'   => 25,
            'placeholder' => '25%',
            'tooltip'     => __('Change the offset in pixels (px) or percentage (%) that your floating social share button container will be from the top of the screen.', 'novashare')
        )
    );

    //edge offset
    add_settings_field(
        'container_offset', 
        novashare_title(__('Edge Offset', 'novashare'), 'floating-container_offset', 'https://novashare.io/docs/floating-offsets/#edge'), 
        'novashare_print_input', 
        'novashare', 
        'floating_display', 
        array(
            'section'     => 'floating',
            'id'          => 'container_offset',
            'input'       => 'text',
            'placeholder' => '5px',
            'tooltip'     => __('Change the offset in pixels (px) that your floating social share button container will be from the edge of the screen.', 'novashare')
        )
    );

    //show on scroll
    add_settings_field(
        'show_on_scroll', 
        novashare_title(__('Show on Scroll', 'novashare'), 'floating-show_on_scroll', 'https://novashare.io/docs/show-on-scroll/'), 
        'novashare_print_input', 
        'novashare', 
        'floating_display', 
        array(
            'section' => 'floating',
            'id'      => 'show_on_scroll',
            'class'   => 'novashare-input-controller',
            'tooltip' => __('Wait to show your floating social share buttons until the user has scrolled past a specific point on the page.', 'novashare')
        )
    );

    //show on scroll location
    add_settings_field(
        'show_on_scroll_location', 
        novashare_title(__('Show on Scroll Location', 'novashare'), 'floating-show_on_scroll_location', 'https://novashare.io/docs/show-on-scroll/#location'), 
        'novashare_print_input', 
        'novashare', 
        'floating_display', 
        array(
            'section' => 'floating',
            'id'      => 'show_on_scroll_location',
            'input'   => 'select',
            'options' => array(
                ''        => 'Both',
                'desktop' => __('Desktop Only', 'novashare'),
                'mobile'  => __('Mobile Only', 'novashare')
            ),
            'class'   => 'floating-show_on_scroll' . (empty($novashare['floating']['show_on_scroll']) ? ' hidden' : ''),
            'tooltip' => __('Choose what devices are set to only show on scroll.', 'novashare')
        )
    );

    //scroll threshold
    add_settings_field(
        'scroll_threshold', 
        novashare_title(__('Scroll Threshold', 'novashare'), 'floating-scroll_threshold', 'https://novashare.io/docs/show-on-scroll/#threshold'), 
        'novashare_print_input', 
        'novashare', 
        'floating_display', 
        array(
            'section'     => 'floating',
            'id'          => 'scroll_threshold',
            'input'       => 'text',
            'placeholder' => '0px',
            'class'       => 'floating-show_on_scroll' . (empty($novashare['floating']['show_on_scroll']) ? ' hidden' : ''),
            'tooltip'     => __('Change the point that show on scroll is triggered. px or %', 'novashare')
        )
    );

    //floating design section
    add_settings_section('floating_design', __('Design', 'novashare'), '__return_false', 'novashare');

    //button style
    add_settings_field(
        'style', 
        novashare_title(__('Button Style', 'novashare'), 'floating-style', 'https://novashare.io/docs/button-appearance/#style'),
        'novashare_print_input', 
        'novashare', 
        'floating_design', 
        array(
            'section' => 'floating',
            'id'      => 'style',
            'input'   => 'select',
            'options' => array(
                '' => __("Solid", 'novashare'),
                'inverse' => __("Inverse", 'novashare')
            ),
            'tooltip' => __('Change the style of your floating social share buttons. Default: Solid', 'novashare')
        )
    );

    //button size
    add_settings_field(
        'size', 
        novashare_title(__('Button Size', 'novashare'), 'floating-size', 'https://novashare.io/docs/button-appearance/#size'), 
        'novashare_print_input', 
        'novashare', 
        'floating_design', 
        array(
            'section' => 'floating',
            'id'      => 'size',
            'input'   => 'select',
            'options' => array(
                'small' => __("Small", 'novashare'),
                ''      => __("Medium", 'novashare'),
                'large' => __("Large", 'novashare')
            ),
            'tooltip' => __('Change the size of your floating social share buttons. Default: Medium', 'novashare')
        )
    );

    //button shape
    add_settings_field(
        'shape', 
        novashare_title(__('Button Shape', 'novashare'), 'floating-shape', 'https://novashare.io/docs/button-appearance/#shape'), 
        'novashare_print_input', 
        'novashare', 
        'floating_design', 
        array(
            'section' => 'floating',
            'id'      => 'shape',
            'input'   => 'select',
            'options' => array(
                ''         => __("Squared", 'novashare'),
                'rounded'  => __("Rounded", 'novashare'),
                'circular' => __("Circular", 'novashare')
            ),
            'tooltip' => __('Change the shape of your floating social share buttons. Default: Squared', 'novashare')
        )
    );

    //button color
    add_settings_field(
        'button_color',
        novashare_title(__('Button Color', 'novashare'), false, 'https://novashare.io/docs/button-appearance/#color'),
        'novashare_print_input',
        'novashare',
        'floating_design',
        array(
            'section' => 'floating',
            'id'      => 'button_color',
            'input'   => 'color',
            'tooltip' => __('Change the background color of your floating social share buttons.', 'novashare')
        )
    );

    //button hover color
    add_settings_field(
        'button_hover_color',
        novashare_title(__('Button Hover Color', 'novashare'), false, 'https://novashare.io/docs/button-appearance/#hover-color'),
        'novashare_print_input',
        'novashare',
        'floating_design',
        array(
            'section' => 'floating',
            'id'      => 'button_hover_color',
            'input'   => 'color',
            'tooltip' => __('Change the hover background color of your floating social share buttons.', 'novashare')
        )
    );

    //icon color
    add_settings_field(
        'icon_color',
        novashare_title(__('Icon Color', 'novashare'), false, 'https://novashare.io/docs/button-appearance/#icon-color'),
        'novashare_print_input',
        'novashare',
        'floating_design',
        array(
            'section' => 'floating',
            'id'      => 'icon_color',
            'input'   => 'color',
            'tooltip' => __('Change the icon color of your floating social share buttons.', 'novashare'),
            'class'   => 'floating-inverse_hover' . (!empty($novashare['floating']['inverse_hover']) ? ' hidden' : '')
        )
    );

    //icon hover color
    add_settings_field(
        'icon_hover_color',
        novashare_title(__('Icon Hover Color', 'novashare'), false, 'https://novashare.io/docs/button-appearance/#icon-hover-color'),
        'novashare_print_input',
        'novashare',
        'floating_design',
        array(
            'section' => 'floating',
            'id'      => 'icon_hover_color',
            'input'   => 'color',
            'tooltip' => __('Change the icon color of your floating social share buttons.', 'novashare'),
            'class'   => 'floating-inverse_hover' . (!empty($novashare['floating']['inverse_hover']) ? ' hidden' : '')
        )
    );

    //inverse hover
    add_settings_field(
        'inverse_hover', 
        novashare_title(__('Inverse on Hover', 'novashare'), 'floating-inverse_hover', 'https://novashare.io/docs/button-appearance/#inverse-hover'), 
        'novashare_print_input', 
        'novashare', 
        'floating_design', 
        array(
            'section'     => 'floating',
            'id'          => 'inverse_hover',
            'tooltip'     => __('Swap to an inverse button style on hover. This function will add additional inline JavaScript to any page where Novashare buttons are present.', 'novashare'),
            'class'       => 'novashare-input-controller'
        )
    );

    //button margin
    add_settings_field(
        'button_margin', 
        novashare_title(__('Button Margin', 'novashare'), 'floating-button_margin', 'https://novashare.io/docs/button-appearance/#button-margin'), 
        'novashare_print_input', 
        'novashare', 
        'floating_design', 
        array(
            'section'     => 'floating',
            'id'          => 'button_margin',
            'input'       => 'text',
            'placeholder' => '10px',
            'tooltip'     => __('Change the margin in pixels (px) around your floating social social share buttons.', 'novashare')
        )
    );

    //floating mobile section
    add_settings_section('floating_mobile', __('Mobile', 'novashare'), '__return_false', 'novashare');

    //mobile max width
    add_settings_field(
        'mobile_max_width', 
        novashare_title(__('Max Width', 'novashare'), 'floating-mobile_max_width', 'https://novashare.io/docs/breakpoints/#mobile-max-width'), 
        'novashare_print_input', 
        'novashare',
        'floating_mobile', 
        array(
            'section'     => 'floating',
            'id'          => 'mobile_max_width',
            'input'       => 'text',
            'validate'    => '[0-9.pPxX]',
            'maxlength'   => 25,
            'placeholder' => '800px',
            'tooltip'     => __('Set the max width in pixels (px) up to where the floating social share buttons should display on mobile.', 'novashare')
        )
    );

    //mobile background color
    add_settings_field(
        'background_color',
        novashare_title(__('Background Color', 'novashare'), false, 'https://novashare.io/docs/mobile-settings/#background-color'),
        'novashare_print_input',
        'novashare',
        'floating_mobile',
        array(
            'section' => 'floating',
            'id'      => 'background_color',
            'input'   => 'color',
            'tooltip' => __('Change the background color of the floating social share button container on mobile.', 'novashare')
        )
    );

    //mobile container padding
    add_settings_field(
        'background_padding',
        novashare_title(__('Background Padding', 'novashare'), false, 'https://novashare.io/docs/mobile-settings/#background-padding'),
        'novashare_print_input',
        'novashare',
        'floating_mobile',
        array(
            'section'     => 'floating',
            'id'          => 'background_padding',
            'input'       => 'text',
            'placeholder' => '10px',
            'tooltip'     => __('Change the padding around your floating social share buttons.', 'novashare')
        )
    );

    //fill space
    add_settings_field(
        'fill_space', 
        novashare_title(__('Fill Available Space', 'novashare'), 'floating-fill_space', 'https://novashare.io/docs/mobile-settings/#fill-available-space'), 
        'novashare_print_input', 
        'novashare', 
        'floating_mobile', 
        array(
            'section' => 'floating',
            'id'      => 'fill_space',
            'tooltip' => __('Allow your floating social share buttons to expand to fill the container.', 'novashare')
        )
    );

    //hide total share count
    add_settings_field(
        'hide_total_share_count', 
        novashare_title(__('Hide Total Share Count', 'novashare'), 'floating-hide_total_share_count', 'https://novashare.io/docs/mobile-settings/#hide-total-share-count'), 
        'novashare_print_input', 
        'novashare', 
        'floating_mobile', 
        array(
            'section' => 'floating',
            'id'      => 'hide_total_share_count',
            'tooltip' => __('Hide the total share count from your floating social share buttons on mobile.', 'novashare')
        )
    );

    //floating share counts section
    add_settings_section('floating_share_counts', __('Share Counts', 'novashare'), '__return_false', 'novashare');

    //total share count
    add_settings_field(
        'total_share_count', 
        novashare_title(__('Total Share Count', 'novashare'), 'floating-total_share_count', 'https://novashare.io/docs/enable-share-counts/#total'), 
        'novashare_print_input', 
        'novashare', 
        'floating_share_counts', 
        array(
            'section' => 'floating',
            'id'      => 'total_share_count',
            'class'   => 'novashare-input-controller',
            'tooltip' => __('Display the total share count with your floating social share buttons. Default: Enabled', 'novashare')
        )
    );

    //total share count position
    add_settings_field(
        'total_share_count_position', 
        novashare_title(__('Total Share Count Position', 'novashare'), 'floating-total_share_count_position', 'https://novashare.io/docs/enable-share-counts/#position'),
        'novashare_print_input', 
        'novashare', 
        'floating_share_counts', 
        array(
            'section' => 'floating',
            'id'      => 'total_share_count_position',
            'input'   => 'select',
            'options' => array(
                'before' => __("Before", 'novashare'),
                ''       => __("After", 'novashare')
            ),
            'class'   => 'floating-total_share_count' . (empty($novashare['floating']['total_share_count']) ? ' hidden' : ''),
            'tooltip' => __('Change the position of your floating total share count display. Default: After', 'novashare')
        )
    );

    //total share count color
    add_settings_field(
        'total_share_count_color',
        novashare_title(__('Total Share Count Color', 'novashare'), false, 'https://novashare.io/docs/enable-share-counts/#color'),
        'novashare_print_input',
        'novashare',
        'floating_share_counts',
        array(
            'section' => 'floating',
            'id'      => 'total_share_count_color',
            'input'   => 'color',
            'class'   => 'floating-total_share_count' . (empty($novashare['floating']['total_share_count']) ? ' hidden' : ''),
            'tooltip' => __('Change the text color of your floating bar total share count display.', 'novashare')
        )
    );

    //network share counts
    add_settings_field(
        'network_share_counts', 
        novashare_title(__('Network Share Counts', 'novashare'), 'floating-network_share_counts', 'https://novashare.io/docs/enable-share-counts/#network'), 
        'novashare_print_input', 
        'novashare', 
        'floating_share_counts', 
        array(
            'section' => 'floating',
            'id'      => 'network_share_counts',
            'tooltip' => __('Display individual network share counts when hovering over each button. Default: Enabled', 'novashare')
        )
    );

    //share button section
    add_settings_section('share', __('Share Button', 'novashare'), '__return_false', 'novashare');

    //social networks
    add_settings_field(
        'social_networks', 
        novashare_title(__('Social Networks', 'novashare'), false, 'https://novashare.io/docs/share-button/#social-networks'), 
        'novashare_print_social_networks', 
        'novashare', 
        'share', 
        array(
            'section' => 'share',
            'id'      => 'social_networks',
            'tooltip' => __('Choose which social share buttons to display when the share button is clicked. Click on a square to enable or disable that specific network. Drag and drop squares to arrange the order in which they will display. Default: All Networks', 'novashare')
        )
    );

    //share call to action section
    add_settings_section('share_cta', __('Call to Action', 'novashare'), '__return_false', 'novashare');

    //call to action text
    add_settings_field(
        'cta_text', 
        novashare_title(__('Text', 'novashare'), 'share_cta-cta_text', 'https://novashare.io/docs/share-button/#text'), 
        'novashare_print_input', 
        'novashare', 
        'share_cta', 
        array(
            'section'     => 'share',
            'id'          => 'cta_text',
            'input'       => 'text',
            'maxlength'   => 50,
            'placeholder' => __('Share to...', 'novashare'),
            'tooltip'     => __('Change the call to action text displayed in your share button window.', 'novashare')
        )
    );

    //background color
    add_settings_field(
        'cta_background_color', 
        novashare_title(__('Background Color', 'novashare'), 'share_cta-background_color', 'https://novashare.io/docs/share-button/#background-color'), 
        'novashare_print_input', 
        'novashare', 
        'share_cta', 
        array(
            'section'     => 'share',
            'id'          => 'cta_background_color',
            'input'       => 'color',
            'tooltip'     => __('Change the background color of the call to action in your share button window.', 'novashare')
        )
    );

    //font color
    add_settings_field(
        'cta_font_color', 
        novashare_title(__('Font Color', 'novashare'), 'share_cta-font_color', 'https://novashare.io/docs/share-button/#font-color'), 
        'novashare_print_input', 
        'novashare', 
        'share_cta', 
        array(
            'section'     => 'share',
            'id'          => 'font_color',
            'input'       => 'color',
            'tooltip'     => __('Change the font color of the call to action in your share button window.', 'novashare')
        )
    );

    //share design section
    add_settings_section('share_design', __('Design', 'novashare'), '__return_false', 'novashare');

    //button style
    add_settings_field(
        'style', 
        novashare_title(__('Button Style', 'novashare'), 'share-style', 'https://novashare.io/docs/share-button/#style'),
        'novashare_print_input', 
        'novashare', 
        'share_design', 
        array(
            'section' => 'share',
            'id'      => 'style',
            'input'   => 'select',
            'options' => array(
                '' => __("Solid", 'novashare'),
                'inverse' => __("Inverse", 'novashare'),
                'solid-inverse-border' => __("Bordered Label", 'novashare'),
                'full-inverse-border' => __("Bordered Button", 'novashare'),
                'solid-inverse' => __("Minimal Label", 'novashare'),
                'full-inverse' => __("Minimal", 'novashare')
            ),
            'tooltip' => __('Change the style of your share button window social share buttons. Default: Solid', 'novashare')
        )
    );

    //button size
    add_settings_field(
        'size', 
        novashare_title(__('Button Size', 'novashare'), 'share-size', 'https://novashare.io/docs/share-button/#size'), 
        'novashare_print_input', 
        'novashare', 
        'share_design', 
        array(
            'section' => 'share',
            'id'      => 'size',
            'input'   => 'select',
            'options' => array(
                'small' => __("Small", 'novashare'),
                ''      => __("Medium", 'novashare'),
                'large' => __("Large", 'novashare')
            ),
            'tooltip' => __('Change the size of your share button window social share buttons. Default: Medium', 'novashare')
        )
    );

    //button shape
    add_settings_field(
        'shape', 
        novashare_title(__('Button Shape', 'novashare'), 'share-shape', 'https://novashare.io/docs/share-button/#shape'), 
        'novashare_print_input', 
        'novashare', 
        'share_design', 
        array(
            'section' => 'share',
            'id'      => 'shape',
            'input'   => 'select',
            'options' => array(
                ''         => __("Squared", 'novashare'),
                'rounded'  => __("Rounded", 'novashare'),
                'circular' => __("Circular", 'novashare')
            ),
            'tooltip' => __('Change the shape of your share button window social share buttons. Default: Squared', 'novashare')
        )
    );

    //click to tweet section
    add_settings_section('click_to_tweet', __('Click to Tweet', 'novashare'), '__return_false', 'novashare');

    //theme
    add_settings_field(
        'theme', 
        novashare_title(__('Theme', 'novashare'), 'click_to_tweet-theme', 'https://novashare.io/docs/click-to-tweet/#theme'), 
        'novashare_print_input', 
        'novashare', 
        'click_to_tweet', 
        array(
            'section' => 'click_to_tweet',
            'id'      => 'theme',
            'input'   => 'select',
            'options' => array(
                ''           => __("Default", 'novashare'),
                'simple'     => __("Simple", 'novashare'),
                'simple-alt' => __("Simple Alternate", 'novashare')
            ),
            'tooltip' => __('Change the visual style of your click to tweet boxes.', 'novashare')
        )
    );

    //call to action text
    add_settings_field(
        'cta_text', 
        novashare_title(__('Call to Action Text', 'novashare'), 'click_to_tweet-cta_text', 'https://novashare.io/docs/click-to-tweet/#cta-text'), 
        'novashare_print_input', 
        'novashare', 
        'click_to_tweet', 
        array(
            'section'     => 'click_to_tweet',
            'id'          => 'cta_text',
            'input'       => 'text',
            'maxlength'   => 50,
            'placeholder' => __('Click to Tweet', 'novashare'),
            'tooltip'     => __('Change the default call to action text displayed on your click to tweet boxes.', 'novashare')
        )
    );

    //call to action position
    add_settings_field(
        'cta_position', 
        novashare_title(__('Call to Action Position', 'novashare'), 'click_to_tweet-cta_position', 'https://novashare.io/docs/click-to-tweet/#cta-position'), 
        'novashare_print_input', 
        'novashare', 
        'click_to_tweet', 
        array(
            'section' => 'click_to_tweet',
            'id'      => 'cta_position',
            'input'   => 'select',
            'options' => array(
                ''     => __("Right (Default)", 'novashare'),
                'left' => __("Left", 'novashare')
            ),
            'tooltip' => __('Change the position of your call to action text displayed on your click to tweet boxes.', 'novashare')
        )
    );

    //remove url
    add_settings_field(
        'remove_url', 
        novashare_title(__('Remove URL', 'novashare'), 'click_to_tweet-remove_url', 'https://novashare.io/docs/click-to-tweet/#remove-url'), 
        'novashare_print_input', 
        'novashare', 
        'click_to_tweet', 
        array(
            'section' => 'click_to_tweet',
            'id'      => 'remove_url',
            'tooltip' => __('Change the default status of the remove url option on your click to tweet boxes.', 'novashare')
        )
    );

    //remove username
    add_settings_field(
        'remove_username', 
        novashare_title(__('Remove Username', 'novashare'), 'click_to_tweet-remove_username', 'https://novashare.io/docs/click-to-tweet/#remove-username'), 
        'novashare_print_input', 
        'novashare', 
        'click_to_tweet', 
        array(
            'section' => 'click_to_tweet',
            'id'      => 'remove_username',
            'tooltip' => __('Change the default status of the remove username option on your click to tweet boxes.', 'novashare')
        )
    );

    //hide hashtags
    add_settings_field(
        'hide_hashtags', 
        novashare_title(__('Hide Hashtags', 'novashare'), 'click_to_tweet-hide_hashtags', 'https://novashare.io/docs/click-to-tweet/#hide-hashtags'), 
        'novashare_print_input', 
        'novashare', 
        'click_to_tweet', 
        array(
            'section' => 'click_to_tweet',
            'id'      => 'hide_hashtags',
            'tooltip' => __('Change the default status of the hide hashtags option on your click to tweet boxes.', 'novashare')
        )
    );

    //accent color
    add_settings_field(
        'accent_color', 
        novashare_title(__('Accent Color', 'novashare'), 'click_to_tweet-accent_color', 'https://novashare.io/docs/click-to-tweet/#accent-color'), 
        'novashare_print_input', 
        'novashare', 
        'click_to_tweet', 
        array(
            'section' => 'click_to_tweet',
            'id'      => 'accent_color',
            'input'   => 'color',
            'tooltip' => __('Change the default accent color used on your click to tweet boxes.', 'novashare')
        )
    );

    //pinterest section
    add_settings_section('pinterest', __('Pinterest', 'novashare'), '__return_false', 'novashare');

    //share button behavior
    add_settings_field(
        'share_button_behavior', 
        novashare_title(__('Share Button Behavior', 'novashare'), 'pinterest-share_button_behavior', 'https://novashare.io/docs/pinterest-settings/#share-button-behavior'), 
        'novashare_print_input', 
        'novashare', 
        'pinterest', 
        array(
            'section' => 'pinterest',
            'id'      => 'share_button_behavior',
            'input'   => 'select',
            'options' => array(
                ''        => __('Share Post Image', 'novashare'),
                'pinterest' => __('Show Pinterest Images', 'novshare'),
                'gallery' => __('Show All Pinnable Images', 'novashare')
            ),
            'tooltip' => __('Change what happens when the Pinterest share button is clicked. Default: Share Post Image', 'novashare'),
        )
    );

    //enable image attributes
    add_settings_field(
        'image_attributes', 
        novashare_title(__('Enable Image Attributes', 'novashare'), 'pinterest', 'https://novashare.io/docs/pinterest-settings/#image-attributes'), 
        'novashare_print_input', 
        'novashare', 
        'pinterest', 
        array(
            'section' => 'pinterest',
            'id'      => 'image_attributes',
            'class'   => 'pinterest-image_pins' . (!empty($novashare['pinterest']['image_pins']) ? ' hidden' : ''),
            'tooltip' => __('Allow for control of Pinterest-specific image attributes throughout the plugin, even if image pins are not enabled. Default: Disabled', 'novashare'),
        )
    );

    add_settings_section('pinterest_image_pins', __('Image Pins', 'novashare'), '__return_false', 'novashare');

    //enable image pins
    add_settings_field(
        'image_pins', 
        novashare_title(__('Enable Image Pins', 'novashare'), 'pinterest-image_pins', 'https://novashare.io/docs/pinterest-image-hover-pins/#image-pins'), 
        'novashare_print_input', 
        'novashare', 
        'pinterest_image_pins', 
        array(
            'section' => 'pinterest',
            'id'      => 'image_pins',
            'class'   => 'novashare-input-controller',
            'tooltip' => __('Show Pinterest pin buttons when hovering over images in your content. Default: Disabled', 'novashare'),
        )
    );

    //post types
    add_settings_field(
        'post_types',
        novashare_title(__('Post Types', 'novashare'), false, 'https://novashare.io/docs/pinterest-image-hover-pins/#post-types'),
        'novashare_print_post_types',
        'novashare',
        'pinterest_image_pins',
        array(
            'section' => 'pinterest',
            'id'      => 'post_types',
            'class'   => 'pinterest-image_pins' . (empty($novashare['pinterest']['image_pins']) ? ' hidden' : ''),
            'tooltip' => __('Choose which post types display Pinterest image pins.', 'novashare')
        )
    );

    //button position
    add_settings_field(
        'pin_button_position',
        novashare_title(__('Button Position', 'novashare'), false, 'https://novashare.io/docs/pinterest-image-hover-pins/#button-position'),
        'novashare_print_input',
        'novashare',
        'pinterest_image_pins',
        array(
            'section' => 'pinterest',
            'id'      => 'pin_button_position',
            'input'   => 'select',
            'options' => array(
                '' => __('Top Left', 'novashare'),
                'topright' => __('Top Right', 'novashare'),
                'bottomleft' => __('Bottom Left', 'novashare'),
                'bottomright' => __('Bottom Right', 'novashare'),
                'center' => __('Center', 'novashare')
            ),
            'class'   => 'pinterest-image_pins' . (empty($novashare['pinterest']['image_pins']) ? ' hidden' : ''),
            'tooltip' => __('Choose where on the image to display your Pinterest image pin buttons. Default: Top Left', 'novashare')
        )
    );

    //button shape
    add_settings_field(
        'pin_button_shape',
        novashare_title(__('Button Shape', 'novashare'), false, 'https://novashare.io/docs/pinterest-image-hover-pins/#button-shape'),
        'novashare_print_input',
        'novashare',
        'pinterest_image_pins',
        array(
            'section' => 'pinterest',
            'id'      => 'pin_button_shape',
            'input'   => 'select',
            'options' => array(
                '' => __('Squared', 'novashare'),
                'rounded' => __('Rounded', 'novashare'),
                'circular' => __('Circular', 'novashare')
            ),
            'class'   => 'pinterest-image_pins' . (empty($novashare['pinterest']['image_pins']) ? ' hidden' : ''),
            'tooltip' => __('Change the shape of your Pinterest image pin buttons. Default: Squared', 'novashare')
        )
    );

    //button color
    add_settings_field(
        'button_color',
        novashare_title(__('Button Color', 'novashare'), false, 'https://novashare.io/docs/pinterest-image-hover-pins/#button-color'),
        'novashare_print_input',
        'novashare',
        'pinterest_image_pins',
        array(
            'section' => 'pinterest',
            'id'      => 'pin_button_color',
            'input'   => 'color',
            'tooltip' => __('Change the background color of your Pinterest image pin buttons.', 'novashare')
        )
    );

    //icon color
    add_settings_field(
        'icon_color',
        novashare_title(__('Icon Color', 'novashare'), false, 'https://novashare.io/docs/pinterest-image-hover-pins/#icon-color'),
        'novashare_print_input',
        'novashare',
        'pinterest_image_pins',
        array(
            'section' => 'pinterest',
            'id'      => 'pin_icon_color',
            'input'   => 'color',
            'tooltip' => __('Change the icon color of your Pinterest image pin buttons.', 'novashare')
        )
    );

    //hide labels
    add_settings_field(
        'hide_pin_button_labels', 
        novashare_title(__('Hide Button Labels', 'novashare'), 'pinterest-image_pins', 'https://novashare.io/docs/pinterest-image-hover-pins/#hide-button-labels'), 
        'novashare_print_input', 
        'novashare', 
        'pinterest_image_pins', 
        array(
            'section' => 'pinterest',
            'id'      => 'hide_pin_button_labels',
            'class'   => 'novashare-input-controller',
            'class'   => 'pinterest-image_pins' . (empty($novashare['pinterest']['image_pins']) ? ' hidden' : ''),
            'tooltip' => __('Hide the labels on your Pinterest image pin buttons. Default: Disabled', 'novashare'),
        )
    );

    //image pins exclusions
    add_settings_field(
        'excluded_images',
        novashare_title(__('Excluded Images', 'novashare'), 'pinterest-excluded_images', 'https://novashare.io/docs/pinterest-image-hover-pins/#excluded-images'),
        'novashare_print_input',
        'novashare',
        'pinterest_image_pins',
        array(
            'section'      => 'pinterest',
            'id'           => 'excluded_images',
            'input'        => 'textarea',
            'textareatype' => 'oneperline',
            'class'        => 'pinterest-image_pins' . (empty($novashare['pinterest']['image_pins']) ? ' hidden' : ''),
            'placeholder'  => 'example.png',
            'tooltip'      => __('Exclude specific images from getting pins applied. Exclude an image by adding the source URL (example.png) or by adding any unique portion of its attribute string (class="example"). Format: one per line', 'novashare')
        )
    );

    //configuration section
    add_settings_section('config', __('Configuration', 'novashare'), '__return_false', 'novashare');

    //enable twitter counts
    add_settings_field(
        'twitter_counts', 
        novashare_title(__('Enable Twitter Counts', 'novashare'), 'twitter_counts', 'https://novashare.io/docs/configure-share-counts/#twitter-counts'), 
        'novashare_print_input', 
        'novashare', 
        'config', 
        array(
            'id'      => 'twitter_counts',
            'class'   => 'novashare-input-controller',
            'tooltip' => __('Request and store Twitter share counts using a third party service. Default: Disabled', 'novashare'),
        )
    );

    //twitter count service
    add_settings_field(
        'twitter_count_service', 
        novashare_title(__('Twitter Count Service', 'novashare'), 'twitter_count_service', 'https://novashare.io/docs/configure-share-counts/#twitter-count-service'), 
        'novashare_print_input', 
        'novashare', 
        'config', 
        array(
            'id'      => 'twitter_count_service',
            'input'   => 'select',
            'options' => array(
                ''               => 'TwitCount (' . __('Default', 'novashare') . ')',
                'opensharecount' => 'OpenShareCount'
            ),
            'class'   => 'twitter_counts' . (empty($novashare['twitter_counts']) ? ' hidden' : ''),
            'tooltip' => __('Choose which service to use to pull Twitter share counts.', 'novashare'),
        )
    );

    //twitter username
    add_settings_field(
        'twitter_username', 
        novashare_title(__('Twitter Username', 'novashare'), 'twitter_username', 'https://novashare.io/docs/add-twitter-username/'), 
        'novashare_print_input', 
        'novashare', 
        'config', 
        array(
            'id'          => 'twitter_username',
            'input'       => 'text',
            'validate'    => '[0-9a-zA-Z_]',
            'maxlength'   => 15,
            'placeholder' => __('novashare', 'novashare'),
            'tooltip'     => __('The username used when sharing content to Twitter.', 'novashare')
        )
    );

    //facebook app id
    add_settings_field(
        'facebook_app_id', 
        novashare_title(__('Facebook App ID', 'novashare'), 'facebook_app_id', 'https://novashare.io/docs/configure-share-counts/#facebook-app-id-app-secret'), 
        'novashare_print_input', 
        'novashare', 
        'config', 
        array(
            'id'        => 'facebook_app_id',
            'input'     => 'text',
            'maxlength' => 50,
            'tooltip'   => __('The Facebook App ID from your Facebook Developer Application.', 'novashare')
        )
    );

    //facebook app secret
    add_settings_field(
        'facebook_app_secret', 
        novashare_title(__('Facebook App Secret', 'novashare'), 'facebook_app_secret', 'https://novashare.io/docs/configure-share-counts/#facebook-app-id-app-secret'), 
        'novashare_print_input', 
        'novashare', 
        'config', 
        array(
            'id'        => 'facebook_app_secret',
            'input'     => 'password',
            'maxlength' => 50,
            'tooltip'   => __('The Facebook App Secret from your Facebook Developer Application.', 'novashare')
        )
    );

    //subscribe link
    add_settings_field(
        'subscribe_link', 
        novashare_title(__('Subscribe Link', 'novashare'), 'subscribe_link', 'https://novashare.io/docs/subscribe-button-link/'), 
        'novashare_print_input', 
        'novashare', 
        'config', 
        array(
            'id'          => 'subscribe_link',
            'input'       => 'text',
            'maxlength'   => 250,
            'placeholder' => 'https://example.com/subscribe',
            'tooltip'     => __('The URL used for your subscribe button.', 'novashare')
        )
    );

    //global styles
    add_settings_field(
        'global_styles', 
        novashare_title(__('Global Styles', 'novashare'), 'global_styles', 'https://novashare.io/docs/shortcode/#global-styles'), 
        'novashare_print_input', 
        'novashare', 
        'config', 
        array(
            'id'      => 'global_styles',
            'tooltip' => __('Enqueue stylesheet globally to allow for use of shortcodes and widgets in custom templates. Default: Disabled', 'novashare')
        )
    );

    //custom css
    add_settings_field(
        'custom_css', 
        novashare_title(__('Custom CSS', 'novashare'), false, 'https://novashare.io/docs/custom-css/'), 
        'novashare_print_input', 
        'novashare', 
        'config', 
        array(
            'id'           => 'custom_css',
            'input'        => 'textarea',
            'textareatype' => 'codemirror',
            'tooltip'      => __('Add custom CSS to only load when needed.', 'novashare')
        )
    );

    //meta section
    add_settings_section('config_meta', __('Meta', 'novashare'), function() {

        //seo plugin notice
        if(defined('WPSEO_VERSION') || defined('AIOSEO_VERSION') || defined('SEOPRESS_VERSION')) {
            echo '<div class="novashare-section-notice">';
                echo '<span class="dashicons dashicons-info-outline"></span>';
                echo __('A compatible SEO plugin is active. Meta and open graph data that is not specific to Novashare will be handled there.', 'novashare');
            echo '</div>'; 
        }

    }, 'novashare');

    //enable open graph
    add_settings_field(
        'open_graph', 
        novashare_title(__('Enable Open Graph', 'novashare'), 'open_graph', 'https://novashare.io/docs/open-graph-meta-tags/'), 
        'novashare_print_input', 
        'novashare', 
        'config_meta', 
        array(
            'id'      => 'open_graph',
            'tooltip' => sprintf(__('Print out open graph meta tags with Novashare in the %s section of your site. Default: Enabled', 'novashare'), '<head>')
        )
    );

    //hide meta box
    add_settings_field(
        'hide_meta', 
        novashare_title(__('Hide Meta Box', 'novashare'), 'hide_meta', 'https://novashare.io/docs/hide-meta-box/'), 
        'novashare_print_input', 
        'novashare', 
        'config_meta', 
        array(
            'id'      => 'hide_meta',
            'tooltip' => __('Hide Novashare meta box in the WordPress editor. Default: Disabled', 'novashare')
        )
    );

    //default social image
    add_settings_field(
        'default_social_image', 
        novashare_title(__('Default Social Image', 'novashare'), 'default_social_image', 'https://novashare.io/docs/post-meta-details/#default-social-image'), 
        'novashare_print_input', 
        'novashare', 
        'config_meta', 
        array(
            'id'      => 'default_social_image',
            'input'   => 'image',
            'tooltip' => __('Add a default image that will be used for share links and meta tags if no post specific images are found.', 'novashare')
        )
    );


    //share counts section
    add_settings_section('config_share_counts', __('Share Counts', 'novashare'), '__return_false', 'novashare');

    //minimum share count
    add_settings_field(
        'minimum_share_count', 
        novashare_title(__('Minimum Share Count', 'novashare'), 'minimum_share_count', 'https://novashare.io/docs/minimum-share-count/'), 
        'novashare_print_input', 
        'novashare', 
        'config_share_counts', 
        array(
            'id'        => 'minimum_share_count',
            'input'     => 'text',
            'validate'  => '[0-9]',
            'maxlength' => 10,
            'tooltip'   => __('Set a minimum total share count threshold to reach before share counts are displayed.', 'novashare'),
        )
    );

    //refresh rate
    add_settings_field(
        'refresh_rate', 
        novashare_title(__('Share Counts Refresh Rate', 'novashare'), 'refresh_rate', 'https://novashare.io/docs/share-counts-refresh-rate/'),
        'novashare_print_input', 
        'novashare', 
        'config_share_counts', 
        array(
            'id'      => 'refresh_rate',
            'input'   => 'select',
            'options' => array(
                ''        => __("High", 'novashare'),
                'medium'   => __("Medium", 'novashare'),
                'low'    => __("Low", 'novashare')
            ),
            'tooltip' => __('Adjust the rate at which your social share counts are refreshed based on the modified date. Default: High', 'novashare') .
                            '<table class="novashare-tooltip-table">
                                <tr><th>' . __('Modified', 'novashare') . '</th><td><7 ' . __('days' , 'novashare') . '</td><td><28 ' . __('days' , 'novashare') . '</td><td>>28 ' . __('days' , 'novashare') . '</td></tr>
                                <tr><th>' . __('High', 'novashare') . '</th><td>3 ' . __('hours', 'novashare') . '</td><td>6 ' . __('hours', 'novashare') . '</td><td>12 ' . __('hours', 'novashare') . '</td></tr>
                                <tr><th>' . __('Medium', 'novashare') . '</th><td>6 ' . __('hours', 'novashare') . '</td><td>12 ' . __('hours', 'novashare') . '</td><td>24 ' . __('hours', 'novashare') . '</td></tr>
                                <tr><th>' . __('Low', 'novashare') . '</th><td>12 ' . __('hours', 'novashare') . '</td><td>24 ' . __('hours', 'novashare') . '</td><td>48 ' . __('hours', 'novashare') . '</td></tr>
                            </table>'
        )
    );

    //purge share counts
    add_settings_field(
        'purge_share_counts', 
        novashare_title(__('Purge Share Counts', 'novashare'), false, 'https://novashare.io/docs/purge-share-counts/'), 
        'novashare_print_input', 
        'novashare', 
        'config_share_counts', 
        array(
            'id'           => 'purge_share_counts',
            'input'        => 'button',
            'title'        => __('Purge Share Counts', 'novashare'),
            'confirmation' => __('Are you sure? This will delete all existing share count data for all posts from the database.', 'novashare'),
            'tooltip'      => __('Permanently delete all existing share counts from your database.', 'novashare')
        )
    );

    //google analytics section
    add_settings_section('config_ga', __('Google Analytics', 'novashare'), '__return_false', 'novashare');

    //enable utm tracking
    add_settings_field(
        'google_utm', 
        novashare_title(__('Enable UTM Tracking', 'novashare'), 'google_utm', 'https://novashare.io/docs/google-analytics/#utm-tracking'), 
        'novashare_print_input', 
        'novashare', 
        'config_ga', 
        array(
            'id'      => 'google_utm',
            'class'   => 'novashare-input-controller',
            'tooltip' => __('Add UTM parameters to social sharing links.', 'novashare')
        )
    );

    //google utm
    add_settings_field(
        'google_utm_source', 
        novashare_title(__('Campaign UTM Source', 'novashare'), 'google_utm_source', 'https://novashare.io/docs/google-analytics/#campaign-utm-source'), 
        'novashare_print_input', 
        'novashare', 
        'config_ga', 
        array(
            'id'        => 'google_utm_source',
            'input'     => 'text',
            'maxlength' => 25,
            'class'     => 'google_utm' . (empty($novashare['google_utm']) ? ' hidden' : ''),
            'tooltip'   => __('The value of the UTM source parameter added to your social sharing links. Use {{network}} to dynamically populate the value with the relative social network. Default: {{network}}', 'novashare')
        )
    );

    //google utm medium
    add_settings_field(
        'google_utm_medium', 
        novashare_title(__('Campaign UTM Medium', 'novashare'), 'google_utm_medium', 'https://novashare.io/docs/google-analytics/#campaign-utm-medium'), 
        'novashare_print_input', 
        'novashare', 
        'config_ga', 
        array(
            'id'        => 'google_utm_medium',
            'input'     => 'text',
            'maxlength' => 25,
            'class'     => 'google_utm' . (empty($novashare['google_utm']) ? ' hidden' : ''),
            'tooltip'   => __('The value of the UTM medium parameter added to your social sharing links. Default: social', 'novashare')
        )
    );

    //google utm name
    add_settings_field(
        'google_utm_name', 
        novashare_title(__('Campaign UTM Name', 'novashare'), 'google_utm_name', 'https://novashare.io/docs/google-analytics/#campaign-utm-name'), 
        'novashare_print_input',
        'novashare', 
        'config_ga', 
        array(
            'id'        => 'google_utm_name',
            'input'     => 'text',
            'maxlength' => 25,
            'class'     => 'google_utm' . (empty($novashare['google_utm']) ? ' hidden' : ''),
            'tooltip'   => __('The value of the UTM name parameter added to your social sharing links. Default: novashare', 'novashare')
        )
    );

    //link shortening section
    add_settings_section('config_link_shortening', __('Link Shortening', 'novashare'), '__return_false', 'novashare');

    //enable bitly
    add_settings_field(
        'enable_bitly', 
        novashare_title(__('Enable Bitly', 'novashare'), 'enable_bitly', 'https://novashare.io/docs/link-shortening/#bitly'), 
        'novashare_print_input', 
        'novashare', 
        'config_link_shortening', 
        array(
            'id'      => 'enable_bitly',
            'class'   => 'novashare-input-controller',
            'tooltip' => __('Generate Bitly short links for all share URLs.', 'novashare')
        )
    );

    //bitly access token
    add_settings_field(
        'bitly_access_token', 
        novashare_title(__('Generic Access Token', 'novashare'), 'bitly_access_token', 'https://novashare.io/docs/link-shortening/#generic-access-token'), 
        'novashare_print_input', 
        'novashare', 
        'config_link_shortening', 
        array(
            'id'        => 'bitly_access_token',
            'input'     => 'text',
            'maxlength' => 50,
            'class'   => 'enable_bitly' . (empty($novashare['enable_bitly']) ? ' hidden' : ''),
            'tooltip'   => __('The Generic Access Token from your Bitly account.', 'novashare')
        )
    );

    //bitly group
    add_settings_field(
        'bitly_group_guid', 
        novashare_title(__('Group', 'novashare'), 'bitly_group_guid', 'https://novashare.io/docs/link-shortening/#group'), 
        'novashare_print_input', 
        'novashare', 
        'config_link_shortening', 
        array(
            'id'      => 'bitly_group_guid',
            'input'   => 'select',
            'options' => novashare_bitly_groups(),
            'class'   => 'enable_bitly' . (empty($novashare['enable_bitly']) ? ' hidden' : ''),
            'tooltip' => __('The group from your Bitly account used to generate and store short links. Non-enterprise users will only have one default group.', 'novashare')
        )
    );

    //purge short links
    add_settings_field(
        'purge_short_links', 
        novashare_title(__('Purge Short Links', 'novashare'), false, 'https://novashare.io/docs/link-shortening/#purge-short-links'), 
        'novashare_print_input', 
        'novashare', 
        'config_link_shortening', 
        array(
            'id'           => 'purge_short_links',
            'input'        => 'button',
            'title'        => __('Purge Short Links', 'novashare'),
            'class'        => 'enable_bitly' . (empty($novashare['enable_bitly']) ? ' hidden' : ''),
            'confirmation' => __('Are you sure? This will delete all existing short links from the database.', 'novashare'),
            'tooltip'      => __('Permanently delete all existing short links from your database.', 'novashare')
        )
    );

    //share count recovery section
    add_settings_section('config_share_count_recovery', __('Share Count Recovery', 'novashare'), '__return_false', 'novashare');

    //combine http + https
    add_settings_field(
        'combine_http_https', 
        novashare_title(__('Combine HTTP & HTTPS', 'novashare'), 'combine_http_https', 'https://novashare.io/docs/combine-http-https-social-share-counts'), 
        'novashare_print_input', 
        'novashare', 
        'config_share_count_recovery', 
        array(
            'id'      => 'combine_http_https',
            'tooltip' => __('Combine share counts for HTTP and HTTPS URLs for networks that store them separately. This will double the amount of API calls for those networks.', 'novashare')
        )
    );

    //recover previous permalinks
    add_settings_field(
        'recover_previous_permalinks', 
        novashare_title(__('Recover Previous Permalinks', 'novashare'), 'recover_previous_permalinks', 'https://novashare.io/docs/recover-permalinks-social-share-counts'), 
        'novashare_print_input', 
        'novashare', 
        'config_share_count_recovery', 
        array(
            'id'      => 'recover_previous_permalinks',
            'class'   => 'novashare-input-controller',
            'tooltip' => __('Recover share counts for a previous permalink structure.', 'novashare')
        )
    );

    //previous permalink structure
    add_settings_field(
        'previous_permalink_structure', 
        novashare_title(__('Previous Permalink Structure', 'novashare'), 'previous_permalink_structure', 'https://novashare.io/docs/recover-permalinks-social-share-counts/#structure'),
        'novashare_print_input', 
        'novashare', 
        'config_share_count_recovery', 
        array(
            'id'      => 'previous_permalink_structure',
            'input'   => 'select',
            'options' => array(
                ''                                     => __('Select a Structure', 'novashare'),
                'plain'                                => __("Plain", 'novashare'),
                '/%year%/%monthnum%/%day%/%postname%/' => __("Day and name", 'novashare'),
                '/%year%/%monthnum%/%postname%/'       => __("Month and name", 'novashare'),
                '/archives/%post_id%'                  => __("Numeric", 'novashare'),
                '/%postname%/'                         => __("Post name", 'novashare'),
                'custom'                               => __("Custom Structure", 'novashare')
            ),
            'class'   => 'recover_previous_permalinks' . (empty($novashare['recover_previous_permalinks']) ? ' hidden' : ''),
            'tooltip' => __('The permalink structure used to recover share counts.', 'novashare')
        )
    );

    //custom permalink structure
    add_settings_field(
        'previous_permalink_structure_custom', 
        novashare_title(__('Custom Permalink Structure', 'novashare'), 'previous_permalink_structure_custom', 'https://novashare.io/docs/recover-permalinks-social-share-counts/#custom'), 
        'novashare_print_input', 
        'novashare', 
        'config_share_count_recovery', 
        array(
            'id'          => 'previous_permalink_structure_custom',
            'input'       => 'text',
            'class'       => 'recover_previous_permalinks' . (empty($novashare['recover_previous_permalinks']) ? ' hidden' : ''),
            'tooltip'     => __('If you are recovering share counts for a custom permalink structure, please provide that structure here.', 'novashare'),
            'placeholder' => '/example/%postname%/'
        )
    );

    //recover previous domain
    add_settings_field(
        'recover_previous_domain', 
        novashare_title(__('Recover Previous Domain', 'novashare'), 'recover_previous_domain', 'https://novashare.io/docs/recover-domain-social-share-counts'), 
        'novashare_print_input', 
        'novashare', 
        'config_share_count_recovery', 
        array(
            'id'      => 'recover_previous_domain',
            'class'   => 'novashare-input-controller',
            'tooltip' => __('Recover share counts for a previous domain.', 'novashare')
        )
    );

    //previous domain
    add_settings_field(
        'previous_domain', 
        novashare_title(__('Previous Domain', 'novashare'), 'previous_domain', 'https://novashare.io/docs/recover-domain-social-share-counts/#domain'), 
        'novashare_print_input', 
        'novashare', 
        'config_share_count_recovery', 
        array(
            'id'          => 'previous_domain',
            'input'       => 'text',
            'class'       => 'recover_previous_domain' . (empty($novashare['recover_previous_domain']) ? ' hidden' : ''),
            'placeholder' => 'domain.com',
            'tooltip'     => __('The domain used to recover share counts.', 'novashare')
        )
    );

    register_setting('novashare', 'novashare', 'novashare_sanitize_options');

    //extra options section
    add_settings_section('novashare_tools', __('Tools', 'novashare'), '__return_false', 'novashare_tools');

    if(!is_multisite()) {

        //clean uninstall
        add_settings_field(
            'clean_uninstall', 
            novashare_title(__('Clean Uninstall', 'novashare'), 'clean_uninstall', 'https://novashare.io/docs/clean-uninstall/'), 
            'novashare_print_input', 
            'novashare_tools', 
            'novashare_tools', 
            array(
                'id'      => 'clean_uninstall',
                'option'  => 'novashare_tools',
                'tooltip' => __('Permanently delete all Novashare data from your database when the plugin is uninstalled.', 'novashare')
            )
        );
    }

    //accessibility mode
    add_settings_field(
        'accessibility_mode', 
        novashare_title(__('Accessibility Mode', 'novashare'), 'accessibility_mode', 'https://novashare.io/docs/accessibility-mode/'),
        'novashare_print_input',
        'novashare_tools', 
        'novashare_tools', 
        array(
            'id'      => 'accessibility_mode',
            'option'  => 'novashare_tools',
            'input'   => 'checkbox',
            'tooltip' => __('Disable the use of visual UI elements in the plugin settings such as checkbox toggles and hovering tooltips.', 'novashare')
        )
    );

    //restore defaults
    add_settings_field(
        'restore_defaults', 
        novashare_title(__('Restore Default Options', 'novashare'), 'restore_defaults', 'https://novashare.io/docs/restore-default-options/'), 
        'novashare_print_input',
        'novashare_tools', 
        'novashare_tools', 
        array(
            'id'      => 'restore_defaults',
            'input'   => 'button',
            'title'   => __('Restore Default Options', 'novashare'),
            'confirmation' => __('Are you sure? This will remove all existing plugin options and restore them to their default states.', 'novashare'),
            'option'  => 'novashare_tools',
            'tooltip' => __('Restore all plugin options to their default settings.', 'novashare')
        )
    );

    //export settings
    add_settings_field(
        'export_settings', 
        novashare_title(__('Export Settings', 'novashare'), 'export_settings', 'https://novashare.io/docs/import-export/'), 
        'novashare_print_input',
        'novashare_tools', 
        'novashare_tools', 
        array(
            'id'      => 'export_settings',
            'input'   => 'button',
            'title'   => __('Export Plugin Settings', 'novashare'),
            'option'  => 'novashare_tools',
            'tooltip' => __('Export your Novashare settings for this site as a .json file. This lets you easily import the configuration into another site.', 'novashare')
        )
    );

    //import settings
    add_settings_field(
        'import_settings', 
        novashare_title(__('Import Settings', 'novashare'), 'import_settings', 'https://novashare.io/docs/import-export/'), 
        'novashare_print_import_settings',
        'novashare_tools', 
        'novashare_tools', 
        array(
            'tooltip' => __('Import Novashare settings from an exported .json file.', 'novashare')
        )
    );

    register_setting('novashare_tools', 'novashare_tools');
}
add_action('admin_init', 'novashare_settings');

//options default values
function novashare_default_options() {
	$defaults = array(
		'inline' => array(
            'social_networks'      => array(0 => "twitter", 1 => "facebook", 2 => "linkedin"),
			'post_types'           => array(0 => "post"),
			'breakpoint'           => "1200px",
            'total_share_count'    => "1",
            'network_share_counts' => "1"
		),
		'floating' => array(
            'social_networks'      => array(0 => "twitter", 1 => "facebook", 2 => "linkedin"),
            'post_types'           => array(0 => "post"),
            'breakpoint'           => "1200px",
            'total_share_count'    => "1",
            'network_share_counts' => "1"
		),
        'open_graph'               => "1",
        'google_utm_source'        => "{{network}}",
        'google_utm_medium'        => "social",
        'google_utm_name'          => "novashare"
	);
    novashare_network_defaults($defaults, 'novashare');
	return apply_filters('novashare_default_options', $defaults);
}

//modify defaults for network
function novashare_network_defaults(&$defaults, $option) {
    if(is_multisite() && is_plugin_active_for_network('novashare/novashare.php')) {
        $novashare_network = get_site_option('novashare_network');
        if(!empty($novashare_network['default'])) {
            $networkDefaultOptions = get_blog_option($novashare_network['default'], $option);
            if(!empty($networkDefaultOptions)) {
                foreach($networkDefaultOptions as $key => $val) {
                    $defaults[$key] = $val;
                }
            }
        }
    }
}

//print settings section
function novashare_settings_section($page, $section, $dashicon = '', $class = '') {
    global $wp_settings_sections;
    if(!empty($wp_settings_sections[$page][$section])) {
        echo '<div class="novashare-settings-section' . ($class ? ' ' . $class : '') . '">';
            echo '<h2>' . ($dashicon ? '<span class="dashicons ' . $dashicon . '"></span>' : '') . __($wp_settings_sections[$page][$section]['title'], 'novashare') . '</h2>';
            call_user_func($wp_settings_sections[$page][$section]['callback']);
            echo '<table class="form-table">';
                echo '<tbody>';
                    do_settings_fields($page, $section);
                echo '</tbody>';
            echo '</table>';
        echo '</div>';
    }
}

//print form inputs
function novashare_print_input($args) {
    $selection_id = $args['id'];
    if(!empty($args['option'])) {
        $option = $args['option'];
        if($args['option'] == 'novashare_network') {
            $options = get_site_option($args['option']);
        }
        else {
            $options = get_option($args['option']);
        }
    }
    else {
        $option = 'novashare';
        $options = get_option('novashare');
    }
    if(!empty($args['option']) && $args['option'] == 'novashare_tools') {
        $tools = $options;
    }
    else {
        $tools = get_option('novashare_tools');
    }

    //set section variables
    if(!empty($args['section'])) {
        $selection_id = $args['section'] . '-' . $args['id'];
        $option = $option . '[' . $args['section'] . ']';
        $options = isset($options[$args['section']]) ? $options[$args['section']] : array();
    }

    //text + color
    if(!empty($args['input']) && ($args['input'] == 'text' || $args['input'] == 'color')) {
        echo "<input type='text' id='" . $selection_id . "' name='" . $option . "[" . $args['id'] . "]'";
        if($args['input'] == 'color') {
                echo " class='novashare-color-picker'";
            }
        echo " value='" . (!empty($options[$args['id']]) ? esc_html($options[$args['id']]) : '') . "' placeholder='" . (!empty($args['placeholder']) ? $args['placeholder'] : '') . "'" . (!empty($args['validate']) ? " novashare_validate='" . $args['validate'] . "'" : "") . (!empty($args['maxlength']) ? " maxlength='" . $args['maxlength'] . "'" : "") . " />";
    }

    //select
    elseif(!empty($args['input']) && $args['input'] == 'select') {
        echo "<select id='" . $selection_id . "' name='" . $option . "[" . $args['id'] . "]'>";
            foreach($args['options'] as $value => $title) {
                echo "<option value='" . $value . "' "; 
                if(!empty($options[$args['id']]) && $options[$args['id']] == $value || (empty($options[$args['id']]) && empty($value))) {
                    echo "selected";
                } 
                echo ">" . $title . "</option>";
            }
        echo "</select>";
    }

    //button
    elseif(!empty($args['input']) && $args['input'] == 'button') {
        echo "<button id='" . $selection_id . "' name='" . $option . "[" . $args['id'] . "]' value='1' class='button button-secondary'";
            if(!empty($args['confirmation'])) {
                echo " onClick=\"return confirm('" . $args['confirmation'] . "');\"";
            }
        echo ">";
            echo $args['title'];
        echo "</button>";
    }

    //password
    elseif(!empty($args['input']) && $args['input'] == 'password') {
        echo "<input type='password' id='" . $selection_id . "' name='" . $option . "[" . $args['id'] . "]' value='" . (!empty($options[$args['id']]) ? $options[$args['id']] : '') . "' />";
    }

    //textarea
    elseif(!empty($args['input']) && $args['input'] == 'textarea') {
        echo "<textarea id='" . $selection_id . "' name='" . $option . "[" . $args['id'] . "]' placeholder='" . (!empty($args['placeholder']) ? $args['placeholder'] : '') . "'" . (!empty($args['textareatype']) && $args['textareatype'] == 'codemirror' ? " class='novashare-codemirror'" : "" ) . ">";
            if(!empty($options[$args['id']])) {
                if(!empty($args['textareatype']) && $args['textareatype'] == 'oneperline') {
                    foreach($options[$args['id']] as $line) {
                        echo $line . "\n";
                    }
                }
                else {
                    echo $options[$args['id']];
                }
            }
        echo "</textarea>";
    }

    //image
    elseif(!empty($args['input']) && $args['input'] == 'image') {

        if(!empty($options[$args['id']])) {
            $thumbnail_url = wp_get_attachment_thumb_url($options[$args['id']]);
            $full_url = wp_get_attachment_url($options[$args['id']]);
        }

        echo "<div class='novashare-image-upload'>";
            echo "<div class='novashare-image-upload-input'>";
                echo "<input type='hidden' id='" . $selection_id . "' name='" . $option . "[" . $args['id'] . "]' value='" . ($options[$args['id']] ?? '') . "' />";
                echo "<input type='text' id='" . $selection_id . "_url'  value='" . (!empty($full_url) ? $full_url : '') . "' class='widefat' placeholder='" . __("Upload an image.", 'novashare') . "' disabled />";
            echo "</div>";
            echo "<div class='novashare-image-upload-preview" . (empty($thumbnail_url) ? " hidden" : "") . "'><a title='Remove'><span class='dashicons dashicons-no'></span>" . (!empty($thumbnail_url) ? "<img src='" . $thumbnail_url . "' />" : '') . "</a></div>";
            echo "<a class='novashare-image-upload-button button button-secondary' value='" . $selection_id . "' frame_title='" . __('Select an Image', 'novashare') . "'>" . __('Upload', 'novashare') . "</a>";
        echo "</div>";
    }

    //checkbox + toggle
    else {
        if(empty($tools['accessibility_mode']) && (empty($args['input']) || $args['input'] != 'checkbox')) {
            echo "<label for='" . $selection_id . "' class='novashare-switch'>";
        }
        echo "<input type='checkbox' id='" . $selection_id . "' name='" . $option . "[" . $args['id'] . "]' value='1' style='display: block; margin: 0px;' ";
        if(!empty($options[$args['id']])) {
            echo "checked";
        }
        echo ">";
        if(empty($tools['accessibility_mode']) && (empty($args['input']) || $args['input'] != 'checkbox')) {
               echo "<div class='novashare-slider'></div>";
           echo "</label>";
        }
    }

    //tooltip
    if(!empty($args['tooltip'])) {
        novashare_tooltip($args['tooltip']);
    }
}

//print sortable social networks
function novashare_print_social_networks($args) {
    $networks = novashare_networks();
    $novashare = get_option('novashare');
    $option = 'novashare';
    $force_active = false;
    if(!empty($args['section'])) {
        $option = $option . '[' . $args['section'] . ']';
        $novashare = $novashare[$args['section']] ?? array();

        //remove share button from network selection in share section
        if($args['section'] == 'share') {
            unset($networks['share']);
            if(empty($novashare['social_networks'])) {
                $force_active = true;
            }
        }
    }
    if(!empty($novashare['social_networks'])) {
        foreach(array_reverse($novashare['social_networks']) as $key => $val) {
            if(array_key_exists($val, $networks)) {
                $temp = array($val => $networks[$val]);
                unset($networks[$val]);
                $networks = array_merge($temp, $networks);
            }
        }
    }
    else {
        $novashare['social_networks'] = array();
    }

    echo "<ul class='novashare-social-networks novashare-sortable'>";
        foreach($networks as $id => $details) {
            echo "<li class='novashare-social-network-" . $id . "'>";
                echo "<label for='novashare" . (!empty($args['section']) ? "-" . $args['section'] : "") . "-social-network-input-" . $id . "' class='" . ($force_active || in_array($id, $novashare['social_networks']) ? "active" : "") . "'>";
                    echo $details['icon'];
                    echo $details['name'];
                    echo "<input type='checkbox' id='novashare" . (!empty($args['section']) ? "-" . $args['section'] : "") . "-social-network-input-" . $id . "' name='" . $option . "[social_networks][]' value='" . $id . "'" . ($force_active || in_array($id, $novashare['social_networks']) ? " checked" : "") . " />";
                echo "</label>";
            echo "</li>";
        }
    echo "</ul>";

    //tooltip
    if(!empty($args['tooltip'])) {
        novashare_tooltip($args['tooltip']);
    }
}

//print post type checkboxes
function novashare_print_post_types($args) {
    $option = 'novashare';
    $options = get_option('novashare');
    $tools = get_option('novashare_tools');

    if(!empty($args['section'])) {
        $option = $option . '[' . $args['section'] . ']';
        $options = isset($options[$args['section']]) ? $options[$args['section']] : array();
    }

    $post_types = get_post_types(array('public' => true), 'objects', 'and');
    if(!empty($post_types)) {
        $used_labels = array();
        foreach($post_types as $key => $value) {
            echo "<label for='novashare" . (!empty($args['section']) ? "-" . $args['section'] : "") . "-post-type-" . $key . "' style='margin-right: 10px;'>";
                echo "<input type='checkbox' name='" . $option . "[" . $args['id'] . "][]' id='novashare" . (!empty($args['section']) ? "-" . $args['section'] : "") . "-post-type-" . $key . "' value='" . $key ."' ";
                    if(isset($options['post_types']) && is_array($options['post_types'])) {
                        if(in_array($key, $options['post_types'])) {
                            echo "checked";
                        }
                    }
                echo " />" . $value->label;
                if(in_array($value->label, $used_labels)) {
                    echo " (" . $value->name . ")";
                }
            echo "</label>";
            array_push($used_labels, $value->label);
        }
    }

    //tooltip
    if(!empty($args['tooltip'])) {
        novashare_tooltip($args['tooltip']);
    }
}

//print import settings
function novashare_print_import_settings($args) {
    
    //file upload + button
    echo "<input type='file' name='novashare_import_settings_file' /><br />";
    echo "<button id='import_settings' name='novashare_tools[import_settings]' value='1' class='button button-secondary'>" . __("Import Plugin Settings", 'novashare') . "</button>";
    
    //tooltip
    if(!empty($args['tooltip'])) {
        novashare_tooltip($args['tooltip']);
    }
}

//bitly groups
function novashare_bitly_groups() {

    $novashare = get_option('novashare');

    if(empty($novashare['bitly_groups'])) {
        return array();
    }

    return $novashare['bitly_groups'];
}

//print tooltip
function novashare_tooltip($tooltip) {

    if(!empty($tooltip)) {
        $tools = get_option('novashare_tools');
        echo "<span class='novashare-tooltip-text" . (!empty($tools['accessibility_mode']) ? "-am" : "") . "'>" . $tooltip . "<span class='novashare-tooltip-subtext'>" . sprintf(__("Click %s to view documentation.", 'novashare'), "<span class='novashare-tooltip-icon'>?</span>") . "</span></span>";
    }
}

//print title
function novashare_title($title, $id = false, $link = false) {
    if(!empty($title)) {

        $var = "<span class='novashare-title-wrapper'>";

            //label + title
            if(!empty($id)) {
                $var.= "<label for='" . $id . "'>" . $title . "</label>";
            }
            else {
                $var.= $title;
            }

            //tooltip icon + link
            if(!empty($link)) {
                $tools = get_option('novashare_tools');
                $var.= "<a" . (!empty($link) ? " href='" . $link . "'" : "") . " class='novashare-tooltip'" . (!empty($tools['accessibility_mode']) ? " title='" . __("View Documentation", 'novashare') . "'" : "") . " target='_blank'>?</a>";
            }

        $var.= "</span>";

        return $var;
    }
}

//sanitize options
function novashare_sanitize_options($values) {

    //textarea inputs with one per line
    if(!empty($values['pinterest']['excluded_images'])) {
        novashare_sanitize_one_per_line($values['pinterest']['excluded_images']);
    }

    return $values;
}

//sanitize one per line text field
function novashare_sanitize_one_per_line(&$value) {
    if(!is_array($value)) {
        $text = trim($value);
        $text_array = explode("\n", $text);
        $text_array = array_filter(array_map('trim', $text_array));
        $value = $text_array;
    }
}