<?php 


class DD_Sendnewsletter {

	protected $send;
	
	protected $xmlparser;

	function __construct() {
	
		$this->send      = new DD_Send();
		
		$this->xmlparser = new DD_Xmlparser();		
	}	

	public function listArchives(){
		
		global $wpdb;
		
		$table_name = $wpdb->prefix . 'dd_newsletter';
		
		$newsletter = array();
		
		$archives   = $wpdb->get_results(' SELECT * FROM '.$table_name.' ORDER BY send DESC');
		
		// if we have archives
		if(!empty($archives))
		{
			foreach($archives as $archive)
			{
				$xml   = $this->send->getStatForNewsletter($archive->newsletter_id);
				
				$stats = $this->xmlparser->parser($xml);
				
				$newsletter[$archive->newsletter_id]['send']  = $archive->send;
				$newsletter[$archive->newsletter_id]['id']    = $archive->id;
				$newsletter[$archive->newsletter_id]['stats'] = $stats;
				
			}
		}

		return $newsletter;
		
	}
	
	public function getNewsletter($id){
	
		global $wpdb;
		
		$newsletter = array();
		
		$table_name = $wpdb->prefix . 'dd_newsletter';
		
		$newsletter = $wpdb->get_row(' SELECT * FROM '.$table_name.' WHERE id = '.$id.'');
		
		return $newsletter;

	}
	
	public function lastNewsletterSend(){
	
		global $wpdb;
		
		$today = date('Y-m-d');
		
		$table_name = $wpdb->prefix . 'dd_newsletter';
		
		$send = $wpdb->get_row(' SELECT send FROM '.$table_name.' ORDER BY send DESC LIMIT 0,1');
		
		if(!empty($send))
		{
			return $send->send;
		}
		
		return $today;		

	}
	
}