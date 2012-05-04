<?php
/* This handles the new-style pretty URLs. Old-style URLs (with parameters) are handled in index.php. */

$request_uri = substr($_SERVER['REQUEST_URI'], 1);

if(strpos($request_uri, '?') !== false)
{
	$request_uri = substr($request_uri, 0, strpos($request_uri, '?'));
}

$parts = explode("/", $request_uri);
$_REWRITE = true;
$var_include = "internal.php";

$var_section = "home";
$var_table = "";
$var_page = "";
$var_id = "";
$var_subpage = "";
$var_last = "";

$var_start = 0;
$break = false;

//// *** /press/item/241/comments/
//// ***   0     1    2   3

if(isset($parts[0]) && strlen($request_uri) > 1)
{
	if($parts[0] == "localize")
	{
		if(isset($parts[1]) && strlen($parts[1]) > 0)
		{
			$var_lang = $parts[1];
			$_SESSION['curlang'] = $var_lang;
			if(isset($parts[2]) && strlen($parts[2]) > 0)
			{
				$var_start = 2;
			}
			else
			{
				$break = true;
			}
		}
		else
		{
			$var_section = "error";
			$var_code = 404;
			$break = true;
		}
	}
	
	if($break === false)
	{
		$var_section = $parts[$var_start];
		if($var_section == "press" || $var_section == "external-news" || $var_section == "related-sites" || $var_section == "forum" || $var_section == "moderation")
		{
			// Handle functional pages
			if($var_section == "external-news")
			{
				$var_table = "ext";
			}
			elseif($var_section == "related-sites")
			{
				$var_table = "sites";
			}
			else
			{
				$var_table = "press";
			}
			
			if(isset($parts[$var_start + 3]) && strlen($parts[$var_start + 3]) > 0)
			{
				$var_subpage = $parts[$var_start + 3];
			}
			
			if(isset($parts[$var_start + 4]) && strlen($parts[$var_start + 4]) > 0)
			{
				$var_last = $parts[$var_start + 4];
			}
			
			if(isset($parts[$var_start + 1]) && strlen($parts[$var_start + 1]) > 0)
			{
				$var_page = $parts[$var_start + 1];
				
				if(($var_table == "ext" || $var_table == "sites") && $var_page == "item" && $var_subpage != "comments")
				{
					$var_include = "external.php";
				}
			}
			
			if(isset($parts[$var_start + 2]) && strlen($parts[$var_start + 2]) > 0)
			{
				$var_id = $parts[$var_start + 2];
			}
		}
		elseif($var_section != "radio")
		{
			// Handle static pages
			$var_section = "static";
			if(isset($parts[$var_start + 1]))
			{
				$var_table = $parts[$var_start + 1];
			}
			else
			{
				$var_section = "error";
				$var_code = 404;
			}
		}
	}	
}

$_INCLUDED = true;
require($var_include);

?>
