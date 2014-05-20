<?php
/**
 * The WordPress Plugin Boilerplate.
 *
 * A foundation off of which to build well-documented WordPress plugins that
 * also follow WordPress Coding Standards and PHP best practices.
 *
 * @package   DD_Newsletter
 * @author    Cindy Leschaud <cindy.leschaud@gmail.com>
 * @license   GPL-2.0+
 * @link      http://designpond.ch
 * @copyright 2014 DesignPond
 *
 * @wordpress-plugin
 * Plugin Name:       DD_Newsletter
 * Plugin URI:        http://designpond.ch
 * Description:       Envoi de newsletter quotidienne depuis www.droitpraticien.ch
 * Version:           1.0.0
 * Author:            Cindy Leschaud
 * Author URI:        http://designpond.ch
 * Text Domain:       dd_newsletter-locale
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Domain Path:       /languages
 * GitHub Plugin URI: https://github.com/<owner>/<repo>
 * WordPress-Plugin-Boilerplate: v2.6.1
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/*----------------------------------------------------------------------------*
 * Public-Facing Functionality
 *----------------------------------------------------------------------------*/
 
 // bootstrap classes
require_once( plugin_dir_path( __FILE__ ) . 'bootstrap.php' );

require_once( plugin_dir_path( __FILE__ ) . 'public/class-dd_newsletter.php' );


/*
 * Register hooks that are fired when the plugin is activated or deactivated.
 * When the plugin is deleted, the uninstall.php file is loaded.
 *
 */
 
register_activation_hook( __FILE__  , array( 'DD_Newsletter', 'activate' ) );
register_deactivation_hook( __FILE__, array( 'DD_Newsletter', 'deactivate' ) );

register_activation_hook( __FILE__  , 'add_schedule' );
register_deactivation_hook( __FILE__, 'clear_schedule');

add_action( 'plugins_loaded', array( 'DD_Newsletter', 'get_instance' ) );
add_shortcode('unsuscribe_newsletter', array( 'DD_Newsletter', 'unsuscribe_newsletter_shortcode' ) );

function add_schedule()
{
	wp_schedule_event( time(), 'daily', 'my_daily_event' );
}

function clear_schedule()
{
	wp_clear_scheduled_hook('my_daily_event');
}



/*----------------------------------------------------------------------------*
 * Dashboard and Administrative Functionality
 *----------------------------------------------------------------------------*/

/*
 *
 * If you want to include Ajax within the dashboard, change the following
 * conditional to:
 *
 * if ( is_admin() ) {
 *   ...
 * }
 *
 * The code below is intended to to give the lightest footprint possible.
 */
if ( is_admin() && ( ! defined( 'DOING_AJAX' ) || ! DOING_AJAX ) ) {

	require_once( plugin_dir_path( __FILE__ ) . 'admin/class-dd_newsletter-admin.php' );
	
	add_action( 'plugins_loaded', array( 'DD_Newsletter_Admin', 'get_instance' ) );
	
	register_activation_hook( __FILE__, array( 'DD_Newsletter_Admin', 'dd_create_plugin_tables' ) );

}
