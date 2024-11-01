<?php
// This file holds the details of brand name etc. to allow white labelling of plugin
// NOTE: to complete the re-naming, you'll also have to perform the following steps:
//
// 1: Change the plugin name in on line 3 of smartbroker.php
// 2: Change the plugin link in on line 4 of smartbroker.php
// 3: Change the plugin description on line 5 of smartbroker.php
// 4: Change the author name on line 7 of smartbroker.php
// 5: Change the author uri on line 8 of smartbroker.php
//

// The name of the system
// Default: SmartBroker
// --
$sb_white_label['name'] = 'SmartBroker';
//$sb_white_label['name'] = '(your system name here)';


// The default server the plugin will try to connect to if there's no other server specified
// Default: http://demo.smart-broker.co.uk
// --
$sb_white_label['default_server'] = 'https://demo.smart-broker.co.uk';
//$sb_white_label['default_server'] = 'http://demo.example.com';


// The sales site for your product (where users go to find out more about the system
// Default: http://www.smart-broker.co.uk
// --
$sb_white_label['sales_site'] = 'https://www.smart-broker.co.uk';
//$sb_white_label['sales_site'] = 'http://www.example.com';

// The installation guide for your product
// Default: http://www.smart-broker.co.uk/?page_id=300
// --
$sb_white_label['install_guide'] = 'https://www.smart-broker.co.uk/?page_id=300';
//$sb_white_label['install_guide'] = 'http://www.example.com/install_guide.html';

// Link to system logo: a 300w x 98h px logo in png, gif or jpg format
// This should be placed in the /images subfolder of the plugin file system
// Default: logo.png
// --
$sb_white_label['logo_url'] = 'logo.png';
//$sb_white_label['logo_url'] = 'white-label.png';

// Shortcode prefixes. SmartBroker uses the 'sb_' prefix to try and avoid shortcode clashes with other plugins. Change this here if required
// Default: 'sb_'
// --
$sb_white_label['sc_prefix'] = 'sb_';
//$sb_white_label['sc_prefix'] = 'your_prefix_here_';

?>