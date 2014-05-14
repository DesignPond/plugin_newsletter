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

		$grab       = new Grab();		
		$send       = new Send();
		$newsletter = new Newsletter();	
		$xmlparser  = new Xmlparser();				

/*
		$url  = plugins_url('public/views/newsletter.php', dirname(dirname( __FILE__ )) );
		
		$body_html = $grab->getPage($url);
		
		//echo $body_html;
		
		// Params
		$fromName  = 'Cindy Leschaud';
		$from      = 'cindy.leschaud@gmail.com';
		$to        = 'cindy.leschaud@gmail.com';
		$list      = 'myself';
		$subject   = 'Newsletter | Droit pour le Praticien';
		$body_text = NULL;

		$result    = $send->sendElasticEmail($to, $subject, $body_text, $body_html, $from, $fromName, $list);
	
		print_r($result);
*/
		
	?>
		
		<h3>Liste</h3>	
		
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
		
		<h3>Envoyer test</h3>	
		
		<form method="post" action="admin-post.php">
		    
		    <table class="form-table">
		        <tr valign="top">
		        <th scope="row">Email</th>
		        <td width="200px">
		        	<input type="hidden" name="action" value="send-test" />
		        	<input type="text" name="email" />
		        </td>
		        <td><input type="submit" value="Envoyer le test" class="button button-primary" id="submit" name="submit"></td>
		        </tr>
		    </table>
		
		</form>
		
		<h3>Archives</h3>
		
		<?php
		
			// Get url to newsletter
			$url = plugins_url('archive.php', __FILE__ );	
			
			$archives = $newsletter->listArchives();
			
			if(!empty($archives)){
				
				echo '<table width="55%">';
				
					echo '<tr align="left">';
						echo '<th>Date d\'envoi</th>';
						echo '<th>Status</th>';
						echo '<th>ID</th>';
						echo '<th>Recipients</th>';
						echo '<th>Failed</th>';
						echo '<th>Delivered</th>';
						echo '<th>Pending</th>';
						echo '<th>Voir</th>';
					echo '</tr>';
				
				foreach($archives as $id => $archive){
					
					echo '<tr>';
						echo '<td>'.$archive['send'].'</td>';
						echo '<td>'.$archive['stats']['status'].'</td>';						
						echo '<td>'.$id.'</td>';
						echo '<td>'.$archive['stats']['recipients'].'</td>';
						echo '<td>'.$archive['stats']['failed'].'</td>';
						echo '<td>'.$archive['stats']['delivered'].'</td>';
						echo '<td>'.$archive['stats']['pending'].'</td>';
						echo '<td><a target="_blank" href="'.$url.'?id='.$archive['id'].'">newsletter</a></td>';
					echo '</tr>';					

				}
				
				echo '</table>';			
			}
			
			
			
		?>
		

</div>
