<?php
/**
 * Plugin Name.
 *
 * @package   DD_Newsletter
 * @author    Your Name <email@example.com>
 * @license   GPL-2.0+
 * @link      http://example.com
 * @copyright 2014 Your Name or Company Name
 */ 
 
 define( 'PLUGIN_DIR', dirname(dirname(__FILE__)).'/' );  
 
 require_once(PLUGIN_DIR . 'admin/classes/Send.php');


/**
 * Plugin class. This class should ideally be used to work with the
 * public-facing side of the WordPress site.
 *
 * If you're interested in introducing administrative or dashboard
 * functionality, then refer to `class-plugin-name-admin.php`
 *
 * @TODO: Rename this class to a proper name for your plugin.
 *
 * @package DD_Newsletter
 * @author  Your Name <email@example.com>
 */
class DD_Newsletter {

	/**
	 * Plugin version, used for cache-busting of style and script file references.
	 *
	 * @since   1.0.0
	 *
	 * @var     string
	 */
	const VERSION = '1.0.0';

	/**
	 * @TODO - Rename "plugin-name" to the name of your plugin
	 *
	 * Unique identifier for your plugin.
	 *
	 *
	 * The variable name is used as the text domain when internationalizing strings
	 * of text. Its value should match the Text Domain file header in the main
	 * plugin file.
	 *
	 * @since    1.0.0
	 *
	 * @var      string
	 */
	protected $plugin_slug = 'dd_newsletter';

	/**
	 * Instance of this class.
	 *
	 * @since    1.0.0
	 *
	 * @var      object
	 */
	protected static $instance = null;
	
	protected $send;

	/**
	 * Initialize the plugin by setting localization and loading public scripts
	 * and styles.
	 *
	 * @since     1.0.0
	 */
	private function __construct() {

		// Load plugin text domain
		add_action( 'init', array( $this, 'load_plugin_textdomain' ) );

		// Activate plugin when new blog is added
		add_action( 'wpmu_new_blog', array( $this, 'activate_new_site' ) );

		// Load public-facing style sheet and JavaScript.
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_styles' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
		
		add_action( 'admin_post_submit-form', array( $this, '_unsuscribe_nl' ) );
				
		add_action( 'my_daily_event', array( $this, 'send_newsletter' ) );
		
		$this->send = new Send();
	}

	/**
	 * Return the plugin slug.
	 *
	 * @since    1.0.0
	 *
	 * @return    Plugin slug variable.
	 */
	public function get_plugin_slug() {
		return $this->plugin_slug;
	}

	/**
	 * Return an instance of this class.
	 *
	 * @since     1.0.0
	 *
	 * @return    object    A single instance of this class.
	 */
	public static function get_instance() {

		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	/**
	 * Fired when the plugin is activated.
	 *
	 * @since    1.0.0
	 *
	 * @param    boolean    $network_wide    True if WPMU superadmin uses
	 *                                       "Network Activate" action, false if
	 *                                       WPMU is disabled or plugin is
	 *                                       activated on an individual blog.
	 */
	public static function activate( $network_wide ) {

		if ( function_exists( 'is_multisite' ) && is_multisite() ) {

			if ( $network_wide  ) {

				// Get all blog ids
				$blog_ids = self::get_blog_ids();

				foreach ( $blog_ids as $blog_id ) {

					switch_to_blog( $blog_id );
					self::single_activate();
				}

				restore_current_blog();

			} else {
				self::single_activate();
			}

		} else {
			self::single_activate();
		}

	}

	/**
	 * Fired when the plugin is deactivated.
	 *
	 * @since    1.0.0
	 *
	 * @param    boolean    $network_wide    True if WPMU superadmin uses
	 *                                       "Network Deactivate" action, false if
	 *                                       WPMU is disabled or plugin is
	 *                                       deactivated on an individual blog.
	 */
	public static function deactivate( $network_wide ) {

		if ( function_exists( 'is_multisite' ) && is_multisite() ) {

			if ( $network_wide ) {

				// Get all blog ids
				$blog_ids = self::get_blog_ids();

				foreach ( $blog_ids as $blog_id ) {

					switch_to_blog( $blog_id );
					self::single_deactivate();

				}

				restore_current_blog();

			} else {
				self::single_deactivate();
			}

		} else {
			self::single_deactivate();
		}

	}

	/**
	 * Fired when a new site is activated with a WPMU environment.
	 *
	 * @since    1.0.0
	 *
	 * @param    int    $blog_id    ID of the new blog.
	 */
	public function activate_new_site( $blog_id ) {

		if ( 1 !== did_action( 'wpmu_new_blog' ) ) {
			return;
		}

		switch_to_blog( $blog_id );
		self::single_activate();
		restore_current_blog();

	}

	/**
	 * Get all blog ids of blogs in the current network that are:
	 * - not archived
	 * - not spam
	 * - not deleted
	 *
	 * @since    1.0.0
	 *
	 * @return   array|false    The blog ids, false if no matches.
	 */
	private static function get_blog_ids() {

		global $wpdb;

		// get an array of blog ids
		$sql = "SELECT blog_id FROM $wpdb->blogs
			WHERE archived = '0' AND spam = '0'
			AND deleted = '0'";

		return $wpdb->get_col( $sql );

	}
	
	
	public function send_newsletter() {
		
		wp_mail('cindy.leschaud@gmail.com', 'Test de send newsletter', 'Depuis le plugin dd_newsletter ');
	}


	/**
	 * Fired for each blog when the plugin is activated.
	 *
	 * @since    1.0.0
	 */
	private static function single_activate() {
		// @TODO: Define activation functionality here
	}

	/**
	 * Fired for each blog when the plugin is deactivated.
	 *
	 * @since    1.0.0
	 */
	private static function single_deactivate() {
	}

	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		$domain = $this->plugin_slug;
		$locale = apply_filters( 'plugin_locale', get_locale(), $domain );

		load_textdomain( $domain, trailingslashit( WP_LANG_DIR ) . $domain . '/' . $domain . '-' . $locale . '.mo' );
		load_plugin_textdomain( $domain, FALSE, basename( plugin_dir_path( dirname( __FILE__ ) ) ) . '/languages/' );

	}

	/**
	 * Register and enqueue public-facing style sheet.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {
		wp_enqueue_style( $this->plugin_slug . '-plugin-styles', plugins_url( 'assets/css/public.css', __FILE__ ), array(), self::VERSION );
	}

	/**
	 * Register and enqueues public-facing JavaScript files.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
		wp_enqueue_script( $this->plugin_slug . '-plugin-script', plugins_url( 'assets/js/public.js', __FILE__ ), array( 'jquery' ), self::VERSION );
	}
	
	/**
	 * Shortcodes
	*/	
	public static function unsuscribe_newsletter_shortcode( $atts ) {
	   	
	   	$args     = shortcode_atts( array('newsletter' => 'suscribe' ), $atts );
	   	
	   	$action   = admin_url( 'admin-post.php');
	   	
	   	$redirect = get_permalink();
	   	
	   	$html     = '';
	   	
	   	if(isset($_GET['unsuscribe']))
	   	{
	   		if($_GET['unsuscribe'] == 'ok')
	   		{
		   		$html .= '<p style="display:block;padding:5px;background:#bfffc2;color:#105513;">Vous avez bien été désinscrit de la newsletter!</p>';	
	   		}
	   		else
	   		{
		   		$html .= '<p style="display:block;padding:5px;background:#fbbdbd;color:#551010;">Problème avec la désinscription, cette adresse email n\'existe pas</p>';
	   		}	   			
	   	}
	   	
	   	if(isset($_GET['suscribe']))
	   	{
	   		if($_GET['suscribe'] == 'ok')
	   		{
		   		$html .= '<p style="display:block;padding:5px;background:#bfffc2;color:#105513;">Vous avez bien été inscrit à la newsletter!</p>';	
	   		}
	   		else
	   		{
		   		$html .= '<p style="display:block;padding:5px;background:#fbbdbd;color:#551010;">Cette adresse email existe déjà / n\'est pas valide</p>';
	   		}	   			
	   	}

	   	if(isset($_GET['ohoh']))
	   	{
		   	$html .= '<p style="display:block;padding:5px;background:#fbbdbd;color:#551010;">Cette adresse email n\'est pas valide.</p>';	   		   			
	   	}
	   		   		   	
	   	$html .= '<div id="'.$args['newsletter'].'">';
	   	
	   	// Test if we what to suscribe or to unsuscribe from the newsletter
	   	if($args['newsletter'] == 'suscribe')
	   	{
	   		$html .= '<h3>S\'inscrire à la newsletter "Derniers arrêts proposés pour la publication"</h3>';
	   		$html .= '<form action="'.$action.'" method="post">'; 
			$html .= '<input type="hidden" name="newsletter" value="suscribe" />';
			$html .= '<input type="hidden" name="redirect" value="'.$redirect.'" />';		   	
	   	}
	   	else
	   	{
	   		$html .= '<h3>Se désinscrire de la newsletter "Derniers arrêts proposés pour la publication"</h3>';
	   		$html .= '<form action="'.$action.'" method="post">'; 
			$html .= '<input type="hidden" name="newsletter" value="unsuscribe" />';
			$html .= '<input type="hidden" name="redirect" value="'.$redirect.'" />';		   	
	   	}
			
		$html .= '<input type="hidden" name="action" value="submit-form" />';
		$html .= '<input type="text" name="email" />';
		$html .= '<input type="submit" value="envoyer" />';
		    
		$html .= '</form>';	    
	    $html .= '</div>';  
	    
	    return $html;
	}
    	
	public function _unsuscribe_nl(){
		
		if( isset($_POST['email']) &&  !empty($_POST['email']))
		{
			// test what we have to do!!! suscribe or unsuscribe
			$attemp = $this->send->addOrDeleteUserFromList($_POST['email'], 'test' , $_POST['newsletter']);
						
			if ( $_POST['newsletter'] == 'unsuscribe' ) 
			{
				if(strpos($attemp,'removed') !== false)
				{
					$where = array('unsuscribe' => 'ok');
				}
				else
				{
					$where = array('unsuscribe' => 'no');		    
				}
			}	
			else if ( $_POST['newsletter'] == 'suscribe' ) 
			{
				if( strpos($attemp,'created') !== false || strpos($attemp,'updated') !== false )
				{
					$where = array('suscribe' => 'ok');
				}
				else
				{
					$where = array('suscribe' => 'no');		    
				}				
			}
			
			
			$url = add_query_arg( $where , $_POST['redirect'] );
			
			wp_redirect( $url ); 			    
			exit;
			
		}
		else
		{

			$url = add_query_arg( array('ohoh' => 'problem') , $_POST['redirect'] );
			
			wp_redirect( $url ); 			    
			exit;
		}
	}


	/**
	 * NOTE:  Actions are points in the execution of a page or process
	 *        lifecycle that WordPress fires.
	 *
	 *        Actions:    http://codex.wordpress.org/Plugin_API#Actions
	 *        Reference:  http://codex.wordpress.org/Plugin_API/Action_Reference
	 *
	 * @since    1.0.0
	 */
	public function action_method_name() {
	
	}

	/**
	 * NOTE:  Filters are points of execution in which WordPress modifies data
	 *        before saving it or sending it to the browser.
	 *
	 *        Filters: http://codex.wordpress.org/Plugin_API#Filters
	 *        Reference:  http://codex.wordpress.org/Plugin_API/Filter_Reference
	 *
	 * @since    1.0.0
	 */
	public function filter_method_name() {
		// @TODO: Define your filter hook callback here
	}

}
