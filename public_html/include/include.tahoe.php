<?php
if(!isset($_ANONNEWS)) { die(); } // Protect against direct access.
/* This include contains functions related to storage of uploaded files on Tahoe-LAFS. */

function urlsafe_b64encode($string) 
{
	$string = base64_encode($string);
	$string = str_replace(array('+','/','='),array('-','_','='), $string);
	return $string;
}

?>
