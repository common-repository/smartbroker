<?php

function build_photo($link, $path, $desc, $boat_id, $xml, $vr_type, $new_thumb_layout) {
        global $sb_config;
		$primary_path = "/images/boats/$boat_id/large/".$xml->boat->primary_photo.".jpg";
		if ($link != $primary_path) {
			$a = '';
			if ($new_thumb_layout) {
				$a  = 'sb_new_thumb';
				}
			return "<div class='sb_clean_thumb $a'>
				<a href='".$sb_config['server_address'].$link."?vr=".$vr_type."' rel='sb_prettyPhoto[all]' title='".$desc."'>
					<img src='".$sb_config['server_address'].$path."?vr=".$vr_type."' alt='' class='sb_vr_".$vr_type."_small'/>
				</a>
			</div>";
			}
		return '';
        }
		
function build_video($link, $path, $desc, $boat_id, $xml, $vr_type, $new_thumb_layout) {
        global $sb_config;
		$a = '';
		if ($new_thumb_layout) {
			$a  = 'sb_new_thumb';
			}
		return "<div class='sb_clean_thumb sb_clean_thumb_video $a'>
			<a href='https://img.youtube.com/vi/$link/default.jpg' rel='sb_prettyPhoto[all]' title='".$desc."'>
				<img src='https://img.youtube.com/vi/$link/default.jpg' alt='' class='sb_video_small'/>
			</a>
		</div>";
        }

function currency_conversion ($value, $currency) {
	global $sb_config;
	if ($currency != $sb_config['currency_1'])
		{
		//this price is not in base currency - convert now
		//1: convert to Euros
		$euro_price = $value / $sb_config['currencies'][strval($currency)]['rate'];
		//2: convert to $sb_config['currency_1']
		$new_price = $euro_price * $sb_config['currencies'][$sb_config['currency_1']]['rate'];
		$new_price = number_format(round($new_price,-2));
		
		$symbol = $sb_config['currencies'][$sb_config['currency_1']]['symbol'];
		if ($sb_config['currencies'][$sb_config['currency_1']]['suffix']) {
			return '(~ '.$new_price.'&nbsp;'.$symbol.')';
			} else {
			return '(~ '.$symbol.$new_price.')';
			}
		}
	return '';
	}
		
		
function make_date_dropdown($year_min, $year_max) {
	$year_min = intval($year_min);
	$year_max = intval($year_max);
	
	$years = array();
	$current_year = date('Y');
	
	if ($current_year - $year_min < 15) {
		//just sequential numbering
		while ($current_year >= $year_min) {
			$years[] = $current_year;
			$current_year--;
			}
		}
	else {
		//need to split into groups
		$n = 1;
		while (($n < 5) OR ($current_year % 4 !== 0)) { //count at least 5 single years back to nearest power of four
			if ($current_year + 1 > $year_min) {$years[] = $current_year;}
			$current_year--;
			$n++;
			}
		$n = 1;
		while (($n < 6) OR ($current_year % 20 !== 0)) { //count at least 6 2-yr periods years back to nearest power of twenty
			if ($current_year + 2 > $year_min) {$years[] = $current_year;}
			$current_year = $current_year - 2;
			$n++;
			}
		$n = 1;
		while (($n < 3) OR ($current_year % 50 !== 0)) { //count at least 5 10-yr periods years back to nearest fifty
			if ($current_year + 10 > $year_min) {$years[] = $current_year;}
			$current_year = $current_year - 10;
			$n++;
			}
		while ($current_year > 1500) { //count back in fifty yr periods until done
			if ($current_year + 50 > $year_min) {$years[] = $current_year;}
			$current_year = $current_year - 50;
			$n++;
			}
		}
	$a =  "<select name='bt'><option value=''>Show all</option>";
	foreach($years as $y) {
		$s = '';
		if (array_key_exists('bt', $_GET) AND ($_GET['bt'] == $y)) {$s = "selected='selected'";}
		$a .= "<option value='".$y."' $s>".$y."</option>";
		}
	$a .= "</select>";
	return $a;
	}
	
function hide_listing_page() {
	global $sb_config;
	echo "<style>
	.page-item-".$sb_config['listing_page']." {display: none !important;}
	.post-".$sb_config['listing_page']." .entry-header {display: none;}
	body.page-id-".$sb_config['listing_page']." .recent-posts {display: none;}
	body.page-id-".$sb_config['search_page']." .recent-posts {display: none;}
	#post-".$sb_config['search_page']." header {display: none;}
	body.page-id-".$sb_config['search_page']." #secondary {display: none;}
	</style>";
	}
	
function add_smartbroker_custom_css () {
	global $sb_config;
	echo "<style>\r\n/*Added by SmartBroker plugin: go to Admin -> Settings -> SmartBroker to edit this text*/\r\n";
	echo $sb_config['css'];
	echo "</style>";
	}

	

?>