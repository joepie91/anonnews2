<?php
if(!isset($_ANONNEWS)) { die(); }  /* Protect from direct requests */

echo("<h2>Moderation panel</h2>");

echo("<h3>Press releases</h3>");
if($result = mysql_query_cached("SELECT * FROM press WHERE `Approved` = '0' AND `Deleted` = '0' ORDER BY `Posted` ASC LIMIT 30", 2))
{
	foreach($result->data as $item)
	{
		$sTitle = utf8entities(stripslashes($item['Name']));
		$sId = $item['Id'];  // PRIMARY KEY, safe to assign

		echo("<div class=\"mod-item\">
			<a href=\"/press/item/{$sId}/\" target=\"_blank\">{$sTitle}</a>
			<a href=\"/moderation/item/press/{$sId}/approve/\" class=\"mod-approve\">Approve</a>
			<a href=\"/moderation/item/press/{$sId}/reject/\" class=\"mod-reject\">Reject</a>
		</div>");
	}
}
else
{
	echo("No unmoderated press releases.");
}


echo("<div class=\"mod-spacer\"></div><h3>External news sources</h3>");
if($result = mysql_query_cached("SELECT * FROM ext WHERE `Deleted` = '0' AND `Approved` = '0' ORDER BY `Visible` DESC LIMIT 100", 2))
{
	foreach($result->data as $item)
	{
		$sUrl = htmlspecialchars(stripslashes($item['Url']));
		$sTitle = utf8entities(stripslashes($item['Name']));
		$sId = $item['Id'];  // PRIMARY KEY, safe to assign

		echo("<div class=\"mod-item\">
			<a href=\"{$sUrl}\" target=\"_blank\">{$sTitle}</a> 
			<a href=\"/moderation/item/external-news/{$sId}/approve/\" class=\"mod-approve\">Approve</a>
			<a href=\"/moderation/item/external-news/{$sId}/reject/\" class=\"mod-reject\">Reject</a>
			<div class=\"mod-url\">{$sUrl}</div>
		</div>");
	}
}
else
{
	echo("No unmoderated external news sources.");
}



echo("<div class=\"mod-spacer\"></div><h3>Related sites</h3>");
if($result = mysql_query_cached("SELECT * FROM sites WHERE `Deleted` = '0' AND `Approved` = '0' ORDER BY `Id` ASC LIMIT 100", 2))
{
	foreach($result->data as $item)
	{
		$sUrl = htmlspecialchars(stripslashes($item['Url']));
		$sTitle = utf8entities(stripslashes($item['Name']));
		$sId = $item['Id'];  // PRIMARY KEY, safe to assign

		echo("<div class=\"mod-item\">
			<a href=\"{$sUrl}\" target=\"_blank\">{$sTitle}</a> 
			<a href=\"/moderation/item/external-news/{$sId}/approve/\" class=\"mod-approve\">Approve</a>
			<a href=\"/moderation/item/external-news/{$sId}/reject/\" class=\"mod-reject\">Reject</a>
			<div class=\"mod-url\">{$sUrl}</div>
		</div>");
	}
}
else
{
	echo("No unmoderated related sites.");
}

//$result = mysql_query_cached("SELECT * FROM sites WHERE `Approved` = '0'", 2);
?>
