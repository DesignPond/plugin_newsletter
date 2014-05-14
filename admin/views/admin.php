<?php
/**
 * Represents the view for the administration dashboard.
 *
 * This includes the header, options, and other information that should provide
 * The User Interface to the end user.
 *
 * @package   Plugin_Name
 * @author    Your Name <email@example.com>
 * @license   GPL-2.0+
 * @link      http://example.com
 * @copyright 2014 Your Name or Company Name
 */
?>

<div class="wrap">

	<h2><?php echo esc_html( get_admin_page_title() ); ?></h2>
	
	<?php
	
/*
		$grab = new Grab();		
		$send = new Send();
				
		$url  = plugins_url('public/views/newsletter.php', dirname(dirname( __FILE__ )) );
		
		//$body_html = $grab->getPage($url);
		
		//echo $body_html;
		
		// Params
		$fromName  = 'Cindy Leschaud';
		$from      = 'cindy.leschaud@gmail.com';
		$to        = 'cindy.leschaud@gmail.com';
		$list      = 'myself';
		$subject   = 'Newsletter | Droit pour le Praticien';
		$body_text = NULL;

		//$result    = $send->sendElasticEmail($to, $subject, $body_text, $body_html, $from, $fromName, $list);
*/	
	?>
				
		<form method="post" action="options.php">
		
		    <?php settings_fields( 'dd-settings-group' ); ?>
		    <?php do_settings_sections( 'dd-settings-group' ); ?>
		    
		    <table class="form-table">
		        <tr valign="top">
		        <th scope="row">Newsletter nom de la liste</th>
		        <td><input type="text" name="dd_newsletter_list" value="<?php echo get_option('dd_newsletter_list'); ?>" /></td>
		        </tr>
		    </table>
		
		    <?php submit_button(); ?>
		
		</form>

</div>
