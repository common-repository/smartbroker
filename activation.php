<?php 
function make_pages() {
	global $wpdb, $sb_config;
	
	include('white_label_settings.php');
	
	//$sb_config= get_option('sb_plugin_options');
	if ($sb_config['currency_1'] == '') {
		$sb_config['currency_1'] = 'EUR';
		}
	if	(!array_key_exists('server_address',$sb_config) OR ($sb_config['server_address'] == '')) {
		$sb_config['server_address'] = $sb_white_label['default_server'];
		}
	if	(!array_key_exists('css',$sb_config)) {
		$sb_config['css'] = '';
		}
	
	$the_query = new WP_Query(array('post_type' => 'page', 's'=>'['.$sb_white_label['sc_prefix'].'listing]','post_status'=>'publish'));
	if ( $the_query->found_posts > 0 ) {
		$listing_page_id =  $the_query->posts[0]->ID; //just get first page found
		} else {
		//no listing page, create page now
		$data = array(
			'post_author'=>'1',
			'post_date'=>date('Y-m-d H:i:s'),
			'post_date_gmt'=>date('Y-m-d H:i:s'),
			'post_content'=>"<!-- page created by ".$sb_white_label['name']." plugin.-->
[".$sb_white_label['sc_prefix']."listing]",
			'post_title'=>'Listing page',
			'post_status'=>'publish',
			'comment_status'=>'closed',
			'ping_status'=>'closed',
			'ping_status'=>'closed',
			'post_name'=>'sb_listing',
			'post_modified'=>date('Y-m-d H:i:s'),
			'post_modified_gmt'=>date('Y-m-d H:i:s'),
			'menu_order'=>'0',
			'post_type'=>'page'
			);
		$wpdb->insert($wpdb->prefix.'posts',$data);
		$listing_page_id = $wpdb->insert_id;
		}
	
	$the_query = new WP_Query( 's='.$sb_white_label['sc_prefix'].'search' );
	if ( $the_query->found_posts > 0 ) {
		$search_page_id =  $the_query->posts[0]->ID; //just get first page found
		} else {
		//no search page, create page now
		$data = array(
			'post_author'=>'1',
			'post_date'=>date('Y-m-d H:i:s'),
			'post_date_gmt'=>date('Y-m-d H:i:s'),
			'post_content'=>"<!-- page created by ".$sb_white_label['name']." plugin. You may wish to set this page to be full-width (with no sidebar), or you may end up with two search boxes on one page -->
[".$sb_white_label['sc_prefix']."search_page]",
			'post_title'=>'Search',
			'post_status'=>'publish',
			'comment_status'=>'closed',
			'ping_status'=>'closed',
			'ping_status'=>'closed',
			'post_name'=>'sb_search',
			'post_modified'=>date('Y-m-d H:i:s'),
			'post_modified_gmt'=>date('Y-m-d H:i:s'),
			'menu_order'=>'0',
			'post_type'=>'page'
			);
		$wpdb->insert($wpdb->prefix.'posts',$data);
		$search_page_id = $wpdb->insert_id;
		}
	
	$options = array(
					'listing_page'=>$listing_page_id,
					'search_page'=>$search_page_id,
					'currency_1'=>$sb_config['currency_1'],
					'server_address'=>$sb_config['server_address'],
					'css'=>$sb_config['css']
					);
	update_option('sb_plugin_options',$options);
	
	set_transient( 'sb_about_page_activated', 1, 30 );
	
	
	}

function sb_add_message() {

	// only do this if the user can activate plugins
	if ( ! current_user_can( 'manage_options' ) )
		return;
 
	// don't do anything if the transient isn't set
	if ( ! get_transient( 'sb_about_page_activated' ) )
		return;
	
	delete_transient( 'sb_about_page_activated' );
	add_action( 'admin_notices', 'my_admin_notice' );
	}

function my_admin_notice() {
	global $sb_config;
	echo "<div class='updated'><img src='http://www.smart-broker.co.uk/wp-content/uploads/2012/02/logo1.png' alt='Logo: SmartBroker' style='display: inline-block; padding: 2em;'/><p>The SmartBroker plugin has been activated, and you can start to search for listings from your search page, which you'll probably want to add to your home menu at some point.</p>
	<h3><strong><a href='/?page_id=".$sb_config['search_page']."'>Go to the listings search page now &gt; &gt;</a></strong></h3>
	<p><small>Full details (including advanced setup options) are available at <a href='http://www.smart-broker.co.uk/?page_id=300' target='_blank'>http://www.smart-broker.co.uk/?page_id=300</a>.</small></p>
	</div>";
	}
	
?>