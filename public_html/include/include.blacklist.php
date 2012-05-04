<?php
if(!isset($_ANONNEWS)) { die(); } // Protect against direct access.
/* This include contains functions related to the blacklist. */

function spam_score($url, $title = "", $check_ip = true)
{
	$score = 0;
	
	if($check_ip)
	{
		/* Check DNSBLs */
		if(check_blacklisted())
		{
			/* If a user is blacklisted in a DNSBL, his submission will be
			 * held for manual review. We do not want to assign any further
			 * spam points to this submission to avoid him accidentally
			 * getting blocked, so we return with a score of 5. */
			return 5;
		}
		
		/* Check internal banlist */
		if(check_banlist())
		{
			return 10;
		}
	}
	
	if(!preg_match("/^https?:\/\/([^\/:]*?\.[^\/:]*)(\/|:[0-9]{1,5}|$)/", $url, $matches))
	{
		return 10;
	}
	
	$domain = $matches[1];
	
	if(preg_match("/^[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}$/", $domain))
	{
		$localhost = false;
		$localhost = $localhost || ip_in_range($domain, "10.0.0.0/8");
		$localhost = $localhost || ip_in_range($domain, "127.0.0.1/8");
		$localhost = $localhost || ip_in_range($domain, "172.16.0.0/12");
		$localhost = $localhost || ip_in_range($domain, "192.168.0.0/16");
		
		if($localhost)
		{
			// Adding entries that point to localhost is not allowed.
			return 10;
		}
	}
	
	$domain_parts = explode(".", $domain);
	$top_domain = $domain_parts[count($domain_parts) - 2] . "." . $domain_parts[count($domain_parts) - 1];
	
	if($result = mysql_query_cached("SELECT * FROM blacklist"))
	{
		$blacklist = $result->data;
	}
	else
	{
		return $score;
	}
	
	$banned_domains = array();
	$banned_parts = array();
	$banned_ips = array();
	$banned_titles = array();
	
	foreach($blacklist as $element)
	{
		if($element['Type'] == "0")
		{
			$banned_ips[] = $element['Value'];
		}
		elseif($element['Type'] == "1")
		{
			$banned_parts[] = $element['Value'];
		}
		elseif($element['Type'] == "2")
		{
			$banned_domains[] = $element['Value'];
		}
		elseif($element['Type'] == "3")
		{
			$banned_titles[] = $element['Value'];
		}
	}
	
	$ipList = explode(",", get_ip());
	foreach($ipList as $ip)
	{
		if(in_array($ip, $banned_ips))
		{
			$score += 5;
		}
	}
	
	if(count($domain_parts) >= 3)
	{
		$sub_domain = $domain_parts[count($domain_parts) - 3] . "." . $domain_parts[count($domain_parts) - 2] . "." . $domain_parts[count($domain_parts) - 1];
	}
	else
	{
		$sub_domain = $top_domain;
	}
		
	foreach($banned_domains as $part)
	{
		if(strtolower($part) == strtolower($top_domain) || strtolower($part) == strtolower($sub_domain))
		{
			$score += 10;
		}
		elseif(strpos($url, $part) !== false)
		{
			$score += 5;
		}
	}
	
	foreach($banned_parts as $part)
	{
		if(strpos(strtolower($url), strtolower($part)) !== false)
		{
			$score += 3;
		}
	}
	
	if(!empty($title))
	{
		foreach($banned_titles as $part)
		{
			if(strpos(strtolower($title), strtolower($part)) !== false)
			{
				$score += 3;
			}
		}
	}
	
	return $score;
}
?>
