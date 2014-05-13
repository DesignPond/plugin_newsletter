<?php

class Send {

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
		
		$fields_string = '';
		
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
	
	
}