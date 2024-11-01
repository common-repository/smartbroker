<?php
function sb_featured_func($atts) {
	global $sb_config;
	$a = '<!-- The Gallery as inline carousel, can be positioned anywhere on the page -->
		<div id="sb_featured_carousel">
		<div id="sb_featured_slide_holder">
			<a id="sb_featured_slide_1"><p></p></a>
			<a id="sb_featured_slide_2"><p></p></a>
		</div>
		</div>
		';
	$path = $sb_config['server_address'].'/wp_feed/featured';
	$xml_data = sb_load_xml($path);
	$a .= "<div id='sb_links'>";
	$n = 1;
	foreach ($xml_data['data']->boat as $b) {
		$desc = $b->builder.' '.$b->model.", ";
		if ($b->price == 0) {
			$desc .= $sb_config['zero_value'];
			}
			elseif ($b->suffix === true) {
			$desc .= number_format(intval($b->price),0).'&nbsp;'.$b->symbol;
			} else {
			$desc .= $b->symbol.number_format(intval($b->price),0);
			}
		if ($b->price != 0) {
			if (isset($b->tax_message)) {
				$desc .= ' '.$b->tax_message;
				} else {
				if ($b->vat_paid == true) {
					$desc .= ' '.$b->tax_label.' '.__('paid','smartbroker');			
					} else {
					$desc .= ' '.$b->tax_label.' '.__('not paid','smartbroker');	
					}
				}
			}
	
		$a .= "<a href='".$sb_config['server_address']."/images/boats/".$b->boat_id."/large/".$b->photo_id.".jpg' data-description='$desc'
		data-link='/?page_id=$sb_config[listing_page]&boat_id=".$b->boat_id."'></a>";
	
		$n++;
		}
	$a .= "</div>";
	echo $a;
	}
?>