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
	
		$grab = new Grab();
		
		$send = new Send();
				
		$url  = plugins_url('newsletter.php', __FILE__);
		
		 $body_html = $grab->getPage($url);
		
		echo $body_html;
		
		// Params
		$fromName  = 'Cindy Leschaud';
		$from      = 'cindy.leschaud@gmail.com';
		$to        = 'cindy.leschaud@gmail.com';
		$list      = 'myself';
		$subject   = 'Newsletter | Droit pour le Praticien';
		$body_text = NULL;

		//echo $send->sendElasticEmail($to, $subject, $body_text, $body_html, $from, $fromName, $list);
		
		// echo admin_url( 'options-general.php?page=dd_newsletter');
		
	?>

</div>
