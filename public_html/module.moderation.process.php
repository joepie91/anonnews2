<?php
if(!isset($_ANONNEWS)) { die(); }  /* Protect from direct requests */

$sId = (is_numeric($var_subpage)) ? $var_subpage : 0;

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
		die();
}

if($var_last == "approve")
{
	$visible = ($sTable == "ext") ? ", `Visible` = '1'" : "";
	$query = "UPDATE {$sTable} SET `Approved` = '1'{$visible}, `Mod` = '{$_SESSION['userid']}' WHERE `Id` = '{$sId}'";
	$message = "The item has been approved.";
}
elseif($var_last == "reject")
{
	$query = "UPDATE {$sTable} SET `Deleted` = '1', `Mod` = '{$_SESSION['userid']}' WHERE `Id` = '{$sId}'";
	$message = "The item has been rejected.";
}

if(!mysql_query($query))
{
	echo("An error occurred.");
}
else
{
	echo($message);
}

?>
