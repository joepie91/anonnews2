<?php
/* This page handles the backwards compatibility with the old-style URLs (with parameters).
 * The new-style (pretty) URLs are handled by rewrite.php. */
if(empty($_SERVER['QUERY_STRING']))
{
	require("rewrite.php");
}
else
{
	
	$_INCLUDED = true;
	$var_include = "internal.php";
	
	$var_subpage = "";
	$var_id = "";
	
	if(isset($_GET['p']))
	{
		if($_GET['p'] == "press")
		{
			$var_section = "press";
			$var_table = "press";
			if(isset($_GET['a']))
			{
				$var_page = $_GET['a'];
			}
			else
			{
				$var_page = "list";
				
				if(isset($_GET['s']))
				{
					$var_id = $_GET['s'];
				}
			}
			
			if(isset($_GET['i']))
			{
				$var_id = $_GET['i'];
			}
		}
		elseif($_GET['p'] == "ext")
		{
			$var_section = "external-news";
			$var_table = "ext";
			if(isset($_GET['a']))
			{
				$var_page = $_GET['a'];
			}
			else
			{
				$var_page = "list";
				
				if(isset($_GET['s']))
				{
					$var_id = $_GET['s'];
				}
			}
			
			if(isset($_GET['i']))
			{
				$var_id = $_GET['i'];
			}
		}
		elseif($_GET['p'] == "sites")
		{
			$var_section = "related-sites";
			$var_table = "sites";
			if(isset($_GET['a']))
			{
				$var_page = $_GET['a'];
			}
			else
			{
				$var_page = "list";
				
				if(isset($_GET['s']))
				{
					$var_id = $_GET['s'];
				}
			}
			
			if(isset($_GET['i']))
			{
				$var_id = $_GET['i'];
			}
		}
		elseif($_GET['p'] == "comments")
		{
			if(isset($_GET['c']) && isset($_GET['i']))
			{
				if($_GET['c'] == "press")
				{
					$var_section = "press";
					$var_table = "press";
					$var_page = "item";
					$var_subpage = "comments";
					$var_id = $_GET['i'];
				}
				else
				if($_GET['c'] == "ext")
				{
					$var_section = "external-news";
					$var_table = "ext";
					$var_page = "item";
					$var_subpage = "comments";
					$var_id = $_GET['i'];
				}
				else
				if($_GET['c'] == "sites")
				{
					$var_section = "related-sites";
					$var_table = "sites";
					$var_page = "item";
					$var_subpage = "comments";
					$var_id = $_GET['i'];
				}
				else
				{
					$var_include = "rewrite.php";
				}
			}
			else
			{
				$var_include = "rewrite.php";
			}
		}
		else
		{
			$var_include = "rewrite.php";
		}
	}
	else
	{
		$var_include = "rewrite.php";
	}
	
	require($var_include);
}
?>
