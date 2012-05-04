<?php
if(!isset($_ANONNEWS)) { die(); } // Protect against direct access.
/* This include contains functions related to string handling. */

function ends_with($haystack, $needle)
{
	if(substr($haystack, strlen($haystack) - strlen($needle), strlen($needle)) == $needle)
	{
		return true;
	}
	else
	{
		return false;
	}
}

function split_if_present($haystack, $needle)
{
	if(strpos($haystack, $needle) !== false)
	{
		return explode($needle, $haystack);
	}
	else
	{
		return false;
	}
}

function suggest_title($title)
{
	if(($parts = split_if_present($title, " | ")) || 
		($parts = split_if_present($title, "&raquo;")) || 
		($parts = split_if_present($title, " : ")) || 
		($parts = split_if_present($title, " &gt; ")) || 
		($parts = split_if_present($title, " / ")) || 
		($parts = split_if_present($title, " - ")))
	{
		$maxlen = 0;
		$highest = 0;
		for($i = 0; $i < count($parts); $i++)
		{
			$len = strlen($parts[$i]);
			if($len > $maxlen)
			{
				$maxlen = $len;
				$highest = $i;
			}
		}
		return trim($parts[$highest]);
	}
	else
	{
		return $title;
	}
}

function split_lines($input)
{
	return explode("\n", str_replace("\r", "", $input));
}

function utf8_entities_if_needed($input)
{
	if(strpos($input, ">") !== false || strpos($input, "<") !== false)
	{
		return utf8entities($input);
	}
	else
	{
		return $input;
	}
}

function arraytolower($array)
{ 
	//return unserialize(strtolower(serialize($array))); 
	return $array;
} 

function clean_tag($tag)
{
	return preg_replace("/[^a-zA-Z0-9']/", "", normalize_chars($tag));
}

/* Thanks to highstrike at gmail dot com (http://www.php.net/manual/en/function.substr.php#80247) */
function cut_text($value, $length)
{    
    if(is_array($value)) list($string, $match_to) = $value;
    else { $string = $value; $match_to = $value{0}; }

    $match_start = stristr($string, $match_to);
    $match_compute = strlen($string) - strlen($match_start);

    if (strlen($string) > $length)
    {
        if ($match_compute < ($length - strlen($match_to)))
        {
            $pre_string = substr($string, 0, $length);
            $pos_end = strrpos($pre_string, " ");
            if($pos_end === false) $string = $pre_string."...";
            else $string = substr($pre_string, 0, $pos_end)."...";
        }
        else if ($match_compute > (strlen($string) - ($length - strlen($match_to))))
        {
            $pre_string = substr($string, (strlen($string) - ($length - strlen($match_to))));
            $pos_start = strpos($pre_string, " ");
            $string = "...".substr($pre_string, $pos_start);
            if($pos_start === false) $string = "...".$pre_string;
            else $string = "...".substr($pre_string, $pos_start);
        }
        else
        {        
            $pre_string = substr($string, ($match_compute - round(($length / 3))), $length);
            $pos_start = strpos($pre_string, " "); $pos_end = strrpos($pre_string, " ");
            $string = "...".substr($pre_string, $pos_start, $pos_end)."...";
            if($pos_start === false && $pos_end === false) $string = "...".$pre_string."...";
            else $string = "...".substr($pre_string, $pos_start, $pos_end)."...";
        }

        $match_start = stristr($string, $match_to);
        $match_compute = strlen($string) - strlen($match_start);
    }
    
    return $string;
}

function normalize_chars($str)
{
	$charmap = array(
    'Š'=>'S', 'š'=>'s', 'Ð'=>'Dj','Ž'=>'Z', 'ž'=>'z', 'À'=>'A', 'Á'=>'A', 'Â'=>'A', 'Ã'=>'A', 'Ä'=>'A', 
    'Å'=>'A', 'Æ'=>'A', 'Ç'=>'C', 'È'=>'E', 'É'=>'E', 'Ê'=>'E', 'Ë'=>'E', 'Ì'=>'I', 'Í'=>'I', 'Î'=>'I', 
    'Ï'=>'I', 'Ñ'=>'N', 'Ò'=>'O', 'Ó'=>'O', 'Ô'=>'O', 'Õ'=>'O', 'Ö'=>'O', 'Ø'=>'O', 'Ù'=>'U', 'Ú'=>'U', 
    'Û'=>'U', 'Ü'=>'U', 'Ý'=>'Y', 'Þ'=>'B', 'ß'=>'Ss','à'=>'a', 'á'=>'a', 'â'=>'a', 'ã'=>'a', 'ä'=>'a', 
    'å'=>'a', 'æ'=>'a', 'ç'=>'c', 'è'=>'e', 'é'=>'e', 'ê'=>'e', 'ë'=>'e', 'ì'=>'i', 'í'=>'i', 'î'=>'i', 
    'ï'=>'i', 'ð'=>'o', 'ñ'=>'n', 'ò'=>'o', 'ó'=>'o', 'ô'=>'o', 'õ'=>'o', 'ö'=>'o', 'ø'=>'o', 'ù'=>'u', 
    'ú'=>'u', 'û'=>'u', 'ý'=>'y', 'ý'=>'y', 'þ'=>'b', 'ÿ'=>'y', 'ƒ'=>'f', 'č'=>'c'
	);
	
	return strtr($str, $charmap);
}

function random_string($length)
{
	$output = "";
	for ($i = 0; $i < $length; $i++) 
	{ 
		$output .= substr("ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789", mt_rand(0, 61), 1); 
	}
	return $output;
}
?>
