<?php 

class Grab {
	
	protected $user_agent;

	function __construct() {
		
		$this->user_agent = "Mozilla/4.0 (compatible; MSIE 5.01; Windows NT 5.0)";
		
	}	

	public function curl_grab_page($url){
	
	    $ch = curl_init();
	
	    curl_setopt($ch, CURLOPT_USERAGENT, $this->user_agent);
	    curl_setopt($ch, CURLOPT_TIMEOUT, 40);
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
	
	    curl_setopt($ch, CURLOPT_URL, $url);
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	    curl_setopt($ch, CURLOPT_REFERER, $url);
	
	    curl_setopt($ch, CURLOPT_HEADER, FALSE);
	    curl_setopt($ch, CURLOPT_USERAGENT, $this->user_agent);
	    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
	    curl_exec($ch);
	
	    curl_setopt($ch,CURLOPT_URL,$url);
	    curl_setopt($ch,CURLOPT_RETURNTRANSFER, 1);
	
	    ob_start();
	    $data = curl_exec($ch);
	    ob_end_clean();
	
	    curl_close($ch);
	    
	    return $data;
	
	}

	/* ========================================
	   GRAB ARRET FROM TF WEBSITE
	==========================================*/
		
	public function getPage($url){
		
		// Curl the url and retrive html
		$content = $this->curl_grab_page($url);
		
		// Parse html 
		return $content;
		
	}
	
	/* ================================================
	   Utils, remove blanks from an array or string
	==================================================*/
	
	public function removeBlank($array){
		
		$notempty = array();
		
		foreach($array as $inner)
		{
			$inner = trim($inner);
			
			if($inner != '')
			{
				$notempty[] = $inner;
			}
		}	
		
		return $notempty;	
	}

    public function removeWhitespace($string) {
     
	    $string = preg_replace('/\s+/', ' ', $string);
	    $string = trim($string);
	    return $string;     
    }
	
}