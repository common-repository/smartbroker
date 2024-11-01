<?php

function sb_search_page_func($atts) {
	global $sb_config, $post, $size_low, $size_high, $price_low, $price_high;
	if (is_array($atts) AND array_key_exists('server_override', $atts)) {
		$sb_config['server_address'] = $atts['server_override'];
		unset($atts['server_override']);
		}
	//add search box
	$a = "<div class='sb_wrapper'>
			<div class='smartbroker_section smartbroker_group'>
			<div class='smartbroker_col smartbroker_span_1_of_3'>";
	
	$a .= sb_search_box_func($atts, get_the_ID());
	
	$a .= '</div>';
	$a .= "<div class='smartbroker_col smartbroker_span_2_of_3'>";
	
	if (is_array($atts) AND array_key_exists('parent_type', $atts)) {
		$sb_config['data']['pt'] = (int) $atts['parent_type'];
		}
	
		//print_r($sb_config['data']);
		$xml_data = load_results_xml($sb_config['data']);
		echo $xml_data['narrative'];
		if ($xml_data['result'] !== FALSE) {
			$xml = $xml_data['data'];
			$total_rows = $xml['count'];
			$returned_rows = count($xml);
			$requested_rows = $xml['requested_rows'];
			$start_row = $xml['start'];
			$end_row = $start_row + $returned_rows - 1;
			
			if ($total_rows > 0) {
				$results_string = sprintf(__('%s results found - showing&nbsp;results&nbsp;%s&nbsp;to&nbsp;%s.','smartbroker'),$total_rows, $start_row, $end_row);
				$a .=  "&nbsp;$results_string<div id='results'>";
				foreach ($xml->boat as $boat) {
					$a .=  search_result_item($boat);
					}
				$a .=  pagination_links($total_rows,$start_row,$requested_rows);
				} else {
				$a .=  "<div id='results'>";
				$a .=  blank_slate_row();
				}
			
			if (empty($sb_config['currency_2'])){
				$sb_config['currency_2'] = 'EUR';
				$sb_config['currencies']['EUR']['symbol'] = "&euro;";
				$sb_config['currencies']['EUR']['rate'] = 1;
				}	
			//data required by javascript
			$a .= "<div style='display: none;' id='sb_server_address'>".$sb_config['server_address']."</div>\r\n";
			$a .= "<div style='display: none;' id='sb_listing_page'>".$sb_config['listing_page']."</div>\r\n";
			$a .= "<div style='display: none;' id='sb_currency_1'>".$sb_config['currency_1']."</div>\r\n";
			$a .= "<div style='display: none;' id='sb_currency_1_symbol'>".$sb_config['currencies'][$sb_config['currency_1']]['symbol']."</div>\r\n";
			$a .= "<div style='display: none;' id='sb_currency_2'>".$sb_config['currency_2']."</div>\r\n";
			$a .= "<div style='display: none;' id='sb_currency_2_symbol'>".$sb_config['currencies'][$sb_config['currency_2']]['symbol']."</div>\r\n";
			$a .= "<div style='display: none;' id='sb_curr_2_rate'>".$sb_config['currencies'][$sb_config['currency_2']]['rate'] / $sb_config['currencies'][$sb_config['currency_1']]['rate']."</div>\r\n";
			
			

			}
		$a .=  "</div><!-- end of #results -->\r\n"; //end span 2_of_3
		$a .=  "</div><!-- end of smartbroker_col span_2_of_3 -->\r\n"; //end row
		$a .=  "</div><!-- end of sb_section -->\r\n"; //end sb_section
		$a .=  "</div><!-- end of sb_wrapper -->\r\n"; //end sb_wrapper
			
	
	return $a;
	}
	
function blank_slate_row() {
	global $sb_config;
	if (array_key_exists('pt',$sb_config['data'])) {
		return "No listings found"; //don't link to view all if only filtering by parent category
		} else {
		return "No listings found - <a href='?page_id=$sb_config[search_page]'>view all </a>";
		}
	}
	
function search_result_item($boat) {
		global $sb_config;
		
		
		$desc = $boat->builder." ".$boat->model;
		
		if (isset($boat->vat_message))  {
			$vat_message = $boat->vat_message;
			} else {
			if ($boat->vat_paid == '1') {
				$vat_message = "VAT ".__('paid','smartbroker');}
				else {
				$vat_message = "VAT ".__('not paid','smartbroker');
				}
			}
			
		$status_message = '';
		$featured = false;
		if (($boat->status != '') AND ($boat->add_label == '1')){	
			$status_message = " <span class='sb_status_message sb_status_".strtolower(str_replace(' ','_',$boat->status))."'>".$boat->status.'</span>';
			}
		
		if ($boat->status == 'Featured') {
			$featured = true;
			}
		if (!array_key_exists('listing_default_tab',$sb_config)) {
		$sb_config['listing_default_tab'] = '';
		}
		
		//if ( get_option('permalink_structure') ) {
		if (false) {
			//use pretty links
			//get subdomain of server address
			$elements = explode('.',$sb_config['server_address']);
			$server_domain_elements = explode('//', $elements[0]);
			$server_subdomain = $server_domain_elements[1];
			$link = site_url('/boats-for-sale/'.$boat->boat_id.'/'.urlencode($server_subdomain).'/'.urlencode($desc));
			} else {
			//no pretty links, boo!
			$link = site_url("/?page_id=".$sb_config['listing_page']."&boat_id=".$boat->boat_id."&server=".urlencode($sb_config['server_address']).'#'.$sb_config['listing_default_tab']);
			}
		
		$length = round($boat->LOA).' '.$sb_config['units']['LOA'];
		
		//format currency
		$currency = $boat->currency;
		$curr_symbol = $sb_config['currencies'][strval($currency)]['symbol'];
		
		$price = number_format(floatval($boat->price));
		$currency_conversion = currency_conversion(floatval($boat->price), $currency);
		if ($currency_conversion != '') {
			$currency_conversion = "<small>$currency_conversion</small>";
			}
		
		if ($sb_config['currencies'][strval($currency)]['suffix']) {
			$price_message = $price.$curr_symbol.' '.$currency_conversion.' '.$vat_message;
			} else {
			$price_message = $curr_symbol.$price.' '.$currency_conversion.' '.$vat_message;
			}
		
		//junk all this is value is zero, and use replace text instead
		if (($price == 0) AND array_key_exists('zero_value',$sb_config) AND ($sb_config['zero_value'] != '')) {
			$price_message = $sb_config['zero_value'];
			}
		
		if ($featured) {
			$img_link = $sb_config['server_address']."/images/boats/".$boat->boat_id.'/medium/'.$boat->photo_id.".jpg";
				$a = "
			<div class='sb_listing_item sb_clearfix' style='clear: both; margin-bottom: .2em;'>
				
				<a href='$link' style='display: block; float: left; margin-right: 1em;' class='sb_img_featured'><img src='$img_link' alt='$desc' title='$desc'></a>
				<div style='padding-left: .5em; display: block;'>
					<h3 style='clear: right; margin-top: .5em;'><a href='$link'>$desc</a></h3>
					
					<p>$length<span class='sb_year_message'>, ".__('built','smartbroker')." $boat->year</span><span class='sb_lying_message'>, ".__('lying','smartbroker')." ".trim($boat->region.' '.$boat->country)."</span><span class='sb_price_message'><br/>$price_message</span></p>
					
					<input type='button' value='View boat details' onclick='window.location=\"$link\"' style='margin-bottom: .5em;' class='sb_results_button_link_featured'/>
				</div>
					
			</div>";
			} else {
			$img_link = $sb_config['server_address']."/images/boats/".$boat->boat_id.'/medium/'.$boat->photo_id.".jpg";
			$a = "
			<div class='sb_listing_item sb_clearfix' style='clear: both;'>
				
				<a href='$link' style='display: block; float: left; margin-right: .5em; margin-top: .5em;' class='sb_img_standard'><img src='$img_link' alt='$desc' title='$desc'></a>
				<div style='display: inline-block;'>
				<h3 style='clear: right; margin-top: .5em;'><a href='$link'>$desc</a>$status_message</h3>
				
				<p>$length<span class='sb_year_message'>, ".__('built','smartbroker')." $boat->year</span><span class='sb_lying_message'>, ".__('lying','smartbroker')." ".
				trim($boat->region.' '.$boat->country)."</span><span class='sb_price_message'>, $price_message</span></p>
				<input type='button' value='View boat details' onclick='window.location=\"$link\"' style='margin-bottom: .5em;' class='sb_results_button_link_standard'/>
				</div>	
			</div>";
			}
		return $a;
		}
		
function sb_search_box_func($atts, $search_page = '') {
	global $sb_config, $post, $size_low, $size_high, $price_low, $price_high;
	extract( shortcode_atts(array(
			'size_low'=>'none',
			'size_high'=>'none',
			'price_low'=>'none',
			'price_high'=>'none',
			'results_per_page' => '10',
			'keyword_examples' => "e.g. roller furling, fridge",
			'layout' => '')
		, $atts ));
		
	if ($search_page == '')	{
		$search_page = $sb_config['search_page'];
		}

	$sb_config['data']['ln'] = $results_per_page;
	
	$fields_xml = load_fields_xml($atts);
	
	if ($fields_xml_data['result'] !== FALSE) {
		if (!function_exists("find_default")) {
			function find_default($id) {
				global $sb_config, $size_low, $size_high, $price_low, $price_high;
				$assoc1 = array('sl'=>'size_low','sh'=>'size_high','pl'=>'price_low','ph'=>'price_high');
				//first see if we have a $_GET value
				if (array_key_exists($id, $_GET) AND ($_GET[$id] != '')) {
					//echo "Find default $id: using GET value: ".$_GET[$id]."<br/>";
					return intval($_GET[$id]);
					}
				//if not, see if there's a shortcode value supplied
				if ((isset(${$assoc1[$id]})) AND (${$assoc1[$id]} != '') AND (${$assoc1[$id]} != 0) AND (${$assoc1[$id]} != 'none')) {
					//echo "Find default $id: using sc value: ".var_dump(${$assoc1[$id]})."<br/>";
					return ${$assoc1[$id]};
					}
				//otherwise, get limit value
				$assoc2 = array('sl'=>'size_min','sh'=>'size_max','pl'=>'price_min','ph'=>'price_max');
				//echo "Find default $id: using limit value: ".var_dump(strval($sb_config[$assoc2[$id]]))."<br/>";
				return strval($sb_config[$assoc2[$id]]);
				}
			}
				
		$sb_config['data']['sl'] = find_default('sl');
		$sb_config['data']['sh'] = find_default('sh');	
		$sb_config['data']['pl'] = find_default('pl');	
		$sb_config['data']['ph'] = find_default('ph');	

		$a = '';
	
		//avoid values outside range
		if (intval($sb_config['data']['pl']) < intval($sb_config['price_min'])) {$sb_config['data']['pl'] = $sb_config['price_min'];}
		if (intval($sb_config['data']['pl']) > intval($sb_config['price_max'])) {$sb_config['data']['pl'] = $sb_config['price_min'];}
		if (intval($sb_config['data']['ph']) < intval($sb_config['price_min'])) {$sb_config['data']['ph'] = $sb_config['price_max'];}
		if (intval($sb_config['data']['ph']) > intval($sb_config['price_max'])) {$sb_config['data']['ph'] = $sb_config['price_max'];}
		if (intval($sb_config['data']['sl']) < intval($sb_config['size_min'])) {$sb_config['data']['sl'] = $sb_config['size_min'];}
		if (intval($sb_config['data']['sl']) > intval($sb_config['size_max'])) {$sb_config['data']['sl'] = $sb_config['size_min'];}
		if (intval($sb_config['data']['sh']) < intval($sb_config['size_min'])) {$sb_config['data']['sh'] = $sb_config['size_max'];}
		if (intval($sb_config['data']['sh']) > intval($sb_config['size_max'])) {$sb_config['data']['sh'] = $sb_config['size_max'];}

		//avoid max < min
		if ($sb_config['data']['pl'] > $sb_config['data']['ph']) {
			$temp = $sb_config['data']['ph'];
			$sb_config['data']['ph'] = $sb_config['data']['pl'];
			$sb_config['data']['pl'] = $temp;
			}
		if ($sb_config['data']['sl'] > $sb_config['data']['sh']) {
			$temp = $sb_config['data']['sh'];
			$sb_config['data']['sh'] = $sb_config['data']['sl'];
			$sb_config['data']['sl'] = $temp;
			}
			
		$sb_config['data']['cr'] = $sb_config['currency_1'];
		
		//start search box
		$a .=  "<form method='get' action='".site_url('/')."' id='boat_search_v2'>";	
				
		//first section - boat type
		$a .= "<p><input type='hidden' name='page_id' value='".$search_page."'>".__('Boat type:','smartbroker').'<br/>'.create_type_dropdown($fields_xml).'</p>';
		
		//second section - size slider
		$a .= '<p>'.__('Boat size:','smartbroker').'<br/>';
		$a .= "<input type='number' step='1' name='sl' value='".$sb_config['data']['sl']."' style='width: 5em;'> to 
		<input type='number' step='1' name='sh' value='".$sb_config['data']['sh']."' style='width: 5em;'>&nbsp;".$sb_config['units']['LOA']."</p>";
		
		//third section: price slider
		$a .='<p>'.__('Price:','smartbroker').'<br/>';
		if ($sb_config['currencies'][$sb_config['currency_1']]['suffix']) {
			$a .= "<input type='number' min='$sb_config[price_min]' max='$sb_config[price_max]' step='100'name='sl' value='".$sb_config['data']['pl']."'> to 
			<input type='number' min='$sb_config[price_min]' max='$sb_config[price_max]' step='100' name='sh' value='".$sb_config['data']['ph']."'>&nbsp;"
			.$sb_config['currencies'][$sb_config['currency_1']]['symbol']."</p>";
			} else {
			$a .= $sb_config['currencies'][$sb_config['currency_1']]['symbol']."&nbsp;<input type='number' min='$sb_config[price_min]' max='$sb_config[price_max]' step='100' name='pl' value='".$sb_config['data']['pl']."'>
			to <input type='number' min='$sb_config[price_min]' max='$sb_config[price_max]' step='100' name='ph' value='".$sb_config['data']['ph']."'></p>";
			}
			
		//forth: advanced search
		$lk = (empty($_GET['lk'])) ? '' : $_GET['lk'];
		$a .= "
		<div id='sb_advanced_options'>
			<hr/><p>".__('Keywords:','smartbroker')."<em><small> ".$keyword_examples."</small></em><br/>
			<input name='lk' value=\"".stripslashes($lk)."\" type='text' /></p>
			<p>".__('Builder:','smartbroker')."&nbsp;<span class='ui-icon ui-icon-info search_icon' title='Available makes are listed'>&nbsp;</span><br/>
			".create_builder_dropdown($fields_xml)."</p>
			<p>".__('Currently lying:','smartbroker')."&nbsp;<span class='ui-icon ui-icon-info search_icon' title='Boats are available in the countries listed.'>&nbsp;</span><br/>
			".create_country_dropdown($fields_xml)."</p>
			<p>".__('Built after:','smartbroker')."<br/>
			".make_date_dropdown(intval($sb_config['year_min']),intval($sb_config['year_max']))."</p>
		</div> <!-- End sb_advanced_options -->";
				
		
		//fifth: advanced and search button
		$a .= "<a href='#'><small id='sb_show_advanced'>Show advanced search options &#9660;</small></a><br/><br/>
		<input type='submit' value='".__('Search','smartbroker')."' />";
		
		$a .=  "<input type='hidden' name='ln' value='".intval($sb_config['data']['ln'])."'/>\r\n";
		$a .=  "<input type='hidden' name='cr' value='".$sb_config['currency_1']."'/>\r\n";
		$a .=  "<div style='display: none;' id='size_min'>".intval($sb_config['size_min'])."</div>\r\n";
		$a .=  "<div style='display: none;' id='size_max'>".intval($sb_config['size_max'])."</div>\r\n";
		$a .=  "<div style='display: none;' id='price_min'>".intval($sb_config['price_min'])."</div>\r\n";
		$a .=  "<div style='display: none;' id='price_max'>".intval($sb_config['price_max'])."</div>\r\n\r\n";
		
		$a .= "</form>";
		return $a;
		}
	return FALSE;
	}
	

?>