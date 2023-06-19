<?php
echo '<div class="novashare-settings-section">';
	
	//page title
	echo '<h2><span class="dashicons dashicons-editor-help"></span>' . __("Support", "novashare") . '</h2>';
	echo '<div style="margin-bottom: 20px;"></div>'; //spacer

	//documentation
	echo '<h2>' . __('Documentation', 'novashare') . '</h2>';
	echo '<div class="form-table">';
		echo '<div style="margin: 1em auto;">' . __('Need help? Check out our in-depth documentation. Every feature has a step-by-step walkthrough.', 'novashare') . '</div>';
		echo '<a class="button-secondary" href="https://novashare.io/docs/?utm_source=novashare&utm_medium=support-page&utm_campaign=documentation-cta" target="_blank">' . __('Documentation', 'novashare') . '</a>';
	echo '</div>';
echo '</div>';

//contact us
echo '<div class="novashare-settings-section">';
	echo '<h2>' . __('Contact Us', 'novashare') . '</h2>';
	echo '<div class="form-table">';
		echo '<div style="margin: 1em auto;">' . __('If you have questions or problems, please send us a message. We’ll get back to you as soon as possible.', 'novashare') . '</div>';
		echo '<a class="button-secondary" href="https://novashare.io/contact/?utm_source=novashare&utm_medium=support-page&utm_campaign=contact-us-cta" target="_blank">' . __('Contact Us', 'novashare') . '</a>';
	echo '</div>';
echo '</div>';

//faq
echo '<div class="novashare-settings-section">';
	echo '<h2>' . __('Frequently Asked Questions', 'novashare') . '</h2>';
	echo '<div class="form-table" style="display: inline-flex; flex-wrap: wrap;">';
		$faq_utm = '?utm_source=novashare&utm_medium=support-page&utm_campaign=faq';
		echo '<ul style="margin-right: 40px;">';	
			echo '<li><a href="https://novashare.io/docs/how-to-install-novashare/' . $faq_utm . '" target="_blank">' . __('How do I license activate the plugin?', 'novashare') . '</a></li>';
			echo '<li><a href="https://novashare.io/docs/update-novashare-plugin/' . $faq_utm . '" target="_blank">' . __('How do I update the plugin?', 'novashare') . '</a></li>';
			echo '<li><a href="https://novashare.io/docs/upgrade-license/' . $faq_utm . '" target="_blank">' . __('How do I upgrade my license?', 'novashare') . '</a></li>';
			echo '<li><a href="https://novashare.io/docs/changelog/' . $faq_utm . '" target="_blank">' . __('Where can I view the changelog?', 'novashare') . '</a></li>';
			echo '<li><a href="https://novashare.io/affiliate-program/' . $faq_utm . '" target="_blank">' . __('Where can I sign up for the affiliate program?', 'novashare') . '</a></li>';
		echo '</ul>';
		echo '<ul>';
			echo '<li><a href="https://novashare.io/docs/inline-content-share-buttons/' . $faq_utm . '" target="_blank">' . __('How do I enable inline share buttons?', 'novashare') . '</a></li>';
			echo '<li><a href="https://novashare.io/docs/floating-bar-share-buttons/' . $faq_utm . '" target="_blank">' . __('How do I enable floating bar share buttons?', 'novashare') . '</a></li>';
			echo '<li><a href="https://novashare.io/docs/enable-share-counts/' . $faq_utm . '" target="_blank">' . __('How do I enable share counts (total + network)?', 'novashare') . '</a></li>';
			echo '<li><a href="https://novashare.io/docs/click-to-tweet/' . $faq_utm . '" target="_blank">' . __('How do I customize Click to Tweet settings?', 'novashare') . '</a></li>';
			echo '<li><a href="https://novashare.io/docs/pinterest-image-hover-pins/' . $faq_utm . '" target="_blank">' . __('How do I enable Pinterest image hover pins?', 'novashare') . '</a></li>';
		echo '</ul>';
	echo '</div>';
echo '</div>';