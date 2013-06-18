<?php

class Labs_Cache_Controller extends Base_Controller {

	public function get_index()
	{
		// test git
		return "Cache Page";
	}

	public function get_test_cache()
	{
		/*
		$context=stream_context_create(array('http'=>
		    array(
		        'timeout' => 3
		    )
		));
		*/
		
		//$timeout = array('http' => array('timeout' => 1));

		//$context = stream_context_create($timeout);

		//file_get_contents('http://www.laravel3lab.dev/labs/cache/createcache',false,$context);

		$ch = curl_init();
		$headers["User-Agent"] = "Curl/1.0";
		curl_setopt($ch, CURLOPT_URL, "http://www.laravel3lab.dev/labs/cache/postdata?myname=vasupat_chantakeaw");
		curl_setopt($ch, CURLOPT_HEADER, false);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt ($ch, CURLOPT_FOLLOWLOCATION, 0);
		curl_setopt($ch, CURLOPT_NOBODY, false);
		curl_setopt($ch,CURLOPT_TIMEOUT,5);
		
		$response = curl_exec($ch);
		curl_close($ch);
		
		
		//$url = "http://www.laravel3lab.dev/labs/cache/createcache";
		//$params = "true";
		//$command = "curl -d \"$params\" \"$url\" -k &";
		//exec($command);
		
		//exec("curl -d $params $url > /dev/null 2>&1");
		
		//$url = "http://www.laravel3lab.dev/labs/cache/createcache";
		//exec("curl ". $url ." > /dev/null &");
		

		return "cache Ok";
	}

	public function get_createcache()
	{
		for($i=1;$i<=10;$i++)
		{
			$file = fopen(path('app')."views/logs.txt","a");
					fwrite($file,"Hello ". $i ." : ". date('h:i:s') ." !\r\n");
					fclose($file);
			sleep(1);
		}
		return "Create Cache";
	}

	public function get_postdata()
	{

		$mydata = $_GET["myname"];
		$file = fopen(path('app')."views/logs.txt","a");
				fwrite($file, $mydata ." !\r\n");
				fclose($file);
		
		return "Post data";
	}

}