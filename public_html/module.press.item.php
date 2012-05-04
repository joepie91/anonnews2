<?php
if(!isset($_ANONNEWS)) { die(); }  /* Protect from direct requests */
/* This module shows a specific press release. */

if(is_numeric($var_id))
{
	$id = mysql_real_escape_string($var_id);
	
	$approved = " AND `Approved` = '1'";
	
	if(isset($_SESSION['loggedin']))
	{
		$approved = "";
	}
	
	$query = "SELECT Name, Body, Attachment, ExternalAttachment, CommentCount FROM press WHERE `Id` = '$id'{$approved}";
	if($result = mysql_query_cached($query))
	{
		$title = utf8entities(stripslashes($result->data[0]['Name']));
		$body = youtubify(filter_extended(stripslashes($result->data[0]['Body'])));
		$externalattachment = $result->data[0]['ExternalAttachment'];
		$attachment = utf8entities(stripslashes($result->data[0]['Attachment']));
		$commentcount = $result->data[0]['CommentCount'];
			
		echo("<div class=\"pressrelease\">
			<a class=\"hl-comments\" href=\"/press/item/{$id}/comments/\"><strong>Join the conversation!</strong> {$commentcount} comment(s) already posted - click to post one yourself, anonymously of course.</a>
			<h5>$title</h5>");

		if(!empty($attachment))
		{
			if($externalattachment == 1)
			{
				$image = $tahoe_gateway . $attachment;
			}
			else
			{
				$image = "/" . $attachment;
			}
			
			echo("<div class=\"pressrelease-image\">
				<img src=\"$image\" alt=\"$title image\" width=\"900\">
			</div>");
		}
						
		echo("$body
			<a class=\"hl-comments\" href=\"/press/item/{$id}/comments/\"><strong>Join the conversation!</strong> {$commentcount} comment(s) already posted - click to post one yourself, anonymously of course.</a>
			</div>
			<script type=\"text/javascript\">
				$(function(){
					$(document).ready(function(){
						//resizeImage();
					});
				});
			</script>");	
	}
	else
	{
		$var_code = ANONNEWS_ERROR_NOT_FOUND;
		require("module.error.php");
	}
}
else
{
	$var_code = ANONNEWS_ERROR_NOT_FOUND;
	require("module.error.php");
}
?>
