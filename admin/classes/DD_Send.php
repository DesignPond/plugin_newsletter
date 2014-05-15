<?php

class DD_Send {

	private $username; 
	
	private $apikey; 

	function __construct() {
	
		$this->username = 'cindy.leschaud@gmail.com';
	    $this->apikey   = '15663da1-b7ed-4ba5-b305-c807d1d49693';
	}
	
	function sendElasticEmail($to, $subject, $body_text, $body_html, $from, $fromName, $list)
	{
	    $res = "";
	
	    $data  = "username=".urlencode($this->username);
	    $data .= "&api_key=".urlencode($this->apikey);
	    $data .= "&from=".urlencode($from);
	    $data .= "&from_name=".urlencode($fromName);
	    $data .= "&to=".urlencode($to);
	    $data .= "&subject=".urlencode($subject);
	    $data .= "&lists=".urlencode($list);
	    
	    if($body_html)
	    {  
	    	$data .= "&body_html=".urlencode($body_html); 	    
	    }
	    if($body_text)
	    {
	    	$data .= "&body_text=".urlencode($body_text);
	    }
	
	    $header  = "POST /mailer/send HTTP/1.0\r\n";
	    $header .= "Content-Type: application/x-www-form-urlencoded\r\n";
	    $header .= "Content-Length: " . strlen($data) . "\r\n\r\n";
	    
	    $fp = fsockopen('ssl://api.elasticemail.com', 443, $errno, $errstr, 30);
	
	    if(!$fp)
	    {
	        return "ERROR. Could not open connection";
	    }
	    else 
	    {
	        fputs ($fp, $header.$data);
	        
	        while (!feof($fp)) 
	        {
		        $res .= fread ($fp, 1024);
	        }
	        
	        fclose($fp);
	    }
	    
	    return $res;
                      
	}
	
	public function addOrDeleteUserFromList($email,$list,$newsletter){
		
		//set url
		if($newsletter == 'suscribe')
		{
			$url = 'https://api.elasticemail.com/lists/create-contact';		
		}
		
		if($newsletter == 'unsuscribe')
		{
			$url = 'https://api.elasticemail.com/lists/remove-contact';	
		}
		
		//set POST variables
		$fields = array(
			'username' =>urlencode($this->username),
			'api_key'  =>urlencode($this->apikey),
			'email'    =>urlencode($email),
			'listname' =>urlencode($list)
		);
		
		return $this->curlUrl($url,$fields);

	}
	
	public function getStatForNewsletter($newsletter_id){
		
		//set url
		$url = 'https://api.elasticemail.com/mailer/status/'.$newsletter_id;		
		
		//set POST variables
		$fields = array(
			'showstats' => 'true'
		);
		
		return $this->curlUrl($url,$fields);

	}
	
	public function curlUrl($url,$fields){
				
		$fields_string = '';
		
		//url-ify the data for the POST
		foreach($fields as $key => $value) { $fields_string .= $key.'='.$value.'&'; }
		
		rtrim($fields_string,'&');
		
		//open connection
		$ch = curl_init();
		
		//set the url, number of POST vars, POST data
		curl_setopt($ch,CURLOPT_URL,$url);
		curl_setopt($ch,CURLOPT_POST,count($fields));
		curl_setopt($ch,CURLOPT_POSTFIELDS,$fields_string);
		curl_setopt($ch,CURLOPT_RETURNTRANSFER, 1);
		
		//execute post
		$result = curl_exec($ch)	;
			
		curl_close($ch);
		
		return $result;	
	}
	
	public function testIdSend($string)
	{
		$string  = trim($string);
		$string  = str_replace("\r", " ", $string);
		$string  = str_replace("\n", " ", $string);

		$explode = explode(' ', $string);		
		$explode = array_filter($explode);			
		$end     = end($explode);
		
		$end     = preg_replace('/ {2,}/',' ',$end);
		$end     = trim($end);
					
		$matches = null;
		
		$returnValue = preg_match('/^[a-z0-9-]{3,40}/', $end , $matches);
		
		if($matches) { return $end; }
		
		return false;
		
	}
	
	
}