<?php
if(!isset($_ANONNEWS)) { die(); } // Protect against direct access.
/* This include contains functions related to IP addresses. */

function ip_in_range($ip, $range)
{
	/* Thanks to Ian B (http://www.php.net/manual/en/function.ip2long.php#71939) */
	
	$ip = str_replace("::ffff:", "", $ip);
	
	list($base, $bits) = explode('/', $range);
	list($a, $b, $c, $d) = explode('.', $base);
	$i = ($a << 24) + ($b << 16) + ($c << 8) + $d;
	$mask = $bits == 0 ? 0 : (~0 << (32 - $bits));
	$low = $i & $mask;
	$high = $i | (~$mask & 0xFFFFFFFF);
	list($a, $b, $c, $d) = explode('.', $ip);
	$check = ($a << 24) + ($b << 16) + ($c << 8) + $d;
	if ($check >= $low && $check <= $high)
	{
		return true;
	}
	else
	{
		return false;
	}
}

function is_reverse_proxied()
{
	$reverseProxied = false;
	// TODO multiple ips!
	if(!empty($_SERVER['HTTP_X_FORWARDED_FOR']) || !empty($_SERVER['HTTP_FORWARDED_FOR']) || !empty($_SERVER['HTTP_CLIENT_IP']) || !empty($_SERVER['HTTP_X_CLUSTER_CLIENT_IP']))
	{
		$ip = $_SERVER['REMOTE_ADDR'];
		// First check for requests that originate from localhost
		$reverseProxied = $reverseProxied || ip_in_range($ip, "10.0.0.0/8");
		$reverseProxied = $reverseProxied || ip_in_range($ip, "127.0.0.1/8");
		$reverseProxied = $reverseProxied || ip_in_range($ip, "172.16.0.0/12");
		$reverseProxied = $reverseProxied || ip_in_range($ip, "192.168.0.0/16");
		
		// Then check for CloudFlare
		$reverseProxied = $reverseProxied || ip_in_range($ip, "204.93.240.0/24");
		$reverseProxied = $reverseProxied || ip_in_range($ip, "204.93.177.0/24");
		$reverseProxied = $reverseProxied || ip_in_range($ip, "199.27.128.0/21");
		$reverseProxied = $reverseProxied || ip_in_range($ip, "173.245.48.0/20");
		$reverseProxied = $reverseProxied || ip_in_range($ip, "103.22.200.0/22");
		$reverseProxied = $reverseProxied || ip_in_range($ip, "141.101.64.0/18");
		
		if(!empty($proxy_ranges))
		{
			foreach($proxy_ranges as $proxy_range)
			{
				$reverseProxied = $reverseProxied || ip_in_range($ip, $proxy_range);
			}
		}
	}
	
	return $reverseProxied;
}

function get_ip()
{
	if(is_reverse_proxied())
	{
		if(!empty($_SERVER['HTTP_X_FORWARDED_FOR']))
		{
			$result = $_SERVER['HTTP_X_FORWARDED_FOR'];
		}
		elseif(!empty($_SERVER['HTTP_FORWARDED_FOR']))
		{
			$result = $_SERVER['HTTP_FORWARDED_FOR'];
		}
		elseif(!empty($_SERVER['HTTP_CLIENT_IP']))
		{
			$result = $_SERVER['HTTP_CLIENT_IP'];
		}
		elseif(!empty($_SERVER['HTTP_X_CLUSTER_CLIENT_IP']))
		{
			$result = $_SERVER['HTTP_X_CLUSTER_CLIENT_IP'];
		}
		else
		{
			$result = $_SERVER['REMOTE_ADDR'];
		}
	}
	else
	{
		$result = $_SERVER['REMOTE_ADDR'];
	}
	
	return $result;
}

function check_banlist()
{
	global $bannedIps;
	$ipList = explode(",", get_ip());
	foreach($ipList as $ip)
	{
		if(isset($bannedIps[trim($ip)]))
		{
			return true;  // Banned.
		}
	}
	return false;  // Not banned.
}

function check_blacklisted($ip = null)
{
	/* Thanks to Rene Moser (http://www.renemoser.net/) */
	if($ip == null)
	{
		$ip = get_ip();
	}
	
	$dns_black_lists = file('dnsbl/dnsbl.txt', FILE_IGNORE_NEW_LINES);
	$rev_ip = implode(array_reverse(explode('.', $ip)), '.');
	$response = array();
	foreach ($dns_black_lists as $dns_black_list) 
	{
		$response = (gethostbynamel($rev_ip . '.' . $dns_black_list));
		if (!empty($response)) 
		{
			return true;
		} 
	}
	return false;
}

function ip_hash($ip)
{
	$ip = str_replace("::ffff:", "", $ip);
	list($a, $b, $c, $d) = explode(".", $ip);
	$e = ($d < 128) ? 0 : 128;
	return sha1("{$a}.{$b}.{$c}.{$e}");
} 
?>
