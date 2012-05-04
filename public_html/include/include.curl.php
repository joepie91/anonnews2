<?php
if(!isset($_ANONNEWS)) { die(); } // Protect against direct access.
/* This include contains functions related to cURL. */

function curl_put($url, $localfile)
{
	$fp = fopen ($localfile, "r"); 
	$ch = curl_init(); 
	curl_setopt($ch, CURLOPT_VERBOSE, 1); 
	curl_setopt($ch, CURLOPT_URL, $url); 
	curl_setopt($ch, CURLOPT_PUT, 1); 
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
	curl_setopt($ch, CURLOPT_INFILE, $fp); 
	curl_setopt($ch, CURLOPT_INFILESIZE, filesize($localfile)); 
	$http_result = curl_exec($ch); 
	$error = curl_error($ch); 
	$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE); 
	curl_close($ch); 
	fclose($fp); 
	if ($error != false || $http_code != 200) 
	{ 
		return false;
	} 
	else
	{
		return $http_result;
	}
}

function curl_head($url)
{
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_HEADER, 1);
	curl_setopt($ch, CURLOPT_NOBODY, 1);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 3);
	curl_setopt($ch, CURLOPT_USERAGENT, 'AnonNews/2.0 Link Validator - http://www.anonnews.org/');
	$return_object->result = curl_exec($ch);
	$error = curl_error($ch); 
	if(empty($error))
	{
		//if(preg_match("HTTP\/[0-9]\.[0-9] ([0-9]{3})", $return_object->result, $matches))
		//if(preg_match("\H", $return_object->result, $matches))
		//if(preg_match("/HTTP\/[0-9]\.[0-9] ([0-9]{3}) (.*?)\n/", $return_object->result, $matches))
		//{
			//$return_object->regex = $matches;
			$return_object->code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
			$return_object->error = "none";
		/*}
		else
		{
			$return_object->regex = $matches;
			$return_object->code = 999;
			$return_object->error = "Could not find a HTTP status code.";
		}*/
	}
	else
	{
		$return_object->code = 998;
		$return_object->error = $error;
	}
	return $return_object;
}

function curl_get($url)
{
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 3);
	curl_setopt($ch, CURLOPT_USERAGENT, 'AnonNews/2.0 Link Validator (Title Fetcher) - http://www.anonnews.org/');
	$return_object->result = curl_exec($ch);
	$error = curl_error($ch);
	if(empty($error))
	{
		$return_object->code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		$return_object->error = "none";
	}
	else
	{
		$return_object->code = 998;
		$return_object->error = $error;
	}
	return $return_object;
}

function curl_post($url, $variables)
{
	if(is_array($variables))
	{
		foreach($variables as $key => $value)
		{
			$variables[$key] = urlencode($value);
			$variable_strings[] = "{$key}={$value}";
		}
		
		$post_string = implode("&", $variable_strings);
	}
	else
	{
		$post_string = "";
	}
	
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_POST, count($variables));
	curl_setopt($ch, CURLOPT_POSTFIELDS, $post_string);
	
	$result = curl_exec($ch);
	
	$error = curl_error($ch);
	if(empty($error))
	{
		return $result;
	}
	else
	{
		return false;
	}
	
	curl_close($ch);
}
?>
