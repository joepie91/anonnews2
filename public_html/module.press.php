<?php
if(!isset($_ANONNEWS)) { die(); }  /* Protect from direct requests */
/* This module is a wrapper for all press release-related modules. */

if($var_page == "")
{
	header("Location: /press/list");
}
elseif($var_page == "list")
{
	require("module.press.list.php");
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
	elseif($var_subpage == "")
	{
		require("module.press.item.php");
	}
	else
	{
		$var_code = ANONNEWS_ERROR_NOT_FOUND;
		require("module.error.php");
	}
}
elseif($var_page == "add")
{
	require("module.press.add.php");
}
else
{
	$var_code = ANONNEWS_ERROR_NOT_FOUND;
	require("module.error.php");
}
?>
