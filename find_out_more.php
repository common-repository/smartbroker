<?php
if (is_user_logged_in() OR ($sb_config['sb_tracking'] != 'on')) {
	$sb_temp_config= get_option('sb_plugin_options');
	$npl_special = "";
	if (strpos($sb_temp_config['server_address'],"npl.smart-broker.co.uk") != FALSE) {
		$npl_special = "<p>City*:</br><input name='city' size='15' required type='text'></input></p>";
		$npl_special .= "<p>State*:<br/><select name='state' required>
		<option value='AL'>Alabama</option>
		<option value='AK'>Alaska</option>
		<option value='AS'>American Samoa</option>
		<option value='AZ'>Arizona</option>
		<option value='AR'>Arkansas</option>
		<option value='CA'>California</option>
		<option value='CO'>Colorado</option>
		<option value='CT'>Connecticut</option>
		<option value='DE'>Delaware</option>
		<option value='DC'>District Of Columbia</option>
		<option value='FL'>Florida</option>
		<option value='GA'>Georgia</option>
		<option value='GU'>Guam</option>
		<option value='HI'>Hawaii</option>
		<option value='ID'>Idaho</option>
		<option value='IL'>Illinois</option>
		<option value='IN'>Indiana</option>
		<option value='IA'>Iowa</option>
		<option value='KS'>Kansas</option>
		<option value='KY'>Kentucky</option>
		<option value='LA'>Louisiana</option>
		<option value='ME'>Maine</option>
		<option value='MD'>Maryland</option>
		<option value='MA'>Massachusetts</option>
		<option value='MI'>Michigan</option>
		<option value='MN'>Minnesota</option>
		<option value='MP'>Northern Mariana Islands</option>
		<option value='MS'>Mississippi</option>
		<option value='MO'>Missouri</option>
		<option value='MT'>Montana</option>
		<option value='NE'>Nebraska</option>
		<option value='NV'>Nevada</option>
		<option value='NH'>New Hampshire</option>
		<option value='NJ'>New Jersey</option>
		<option value='NM'>New Mexico</option>
		<option value='NY'>New York</option>
		<option value='NC'>North Carolina</option>
		<option value='ND'>North Dakota</option>
		<option value='OH'>Ohio</option>
		<option value='OK'>Oklahoma</option>
		<option value='OR'>Oregon</option>
		<option value='PA'>Pennsylvania</option>
		<option value='PR'>Puerto Rico</option>
		<option value='RI'>Rhode Island</option>
		<option value='SC'>South Carolina</option>
		<option value='SD'>South Dakota</option>
		<option value='TN'>Tennessee</option>
		<option value='TX'>Texas</option>
		<option value='UM'>United States Minor Outlying Islands</option>
		<option value='UT'>Utah</option>
		<option value='VT'>Vermont</option>
		<option value='VA'>Virginia</option>
		<option value='VI'>Virgin Islands</option>
		<option value='WA'>Washington</option>
		<option value='WV'>West Virginia</option>
		<option value='WI'>Wisconsin</option>
		<option value='WY'>Wyoming</option>
		<option value='NOT US'>(not in USA)</option></select>";
		}
	
	if (array_key_exists('boat_id', $_GET)) {
		$boat_id =  (int) $_GET['boat_id'];
		} else {
		$boat_id = (int) get_query_var('boat_id'); //used if there's a rewrite in operation
		}
	
	$find_out_more = "
	<p>".nl2br($xml->config->find_out_more_form_intro)."</p>
	<hr />
	
	<form action='".$sb_temp_config['server_address']."/system/wp_plugin/wp_plugin_enquire.php' method='post'  id='sb_find_out_more'>
	<p>Your name*:<br/>
	<input type='text' name='name' value='$user_identity' size='14' required/></p>
	
	<div id='hpt'>
	<p>Please leave empty<br/>
	<input type='email' name='email_address' /></p>
	</div>
	
	<p>".__('Your email address:','smartbroker')."*<br />
	<input type='email' name='cwr' value='$user_email' size='19' required/></p>

	<p>".__('Phone number:','smartbroker')."*<br />
	<input type='tel' name='phone' size='15' required/></p>
	
	<p>".__('Preferred contact method:','smartbroker')."<br/>
	<input type='radio' name='contact_method' value='phone' checked='checked' />&nbsp;".__('Phone','smartbroker')."<br />
	<input type='radio' name='contact_method' value='email' />&nbsp;".__('Email','smartbroker')."</p>".
	$npl_special
	."<p>".__('Notes:','smartbroker')."<br />
	<textarea name='notes' rows='5' cols='30'></textarea></p>

	<input type='hidden' name='boat_id' value='$boat_id' />
	<input type='hidden' name='server' value='".get_query_var('server')."' />
	<input type='hidden' name='desc' value='".$xml->boat->builder." ".$xml->boat->model."' />
	<input type='hidden' name='admin_email' value='".$xml->config->email."' />
	<input type='hidden' name='path' value='http://".$_SERVER['SERVER_NAME']."/?page_id=".$sb_config['listing_page']."' />
	
	<button style='position: relative;' type='submit'>".__('Send enquiry','smartbroker')."</button><br/><br/>
	
	</form>";
	$_GET = stripslashes_deep($_GET);
	if (array_key_exists('msg',$_GET)) {
		$m = $_GET['msg'];
		if (array_key_exists('state',$_GET)) {
			$s = $_GET['state'];
			} else {
			$s = '';
			}
		$find_out_more .= "<div id='sb_response_msg'>
		<p><strong>$m</strong></p></div>";
		}
	} else {
	$find_out_more = "<p>";
	$llnk = wp_login_url(get_permalink()."&boat_id=".$_GET['boat_id']);
	$find_out_more .= sprintf(__("To find out more about this yacht, please <a href='/wp-register.php' title='Register'>register</a> or 
	<a href='%s' title='Login'>log in</a>.<br/>Registration is free and takes seconds.",'smartbroker'), $llnk);
	$find_out_more .= "</p><p>";
	$find_out_more .= sprintf(__("Don't forget you can call us on %s anytime for a chat.",'smartbroker'),$xml->config->phone);
	}
	
?>