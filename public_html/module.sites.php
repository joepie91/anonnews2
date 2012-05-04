<?php
if(!isset($_ANONNEWS)) { die(); }  /* Protect from direct requests */
/* This module is a wrapper for all modules related to the 'related sites' section. */

if($var_page == "")
{
	header("Location: /sites/list");
}
elseif($var_page == "list")
{
	require("module.sites.list.php");
}
elseif($var_page == "item")
{
	if($var_subpage == "comments")
	{
		$var_post = (isset($parts[$var_start + 4])) ? $parts[$var_start + 4] : "";

		if(!empty($var_post) && $var_post == "post")
		{
			require("module.comments.post.php");
		}
		else
		{
			require("module.comments.php");
		}
	}
	else
	{
		$var_code = ANONNEWS_ERROR_NOT_FOUND;
		require("module.error.php");
	}
}
elseif($var_page == "add")
{
	require("module.sites.add.php");
}
else
{
	$var_code = ANONNEWS_ERROR_NOT_FOUND;
	require("module.error.php");
}
?>
