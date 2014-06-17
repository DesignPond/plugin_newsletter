<?php 


if (function_exists('plugin_dir_path')) 
{
    $path =  plugin_dir_path( __FILE__ );
} 
else 
{
    $path = dirname(dirname(dirname(dirname(__FILE__)))).'/wp-content/plugins/dd_arrets/';
    
    require_once( dirname(dirname(dirname(dirname(__FILE__)))).'/wp-load.php' );
}

include_once('autoloader.php');
// Register the directory to your include files
AutoLoader::registerDirectory( plugin_dir_path( __FILE__ ) . 'admin/classes');

//require_once( '/Applications/MAMP/htdocs/droitpraticien/wp-load.php' );
//require_once( '/Applications/MAMP/htdocs/droitpraticien/wp-admin/includes/admin.php' );