<?php
if(!isset($_ANONNEWS)) { die(); }  /* Protect from direct requests */
/* This module handles the thread listing. */
$post_id = (is_numeric($var_id)) ? $var_id : 0;

if($result = mysql_query_cached("SELECT * FROM forum_posts WHERE `Id`='{$var_id}' AND `ParentId`='0'", 5))
{
	$post = $result->data[0];
	
	$query = "SELECT * FROM forum_categories WHERE `Id`='{$post['CategoryId']}'";
	if($category = mysql_query_cached($query)->data[0])
	{
		$topic = utf8entities(stripslashes($post['Topic']));
		
		$caturlname = utf8entities(stripslashes($category['UrlName']));
		$catname = utf8entities(stripslashes($category['Name']));
		
		echo("<h2><a href=\"/forum\">Forum</a> &gt; <a href=\"/forum/category/{$caturlname}/\">{$catname}</a> &gt; {$topic}</h2>");
		
		echo(template_post($post));
		
		$query = "SELECT * FROM forum_posts WHERE `ParentId`='{$post['Id']}'";
		if($children = mysql_query_cached($query, 5))
		{
			foreach($children->data as $child)
			{
				echo(template_post($child));
			}
		}
		
		echo("<div class=\"forum-reply\">
			<h3>Post a reply</h3>
			<form class=\"forum\" method=\"post\" action=\"/forum/post/{$var_id}/reply\">
				<input type=\"text\" name=\"name\" value=\"Anonymous\">
				<textarea name=\"body\"></textarea>
				<div class=\"forum-reply-button\">
					<button type=\"submit\" name=\"submit\">Post reply &gt;&gt;</button>
					" . template_captcha() . "
				</div>
			</form>
		</div>");
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
