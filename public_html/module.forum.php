<?php
if(!isset($_ANONNEWS)) { die(); }  /* Protect from direct requests */
/* This module is a wrapper for the forum. */

// /forum/category/1/post/new
//      forum           x             1          category     post            new
//echo("$var_section // $var_table // $var_id // $var_page // $var_subpage // $var_last");

if(empty($var_page))
{
	require("module.forum.overview.php");
}
elseif($var_page == "category")
{
	if(empty($var_subpage))
	{
		require("module.forum.category.php");
	}
	elseif($var_subpage == "new")
	{
		$var_mode = "thread";
		require("module.forum.post.php");
	}
	else
	{
		$var_code = ANONNEWS_ERROR_NOT_FOUND;
		require("module.error.php");
	}
}
elseif($var_page == "post")
{
	if(empty($var_subpage))
	{
		require("module.forum.view.php");
	}
	elseif($var_subpage == "reply")
	{
		$var_mode = "reply";
		require("module.forum.post.php");
	}
	else
	{
		$var_code = ANONNEWS_ERROR_NOT_FOUND;
		require("module.error.php");
	}
}
?>
