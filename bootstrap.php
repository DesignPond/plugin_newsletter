<?php 

include_once('autoloader.php');
// Register the directory to your include files
AutoLoader::registerDirectory( plugin_dir_path( __FILE__ ) . 'admin/classes');

require_once( '/Applications/MAMP/htdocs/praticien/wp-load.php' );
require_once( '/Applications/MAMP/htdocs/praticien/wp-admin/includes/admin.php' );