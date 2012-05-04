<?php
if(!isset($_ANONNEWS)) { die(); }  /* Protect from direct requests */

$sId = (is_numeric($var_page)) ? $var_page : 0;

switch($var_id)
{
	case "external-news":
		$sTable = "ext";
		break;
	case "press":
		$sTable = "press";
		break;
	case "related-sites":
		$sTable = "sites";
		break;
	default:
		die($var_id);
}

mysql_query("UPDATE {$sTable} SET `Approved` = '1' WHERE `Id` = '{$sId}'");

?>
