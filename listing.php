<?php

function sb_listing_func(){
	global $sb_config, $user_email, $user_identity, $current_user, $wp_query;
	
	include_once('utility_functions.php');
	$boat_id = '';
	//var_dump($wp_query->query_vars);
	
	$new_thumb_layout = true;
	//$new_thumb_layout = false;
	
	if (array_key_exists('boat_id', $_GET)) {
		$boat_id =  (int) $_GET['boat_id'];
		} else {
		$boat_id = (int) get_query_var('boat_id'); //used if there's a rewrite in operation
		}
if (array_key_exists('server', $_GET) AND ($_GET['server'] != '')) {
		$sb_config['server_address'] = urldecode($_GET['server']);
		} elseif (get_query_var('server_address','none') != 'none') {
		$sb_config['server_address'] = get_query_var('server_address'); //used if there's a rewrite in operation
		}
	
	$sb_config['video_link'] = '<div class="sb_clean_thumb" style="display: none;">
		<a href="https://www.youtube.com/watch?v=%1$s?rel=0&wmode=opaque&modestbranding=1&showinfo=0&theme=light" rel="sb_prettyPhoto[all]" title="Video: %2$s" style="text-decoration: none;">
			<div class="sb_clean_vt_holder">
				<span>&#9654;</span>
			</div>
		</a>
	</div>';
	
	//configure tracking, if required
	$tracking_code = '';
	if (is_user_logged_in() AND array_key_exists('sb_tracking',$sb_config)) {
			$tracking_code = "&track=true&a1=".urlencode($user_email).
			"&a2=".urlencode($current_user->display_name);
		}
		
	$site_id = explode(".",$sb_config['server_address']);
	$site_id = $site_id[0];
	$site_id = explode("/",$site_id);
	$site_id = end($site_id);

	$xml_file = $sb_config['server_address']."/system/wp_plugin/boat.php?boat_id=$boat_id";
	$xml_data = sb_load_xml($xml_file);
	
	echo $xml_data['narrative'];
	
	if ($xml_data['result'] !== FALSE) {

		//check if we're using a server other than the default, and substitute in the proper config node if required
		$sb_temp_config= get_option('sb_plugin_options');
		if ($sb_config['server_address'] != $sb_temp_config['server_address']) {
			$xml_file = $sb_temp_config['server_address']."/system/wp_plugin/boat.php?boat_id=0";
			$xml2 = sb_load_xml($xml_file);
			$domToChange = dom_import_simplexml($xml_data['data']->config);
			$domReplace  = dom_import_simplexml($xml2['data']->config);
			$nodeImport  = $domToChange->ownerDocument->importNode($domReplace, TRUE);
			$domToChange->parentNode->replaceChild($nodeImport, $domToChange);
			}
		
		
		//--------------------------------------------
		// Format price
		//--------------------------------------------
		//load exchange rates into sb_config
		foreach($xml_data['data']->currencies->currency as $c) {
			$sb_config['currencies'][strval($c->currency)] = array('rate' => floatval($c->rate), 'symbol' => strval($c->symbol), 'name' => strval($c->name), 'suffix'=>strval($c->suffix)); 
			}
		$currency = $xml_data['data']->boat->currency;
		$curr_symbol = $xml_data['data']->boat->symbol;
		$value = floatval($xml_data['data']->boat->asking_price);
		//Tax label
		$vat_paid = $xml_data['data']->boat->vat_included;
		if ($vat_paid == '1') {$vat_message = $xml_data['data']->config->tax_label." ".$xml_data['data']->config->tax_paid_label;}
		else {$vat_message = $xml_data['data']->config->tax_label." ".$xml_data['data']->config->tax_not_paid_label;}
		if (array_key_exists('hide_tax_label',$sb_config) AND ($sb_config['hide_tax_label'] == 'on')) {$vat_message = '';}
		if	($xml_data['data']->boat->suffix == 1) {
			$price = number_format($value,0).'&nbsp;'.$curr_symbol.' '.$vat_message;
			} else {
			$price = $curr_symbol.number_format($value,0).' '.$vat_message;
			}
		
		//add currency conversion if not in primary currency
		$currency_conversion = currency_conversion($value, $currency);
		
		//junk all this is value is zero, and use replace text instead
		if (($value == 0) AND array_key_exists('zero_value',$sb_config) AND ($sb_config['zero_value'] != '')) {
			$price = $sb_config['zero_value'];
			$currency_conversion = '';
			}
		
		//--------------------------------------------
		// Format provisional message
		//--------------------------------------------
		$prov = $xml_data['data']->boat->approved;
		$prov_message = '';
		if ($prov != 'true') {$prov_message = '<p><em>'.$xml_data['data']->config->provisional_listing_disclaimer.'</em></p>';}
		
		//--------------------------------------------
		// Compile specification categories
		//--------------------------------------------
		$cats = ''; $i = ''; $j = ''; $k = '';
		foreach($xml_data['data']->boat->specifications->categories->category as $category) {
			$e = "<hr/><h3>".$category->name."</h3><hr/><p>";
			foreach($category->item as $item) {
				$e .= $item->name;
				$note = $item->note; //note already includes units in XML feed
				if ($note != '') {$e .= ": ".$note;}
				$e.= "<br/>";
				}
			$e = substr($e,0,-5)."</p>";
			if ((strlen($i) > strlen($j)) AND (strlen($j) >= strlen($k))){$k .= $e;}
			elseif ((strlen($i) > strlen($k)) AND (strlen($k) >= strlen($j))) {$j .= $e;}
			else {$i .= $e;}
			}
		$cats = "<div class='smartbroker_section smartbroker_group'>
					<div class='smartbroker_col smartbroker_span_1_of_3'>$i</div>
					<div class='smartbroker_col smartbroker_span_1_of_3'>$j</div>
					<div class='smartbroker_col smartbroker_span_1_of_3'>$k</div>
				</div>";
							
		//--------------------------------------------
		// Compile media
		//--------------------------------------------
		$a = '';
		if ($new_thumb_layout) {
			$a  = 'sb_new_thumb';
			}
		$m = "<div id='sb_clean_thumb_window' class='$a'>";
		foreach($xml_data['data']->boat->media->media as $media) {
			if ($media['type'] == 'photo') {
				$m .= build_photo($media->link,$media->path,$media->description, $boat_id, $xml_data['data'],$media['vr'],$new_thumb_layout);
				} elseif ($media['type'] == 'video') {
				if ($new_thumb_layout)
					$m.= build_video($media->video_id,$media->path,$media->description, $boat_id, $xml_data['data'],$media['vr'],$new_thumb_layout);
				else {
					$m .= sprintf($sb_config['video_link'],$media->video_id,$media->description);
					}
				}
			}
		$m .= "</div>";
		//count photos and videos	
		$photos = $xml_data['data']->xpath('//*[@type="photo"]');
		$num_photos = count($photos);
		$videos = $xml_data['data']->xpath('//*[@type="video"]');
		$num_videos = count($videos);
		if ((($num_photos > 8) OR ($num_videos > 0)) AND ($new_thumb_layout == false)) {
			$m .= "<a id='sb_view_all_link' href='#' style='text-align: right; display: block;'>+ ";
			$mes = '';
			if (($num_photos - 9) == 1) {$mes .= " 1 more photo";}
			if (($num_photos - 9) > 1) {$mes .= $num_photos - 9 ." more photos";}
			if ($num_videos == 1) {
				if ($mes != '') {$mes .= ' and';}
				$mes .= " 1 video";
				}
			if ($num_videos > 1)  {
				if ($mes != '') {$mes .= ' and';}
				$mes .= " $num_videos videos";
				}
			$m .= $mes."</a>";
			}
		
		//--------------------------------------------
		// Sort the find out more div
		//--------------------------------------------
		include_once('find_out_more.php');
		$brokers_notes = nl2br($xml_data['data']->boat->brokers_notes);
		if ($brokers_notes == '') {
			$brokers_notes = "<em>".__("(There are no broker's notes available for this boat)",'smartbroker')."</em>";
			}
			
		//--------------------------------------------
		// Status label (if required)
		//--------------------------------------------
		$label = '';
		if ($xml_data['data']->boat->add_label == 1) {
			$label = $xml_data['data']->boat->status_text.'<br/>';
			}
		
		//--------------------------------------------		
		// Catch widgets into variables
		//--------------------------------------------
		ob_start();
		dynamic_sidebar( 'sb_listing_under_photos' );
		$wd1 = ob_get_contents();
		ob_end_clean();
			
		//--------------------------------------------
		// Build image & key facts blocks
		//--------------------------------------------
		$image_link = $sb_config['server_address']."/images/boats/$boat_id/large/".$xml_data['data']->boat->primary_photo.".jpg";
		$image = "<div id='sb_primary_image'>
				<a href='$image_link' rel='sb_prettyPhoto[all]' title=''>
				<img src='$image_link' title='' alt='".$xml_data['data']->boats->model."'/>
				</a>	 
			</div>";
		$new_thumbs = "";
		if (!$new_thumb_layout) { //using old thumb layout
			$image .= "<div class='sb_no_print'>$m</div>$wd1";
			} else {
			$new_thumbs = "<div class='sb_no_print'>$m</div>";
			}
		
		//key facts
		$kf = "<div class='sb_wide_only'><h1 style='clear: left;'><span id='sb_boat_builder_and_model'>".$xml_data['data']->boat->builder." ".$xml_data['data']->boat->model."</span></h1></div>
		<h2 style='clear: left;'><span class='sb_price_message'>$label".$price."
		<br /><small>".$currency_conversion."</small></span></h2>
		<p><span class='sb_year_message'>".__('Built','smartbroker').': '.$xml_data['data']->boat->year."</span><span class='sb_lying_message'><br/>".__('Currently lying','smartbroker').': '.$xml_data['data']->boat->region.", ".$xml_data['data']->boat->country_name."</span></p>";
		if ($xml_data['data']->boat->approved == 'false') {
			$kf .= $prov_message;
			}
			
		//--------------------------------------------
		// Data required by javascript
		//--------------------------------------------
		$a = "<div style='display: none;' id='server_address_address'>".$sb_config['server_address']."</div>";
		$a .= "<div style='display: none;' id='sb_listing_page'>".$sb_config['listing_page']."</div>";
		
		//--------------------------------------------
		// Compile main layout
		//--------------------------------------------
		$a .= "
		<div class='sb_wrapper sb_theme_clean'>
		<div class='sb_narrow_only'><h1 style='clear: left;'><span id='sb_boat_builder_and_model'>".$xml_data['data']->boat->builder." ".$xml_data['data']->boat->model."</span></h1></div>
		<div>
			<div id='sb_clean_images'>
				$image
			</div>
			$kf
			<div class='sb_no_print'>
				<button onclick=\"location.href='#sb_find_out_more';\" style='position: relative !important;' \>
					".__('Contact us about this boat','smartbroker')."
				</button>
				<br/><br/>
			</div>
			<hr style='clear: left;'/>
			$new_thumbs
			<p><br/>$brokers_notes</p>
		</div>
		$cats
		<hr/><div class='sb_no_print'><a name='sb_find_out_more'></a><h3>".__('Find out more','smartbroker')."</h3>$find_out_more</div>
		<hr /><p><small>".$xml->config->disclaimer."</small></p>
		</div> <!--end sb_wrapper -->";
		
		return $a;
		} else {
		//if we get here, we've got errors, and probably can't find the listing
		echo "<script>window.location.replace('/?page_id=".$sb_config['search_page']."');</script>";
		exit();
		}
	}
?>