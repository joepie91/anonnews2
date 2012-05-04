<?php
if(!isset($_ANONNEWS)) { die(); } // Protect against direct access.
/* This include contains functions related to UTF8 handling. */

function utf8entities($utf8) 
{
	// Credits to silverbeat@gmx.at (http://www.php.net/manual/en/function.htmlentities.php#96648)
	$encodeTags = true;
	$result = '';
	for ($i = 0; $i < strlen($utf8); $i++) 
	{
		$char = $utf8[$i];
		$ascii = ord($char);
		if ($ascii < 128) 
		{
			$result .= ($encodeTags) ? htmlentities($char) : $char;
		} 
		else if ($ascii < 192) 
		{
			// Do nothing.
		} 
		else if ($ascii < 224) 
		{
			$result .= htmlentities(substr($utf8, $i, 2), ENT_QUOTES, 'UTF-8');
			$i++;
		} 
		else if ($ascii < 240) 
		{
			$ascii1 = ord($utf8[$i+1]);
			$ascii2 = ord($utf8[$i+2]);
			$unicode = (15 & $ascii) * 4096 +
			(63 & $ascii1) * 64 +
			(63 & $ascii2);
			$result .= "&#$unicode;";
			$i += 2;
		} 
		else if ($ascii < 248) 
		{
			$ascii1 = ord($utf8[$i+1]);
			$ascii2 = ord($utf8[$i+2]);
			$ascii3 = ord($utf8[$i+3]);
			$unicode = (15 & $ascii) * 262144 +
			(63 & $ascii1) * 4096 +
			(63 & $ascii2) * 64 +
			(63 & $ascii3);
			$result .= "&#$unicode;";
			$i += 3;
		}
	}
	return $result;
}

?>
