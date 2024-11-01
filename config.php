<?php

function sb_plugin_admin_init(){
	global $sb_white_label;
	register_setting('sb_plugin_options', 'sb_plugin_options', '' );	
	
	add_settings_section('sb_server_settings', $sb_white_label['name'].' server settings', 'sb_blank_intro', 'sb_plugin');
		add_settings_field('sb_server_address', $sb_white_label['name'].' server address', 'sb_server_address_string', 'sb_plugin', 'sb_server_settings');
		
	add_settings_section('sb_page_settings', 'Page settings', 'sb_page_intro', 'sb_plugin');
		add_settings_field('sb_search_page', $sb_white_label['name'].' search page ID', 'sb_search_page_string', 'sb_plugin', 'sb_page_settings');
		add_settings_field('sb_listing_page', $sb_white_label['name'].' listing page ID', 'sb_listing_page_string', 'sb_plugin', 'sb_page_settings');
	
	add_settings_section('sb_currency_settings', 'Price settings', 'sb_blank_intro', 'sb_plugin');
		add_settings_field('sb_currency_1', 'Display currency', 'sb_currency_1_string', 'sb_plugin', 'sb_currency_settings');
		add_settings_field('sb_zero_value', 'Zero-value display', 'sb_zero_value_string', 'sb_plugin', 'sb_currency_settings');
	
	add_settings_section('sb_css_settings', 'Custom CSS settings', 'sb_blank_intro', 'sb_plugin');
		add_settings_field('sb_css', 'Custom CSS', 'sb_css_string', 'sb_plugin', 'sb_css_settings');
	}
function sb_blank_intro() {
	echo '';
	}
	
function sb_page_intro() {
	global $sb_white_label;
	echo "<p>".$sb_white_label['name']." creates two pages on your WordPress installation, which it uses to find and display listing information:</p><ol><li>A search page</li><li>The listing page (where listings details are displayed)</li></ol>
	These pages include the shortcodes <strong>[sb_search_page]</strong> and <strong>[sb_listing_page]</strong> respectively.</p>
	<p>These pages are automatically created during Plugin installation, but if you change them, you'll need to update these ID values.</p>
	<p><a href='http://www.youtube.com/watch?v=fLg2T1AvmFE' target='_blank'>A quick video on how to find page (or post) ID numbers.</a></p>";
	}
	
function sb_server_address_string() {
	global $sb_config, $sb_white_label;
	echo "<input id='server_address' name='sb_plugin_options[server_address]' size='40' type='text' value='".$sb_config['server_address']."' />
	<p>e.g. <i>https://mybrokerage.example.com</i> - always include the <i>'https://'</i> part.</p>
	<p>Your ".$sb_white_label['name']." server is the where all your listing data is held. It's also where you go to add, edit and delete your listings.</p>
	<p>Will default to <em>".$sb_white_label['default_server']."</em> if left blank.</p>";
	}
	
function sb_search_page_string() {
	global $sb_config, $sb_white_label;
	echo "<input id='sb_search_page' name='sb_plugin_options[search_page]' size='10' type='text' value='".$sb_config['search_page']."' />
	<p>This is the id number of the WordPress page that contains the shortcode [".$sb_white_label['sc_prefix']."search_page].</p>";
	}


function sb_listing_page_string() {
	global $sb_config, $sb_white_label;
	echo "<input id='sb_listing_page' name='sb_plugin_options[listing_page]' size='10' type='text' value='".$sb_config['listing_page']."' />
	<p>This is the id number of the WordPress page that contains the shortcode [".$sb_white_label['sc_prefix']."listing].</p>";
	}


function sb_currency_1_string() {
	global $sb_config, $sb_white_label;
	echo "<input id='currency_1' name='sb_plugin_options[currency_1]' size='10' type='text' value='".$sb_config['currency_1']."' />
	<p>3-letter code indicating the currency for the plug-in to run - a currency conversion will be included for any listings not in this currency.<br/>
	To see available currencies, log in to your ".$sb_white_label['name']." system and go to 'Taxonomy' > 'Currencies' - any live currency can be used.<br/><br/>
	Will default to <strong>EUR</strong> (Euros) if left blank.</p>";
	}

function sb_zero_value_string() {
	global $sb_config, $sb_white_label;
	echo "<input id='zero_value' name='sb_plugin_options[zero_value]' type='text' value='".$sb_config['zero_value']."' size='40'/>
	<p>Text to use if the listing price is zero  - common examples: 'Price on application', 'Price: please enquire' etc.<br/>
	If left blank, will display zero price without substitution.</p>";
	}
	
function sb_css_string() {
	global $sb_config, $sb_white_label;
	echo "<textarea id='sb_css' name='sb_plugin_options[css]' cols='100' rows='5'>$sb_config[css]</textarea>
	<p>Add CSS to the <i>div.".$sb_white_label['sc_prefix']."wrapper</i> element to style only ".$sb_white_label['name']." elements.</p>
	<p>Common examples:</p>
	<p><code>.sb_year_message {display: none;}<br/>
	.sb_lying_message {display: none;}<br/>
	.sb_price_message {display: none;}</code></p>
	<p>will completely hide the build year, location and price data for search results and listings.</p>
	";
	}
	
function sb_plugin_options() {
	global $sb_white_label;
	if (!current_user_can('manage_options'))  {
		wp_die( __('You do not have sufficient permissions to access this page.') );
	}
	echo '<div class="wrap">';
	echo "<img src='".plugins_url('images/'.$sb_white_label['logo_url'], __FILE__)."'
	alt='".$sb_white_label['name']."' style='padding: 10px;' />";
	echo "<h3>".$sb_white_label['name']." is a web-based sales listing tool. It can be used to sell anything from boats to houses.</h3>
	<p>To use this ".$sb_white_label['name']." plugin with real data from real listings, you'll need an account with us - sign up at
	<a href='".$sb_white_label['sales_site']."'>".$sb_white_label['sales_site']."</a>.</p>
	<p>If this is your first ".$sb_white_label['name']." installation, we strongly recommend you read the <a href='".$sb_white_label['install_guide']."' target='_blank'>Installation Guide</a>.";
	echo '<form method="post" action="options.php"> ';
	settings_fields('sb_plugin_options');
	do_settings_sections('sb_plugin');
	echo "<input name='Submit' type='submit' value='Save Changes'/></form>";
	echo '</div>';
}
?>