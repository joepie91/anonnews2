<?php
if(!isset($_ANONNEWS)) { die(); }  /* Protect from direct requests */
/* This module is a wrapper for the moderation panel. */

// /forum/category/1/post/new
//      forum           x             1          category     post            new
//echo("$var_section // $var_table // $var_id // $var_page // $var_subpage // $var_last");

if(isset($_SESSION['loggedin']))
{
	if(empty($var_page))
	{
		require("module.moderation.overview.php");
	}
	elseif($var_page == "item")
	{
		if(empty($var_subpage))
		{
			require("module.moderation.item.php");
		}
		elseif($var_last == "approve" || $var_last == "reject")
		{
			require("module.moderation.process.php");
		}
		else
		{
			$var_code = ANONNEWS_ERROR_NOT_FOUND;
			require("module.error.php");
		}
	}
	elseif($var_page == "blacklist")
	{
		if($_SESSION['accesslevel'] > 5)
		{
			if(empty($var_id))
			{
				require("module.forum.blacklist.overview.php");
			}
			elseif($var_id == "add")
			{
				require("module.forum.blacklist.add.php");
			}
			else
			{
				$var_code = ANONNEWS_ERROR_NOT_FOUND;
				require("module.error.php");
			}
		}
		else
		{
			echo("You do not have sufficient rights to view this page.");
		}
	}
}
else
{
	if($var_page == "login")
	{
		require("module.moderation.login.php");
	}
	else
	{
		echo("<strong>You are not logged in.</strong> Please <a href=\"/moderation/login/\">log in</a> to start moderating.");
	}
}
?>
