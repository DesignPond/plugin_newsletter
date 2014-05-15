<?php

	// require wordpress bootstrap
	require_once( $_SERVER["DOCUMENT_ROOT"].'/wp-load.php' );
	// database functions
	$newsletter = new DD_Sendnewsletter();	
	
	if(isset($_GET['id']))
	{	
		$archive = $newsletter->getNewsletter($_GET['id']);	
		
		echo $archive->newsletter;
	}
	
	
?>