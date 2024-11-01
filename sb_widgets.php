<?php
wp_register_sidebar_widget(
	'sb_widget_search',
	'SmartBroker search',
	'widget_smartbroker_search',
	array(
		'description'=>'Add SmartBroker search form')
	);

function widget_smartbroker_search($args) {
	echo $before_widget;
	echo $before_title;
	//load options
	$data['size_low'] = get_option('sb_widget_size_low');
	$data['size_high']  = get_option('sb_widget_size_high');
	$data['price_low'] = get_option('sb_widget_price_low');
	$data['price_high'] = get_option('sb_widget_price_high');
	echo "<style> .sb_widget p {margin: 1em 0;} </style>";
	echo "<div class='sb_widget'>".sb_search_box_func($data).'</div>';
	echo $after_title;
	echo $after_widget;
	}
	
wp_register_widget_control('sb_widget_search','sb_widget_search', 'widget_smartbroker_search_config');

function widget_smartbroker_search_config($args = array()) {
	//the form is submitted, save into database
	if (isset($_POST['submitted'])) {
		update_option('sb_widget_size_low', $_POST['sb_widget_size_low']);
		update_option('sb_widget_size_high', $_POST['sb_widget_size_high']);
		update_option('sb_widget_price_low', $_POST['sb_widget_price_low']);
		update_option('sb_widget_price_high', $_POST['sb_widget_price_high']);
	}

	//load options
	$sb_widget_size_low = get_option('sb_widget_size_low');
	$sb_widget_size_high  = get_option('sb_widget_size_high');
	$sb_widget_price_low = get_option('sb_widget_price_low');
	$sb_widget_price_high = get_option('sb_widget_price_high');
	?>
	<p>Default values for search fields:</p>
	<p>Size low (same units as on your SmartBroker system):<br />
	<input type="number" name="sb_widget_size_low" value="<?php echo intval($sb_widget_size_low); ?>" /></p>
	<p>Size high:<br />
	<input type="number" name="sb_widget_size_high" value="<?php echo intval($sb_widget_size_high); ?>" /></p>
	<p>Price low (in your Primary currency):<br />
	<input type="number" name="sb_widget_price_low" value="<?php echo intval($sb_widget_price_low); ?>" /></p>
	<p>Price high:<br />
	<input type="number" name="sb_widget_price_high" value="<?php echo intval($sb_widget_price_high); ?>" /></p>
	<p><em>If any of these figures fall outside the range of sizes/prices available, they will be adjusted to suit.</em></p>
	<input type="hidden" name="submitted" value="1" />
	<?php
	}
?>