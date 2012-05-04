<?php
if(!isset($_ANONNEWS)) { die(); } // Protect against direct access.
/* This include contains functions related to memcached. */

if($memcache_enabled)
{
	$memcache = new Memcache;
	$memcache_established = $memcache->connect($memcache_server, $memcache_port);

	if($memcache_established !== false)
	{
		$memcache_connected = true;
	}
	else
	{
		$memcache_connected = false;
	}
}

function mc_get($key)
{
	global $memcache_enabled, $memcache_connected, $memcache;
	
	if($memcache_enabled === false || $memcache_connected === false)
	{
		return false;
	}
	else
	{
		$get_result = $memcache->get($key);
		if($get_result !== false)
		{
			return $get_result;
		}
		else
		{
			return false;
		}
	}
}

function mc_set($key, $value, $expiry)
{
	global $memcache_enabled, $memcache_connected, $memcache_compressed, $memcache;
	
	if($memcache_enabled === false || $memcache_connected === false)
	{
		return false;
	}
	else
	{
		if($memcache_compressed === true)
		{
			$flag = MEMCACHE_COMPRESSED;
		}
		else
		{
			$flag = false;
		}
		
		$set_result = $memcache->set($key, $value, $flag, $expiry);
		return $set_result;
	}
}

function mc_delete($key)
{
	global $memcache_enabled, $memcache_connected, $memcache;
	
	if($memcache_enabled === false || $memcache_connected === false)
	{
		return false;
	}
	else
	{
		return $memcache->delete($key);
	}
}

function mysql_query_cached($query, $expiry = 60)
{
	if($res = mc_get(md5($query) . md5($query . "x")))
	{
		$return_object->source = "memcache";
		$return_object->data = $res;
		return $return_object;
	}
	else
	{
		if($res = mysql_query($query))
		{
			$found = false;
			
			while($row = mysql_fetch_assoc($res))
			{
				$return_object->data[] = $row;
				$found = true;
			}
			
			if($found === true)
			{
				$return_object->source = "database";
				mc_set(md5($query) . md5($query . "x"), $return_object->data, $expiry);
				return $return_object;
			}
			else
			{
				return false;
			}
		}
		else
		{
			return false;
		}
	}
}

function file_get_contents_cached($path, $expiry = 3600)
{
	if($res = mc_get(md5($path) . md5($path . "x")))
	{
		$return_object->source = "memcache";
		$return_object->data = $res;
		return $return_object;
	}
	else
	{
		if($result = file_get_contents($path))
		{
			$return_object->source = "disk";
			$return_object->data = $result;
			mc_set(md5($path) . md5($path . "x"), $return_object->data, $expiry);
			return $return_object;
		}
		else
		{
			return false;
		}
	}
}
?>
