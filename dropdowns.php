<?php
function create_country_dropdown($xml) {
	$a =  "<select name='cn'><option value=''>Any country</option>";
	foreach ($xml->countries->country as $country) {
		$s = '';
		$code = strtolower($country->code);
		if (array_key_exists('cn', $_GET) AND ($_GET['cn'] == $code)) {
			$s = "selected='selected'";
			}
		$a .= "<option value='".$code."' $s>".$country->name."</option>";
		}
	$a .= "</select>";
	return $a;
	}
function create_type_dropdown($xml) {
	$sail_s = '';
	$power_s = '';
	if (array_key_exists('tp', $_GET) AND ($_GET['tp'] == 's')) {$sail_s = "selected='selected'";}
	if (array_key_exists('tp', $_GET) AND ($_GET['tp'] == 'p')) {$power_s = "selected='selected'";}
	$a =  "<select name='tp'>";
	$a .= "<option value='a'>All boat types</option>";
	
	$sail_group = "<optgroup label='Sail'>";
	$sail_group .= "<option value='s' $sail_s>All sail boats</option>";
	
	$power_group = "<optgroup label='Power'>";
	$power_group .= "<option value='p' $power_s>All power boats</option>";
	foreach ($xml->boat_types->type as $type) {
		$s = '';
		$code = $type->id;
		$group = $type->om_type;
		if (array_key_exists('tp', $_GET) AND ($_GET['tp'] == $code)) {
			$s = "selected='selected'";
			}
		if ($group == 'sail') {
			$sail_group .= "<option value='".$type->id."' title='".$type->notes."' $s>".$type->type."</option>";
			} else {
			$power_group .= "<option value='".$type->id."' title='".$type->notes."' $s>".$type->type."</option>";
			}
		}
	$sail_group .= "</optgroup>";
	$power_group .= "</optgroup>";
	$a .= $sail_group.$power_group."</select>";
	return $a;
	}
function create_builder_dropdown($xml) {
	$a =  "<select name='bd'><option value=''>Any builder</option>";
	foreach ($xml->builders->builder as $builder) {
		$s = '';
		$code = $builder;
		if (array_key_exists('bd', $_GET) AND ($_GET['bd'] == $builder)) {
			$s = "selected='selected'";
			}
		$a .= "<option value='".$builder."' $s>".$builder."</option>";
		}
	$a .= "</select>";
	return $a;
	}

function create_built_after_dropdown() {
	$a =  "<select name='bt'><option value=''>Show all</option>";
	$n = date('Y', time()) - 1;
	while ($n >= 1970) {
		$s = '';
		if ($_GET['bt'] == $n) {
			$s = "selected='selected'";
			}
		$a .= "<option value='".$n."' $s>".$n."</option>";
		$n--;
		}
	$a .= "</select>";
	return $a;
	}
	

?>