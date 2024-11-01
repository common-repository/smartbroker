<?php
// flush_rules() if our rules are not yet included
function sb_flush_rules(){
	global $sb_config;
	$rules = get_option('rewrite_rules');
	$redirect_url =  'index.php?page_id='.$sb_config['listing_page'].'&boat_id=$matches[1]&server_address=https%3A%2F%2F$matches[2].smart-broker.co.uk';
	$key = 'boats-for-sale/([0-9]+)/(\w+)/(\S+)';
	if (!is_array($rules) OR !array_key_exists($key, $rules) OR ($rules[$key] != $redirect_url)) {
		global $wp_rewrite;
	   	$wp_rewrite->flush_rules();
		}
}

// Adding a new rule
function sb_insert_rewrite_rules( $rules )
{
	global $sb_config;
	$newrules = array();
	$newrules['boats-for-sale/([0-9]+)/(\w+)/(\S+)'] = 'index.php?page_id='.$sb_config['listing_page'].'&boat_id=$matches[1]&server_address=https%3A%2F%2F$matches[2].smart-broker.co.uk';
	//echo $newrules['boats-for-sale/([0-9]+)/(\w+)']; exit();
	return $newrules + $rules;
}

// Adding the id var so that WP recognizes it
function sb_insert_query_vars( $vars )
{
    array_push($vars, 'boat_id');
    array_push($vars, 'server_address');
    return $vars;
}
?>