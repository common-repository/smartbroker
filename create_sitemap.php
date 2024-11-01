<?php

function get_home_path2() {
	$home    = set_url_scheme( get_option( 'home' ), 'http' );
	$siteurl = set_url_scheme( get_option( 'siteurl' ), 'http' );
	if ( ! empty( $home ) && 0 !== strcasecmp( $home, $siteurl ) ) {
		$wp_path_rel_to_home = str_ireplace( $home, '', $siteurl ); /* $siteurl - $home */
		$pos = strripos( str_replace( '\\', '/', $_SERVER['SCRIPT_FILENAME'] ), trailingslashit( $wp_path_rel_to_home ) );
		$home_path = substr( $_SERVER['SCRIPT_FILENAME'], 0, $pos );
		$home_path = trailingslashit( $home_path );
	} else {
		$home_path = ABSPATH;
	}

	return str_replace( '\\', '/', $home_path );
	}

$last_refresh = get_option('sb_sitemap_last_refresh');

if (($last_refresh === FALSE) OR ($last_refresh < time() - 86400) OR ($last_refresh < 1474400000)) { // final option to fix yachting brokers issue, can be removed later
//if (($last_refresh === FALSE) OR ($last_refresh < time() - 1)) { //testing only!!
	//sitemap is over a day old, refresh now
	$result = create_sitemap();
	update_option('sb_sitemap_last_refresh',time());
}




function create_sitemap() {
	global $sb_config;
	
	class SimpleXMLExtended extends SimpleXMLElement {
	public function addCData($cdata_text) {
		$node= dom_import_simplexml($this); 
		$no = $node->ownerDocument; 
		$node->appendChild($no->createCDATASection($cdata_text)); 
		}
	}

	//root node
	$xml = new SimpleXMLExtended("<?xml version='1.0' encoding='utf-8'?><urlset />");
	$xml->addAttribute('xmlns',"http://www.sitemaps.org/schemas/sitemap/0.9");

	$data=array('ln'=>1000); //max 1000 boats for this request
	$listings_xml = load_results_xml($data);
	//var_dump($sb_config);
	//die();
	foreach ($listings_xml['data']->boat as $b) {
		$boat_id = $b->boat_id;
		
		$loc = htmlentities(site_url().'?page_id='.$sb_config['listing_page'].'&boat_id='.$boat_id);
		
		$url_node = $xml->addChild('url');
		$loc_node = $url_node->addChild('loc',$loc);
		$change_node = $url_node->addChild('changefreq','weekly');
		$pri_node = $url_node->addChild('priority','0.8');
		}

	//output to console
	//header ("content-type: text/xml; charset=UTF-8");
	//print $xml->asXML();

	$path = get_home_path2().'sb_sitemap.xml';
	file_put_contents($path,$xml->asXML());
	update_robots_txt();
	}

function update_robots_txt() {
	$file = get_home_path2().'robots.txt';
	$text = 'Sitemap: '.site_url().'/sb_sitemap.xml';
	if (file_exists($file)) {
		$file_contents = file_get_contents($file);
		$search = strpos($text,$file_contents);
		if ($search === FALSE) {
			$file_contents = $file_contents."\r\n".$text;
			}
		} else {
		//no robots.txt file, make one now from current text only
		$file_contents = $text;
		}
	file_put_contents($file,$file_contents);
	}


?>