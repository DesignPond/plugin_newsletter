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
 
 require_once(PLUGIN_DIR . 'admin/classes/DD_Send.php');


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
	
	protected $grab;
	
	protected $database;
	
	protected $newsletter;
	
	protected $log;

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
		
		// Suscribe and unsuscribe shortcode function
		add_action( 'admin_post_submit-form', array( $this, '_unsuscribe_nl' ) );

		// Send test email
		add_action( 'admin_post_send-test', array( $this, '_send_test_nl' ) );
		
		// Cron job		
		add_action( 'dd_weekly_newsletter', array( $this, 'send_newsletter' ) );
		
		$this->send       = new DD_Send();
		
		$this->grab       = new DD_Grab();
		
		$this->database   = new DD_Database();
		
		$this->newsletter = new DD_Sendnewsletter();	
		
		$this->log        = new DD_Log();
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
		$sql = "SELECT blog_id FROM $wpdb->blogs WHERE archived = '0' AND spam = '0' AND deleted = '0'";

		return $wpdb->get_col( $sql );

	}
	
	public function send_newsletter(){
		
		// Test if newsletter has already been sent
		$today = date('Y-m-d');
		
		$last  = $this->newsletter->lastNewsletterSend(); // if no last we return today's date
		
		if($today > $last)
		{			
			if( $this->newsletterCanBeSent() )
			{			
				if($this->sending())
				{
					wp_mail('cindy.leschaud@gmail.com', 'Newsletter', 'Newsletter envoyé!');
					
					$this->log->write('Newsletter envoyé! : '.$today);
				}
				else
				{
					wp_mail('cindy.leschaud@gmail.com', 'Newsletter', 'Problème avec la newsletter');
					
					$this->log->write('Problème avec l\'envoi de la newsletter : '.$today);
				}
			}
			else
			{
				wp_mail('cindy.leschaud@gmail.com', 'Newsletter', 'Pas d\'arrets pour la publication à envoyer dans la newsletter');
				
				$this->log->write('Pas d\'arrets pour la publication à envoyer dans la newsletter: '.$today);
			}				
		}
		
		$this->log->write('Déjà envoyé aujourd\'hui : '.$today);
		
	}
	
	/*
	 * Test if the newsletter can be sent, there is arrets
	 *
	*/
	public function newsletterCanBeSent(){
	
		// weeke day range for query last week's arrets
		$dates  = $this->database->getWeekDays();
		
		// Get arrets
		$arrets = $this->database->getArretsAndCategoriesForDates($dates);
		
		if(!empty($arrets))
		{
			return true;
		}
		
		return false;
	}
	
	public function sending() {
		
		// Get url to newsletter
		$url = plugins_url('views/newsletter.php', __FILE__ );	
		
		// Get newsletter html from newsletter.php 
		$body_html = $this->grab->getPage($url);
		
		$list      = get_option('dd_newsletter_list'); 
		
		// Params
		$fromName  = 'Droit pour le Praticien';
		$from      = 'info@droitpourlepraticien.ch';
		$to        = 'cindy.leschaud@gmail.com';
		$subject   = 'Newsletter | Droit pour le Praticien';
		$body_text =  NULL;
		
		// send with elasticemail
		$result    = $this->send->sendElasticEmail($to, $subject, $body_text, $body_html, $from, $fromName, $list);
		
		$newsletter_id = $this->send->testIdSend($result);
		
		if($newsletter_id)
		{
			// Newsletter is send!			
			// Update database with infos
			$this->updateNewsletterIsSend($newsletter_id,$body_html);
			
			// Everything ok!
			return true;
		}

		return false;	

	}
	
	public function updateNewsletterIsSend($newsletter_id,$body_html){
		
		global $wpdb;
		
		$table_name = $wpdb->prefix . 'dd_newsletter';

	    $wpdb->insert($table_name, 
	        array( 'newsletter_id' => $newsletter_id, 'send' => date('Y-m-d') , 'newsletter' => $body_html )
	    );
		
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
	   	
	   	$unsuscribe = NULL;
	   	
	   	/*
			abo-result:
			1: removed ok
			2: removed failed
			3: new/update subscriber ok
			4: suscribe already on list
			5: suscribe failed
		*/
		
		if(isset($_GET['abo-result']))
	   	{	   		
			switch ($_GET['abo-result']) {
			    case 1:
			        $html .= '<div class="dd_success"><strong>Vous avez bien été désinscrit de la newsletter!</strong></div>';
			        $unsuscribe = true;
			        break;
			    case 2:
			        $html .= '<div class="dd_error"><strong>Problème avec la désinscription, cette adresse email n\'existe pas</strong></div>';
			        break;
			    case 3:
			        $html .= '<div class="dd_success"><strong>Vous avez bien été inscrit à la newsletter!</strong></div>';
			        $unsuscribe = true;
			        break;
			    case 4:
			        $html .= '<div class="dd_error"><strong>Cette adresse email existe déjà!</strong></div>';
			        break;	
			    case 5:
			        $html .= '<div class="dd_error"><strong>Cette adresse email n\'est pas valide!</strong></div>';
			        break;				    			      
			}  			
	   	}
	   	
	   	if(!$unsuscribe)
	   	{   		   	
		   	$html .= '<div id="'.$args['newsletter'].'">';
		   	$html .= '<h4>Derniers arrêts proposés pour la publication</h4>';
		   	// Test if we what to suscribe or to unsuscribe from the newsletter
		   	if($args['newsletter'] == 'suscribe')
		   	{
		   		$html .= '<p>S\'inscrire à la newsletter</p>';
		   		$html .= '<form action="'.$action.'" method="post">'; 
				$html .= '<input type="hidden" name="newsletter" value="suscribe" />';
				$html .= '<input type="hidden" name="redirect" value="'.$redirect.'" />';		   	
		   	}
		   	else
		   	{
		   		$html .= '<p>Se desinscrire de la newsletter</p>';
		   		$html .= '<form action="'.$action.'" method="post">'; 
				$html .= '<input type="hidden" name="newsletter" value="unsuscribe" />';
				$html .= '<input type="hidden" name="redirect" value="'.$redirect.'" />';		   	
		   	}
				
			$html .= '<input type="hidden" name="action" value="submit-form" />';
			
				$html .= '<div class="input-group col-sm-8">';
					$html .= '<input type="text" name="email" class="form-control" placeholder="Votre email" />';
					$html .= '<span class="input-group-btn"><button class="btn btn-buy" type="submit">Envoyer</button></span>';
				$html .= '</div>';
			    
			$html .= '</form>';	    
		    $html .= '</div>'; 
		    
		} 
	    
	    return $html;
	}
    	
	public function _unsuscribe_nl(){
	
		/*
			abo-result:
			1: removed ok
			2: removed failed
			3: new/update subscriber ok
			4: suscribe already on list
			5: suscribe failed
		*/
		
		$list  = get_option('dd_newsletter_list'); 
		
		if( isset($_POST['email']) &&  !empty($_POST['email']))
		{
			// test what we have to do!!! suscribe or unsuscribe
			$attemp = $this->send->addOrDeleteUserFromList($_POST['email'], $list , $_POST['newsletter']);
				
			if ( $_POST['newsletter'] == 'unsuscribe' ) 
			{
				if(strpos($attemp,'removed') !== false)
				{
					$where = array('unsuscribe' => 'ok', 'abo-result' => 1);
				}
				else
				{
					$where = array('unsuscribe' => 'no', 'abo-result' => 2);		    
				}
			}	
			else if ( $_POST['newsletter'] == 'suscribe' ) 
			{
				if( strpos($attemp,'created') !== false || strpos($attemp,'updated') !== false)
				{
					$where = array('suscribe' => 'ok', 'abo-result' => 3);
				}
				else if( strpos($attemp,'already') !== false  )
				{
					$where = array('suscribe' => 'no', 'abo-result' => 4);
				}
				else
				{
					$where = array('suscribe' => 'no', 'abo-result' => 5);		    
				}				
			}
						
			$url = add_query_arg( $where , $_POST['redirect'] );
				
		}
		else
		{
			$url = add_query_arg( array('ohoh' => 'problem') , $_POST['redirect'] );
		}	
					
		wp_redirect( $url ); 			    
		exit;
		
	}
	
	public function _send_test_nl(){
	
		// Get url to newsletter
		$page = admin_url( 'options-general.php?page=dd_newsletter' ); // redirect url
	
		if( isset($_POST['email']) &&  !empty($_POST['email']))
		{
			if(filter_var($_POST['email'], FILTER_VALIDATE_EMAIL))
			{
				
				// If we have arrêts we can send the newsletter
				if( $this->newsletterCanBeSent() )
				{	
					// Get url to newsletter
					$url  = plugins_url('views/newsletter.php', __FILE__ );	
					
					// Get newsletter html from newsletter.php 
					$body_html = $this->grab->getPage($url);
				
					add_filter( 'wp_mail_content_type', create_function('', 'return "text/html"; '));
					
					wp_mail($_POST['email'], 'Test Newsletter Droit pour le Praticien', $body_html);
					
					$redirect = add_query_arg( array('send-result' => 0) , $page );
					
	        	}
	        	else
	        	{
		        	$redirect = add_query_arg( array('send-result' => 1) , $page ); 
	        	}
		    }
		    else
		    {
				$redirect = add_query_arg( array('send-result' => 2) , $page );    
		    }			
		}
		else
		{
			$redirect = add_query_arg( array('send-result' => 3) , $page );   
		}
		
		// redirect with result
		wp_redirect( $redirect ); 			    
		exit;
	}


}
