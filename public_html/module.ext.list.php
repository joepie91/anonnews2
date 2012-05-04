<?php
if(!isset($_ANONNEWS)) { die(); }  /* Protect from direct requests */
/* This module lists all press releases in a specified order. */

/* Note: internal page numbering starts at 0. */

$var_sort = "date";

if(!empty($var_id))
{
	if($var_id == "rank")
	{
		$var_sort = "rank";
	}
}

$var_sortdir = "desc";

if(!empty($var_subpage))
{
	if($var_subpage == "asc")
	{
		$var_sortdir = "asc";
	}
}

$query = "SELECT COUNT(*) FROM ext WHERE `Deleted` = '0' AND `Visible` = '1'";

if($result = mysql_query_cached($query))
{
	if(isset($result->data[0]))
	{
		$total_records = $result->data[0]['COUNT(*)'];
		
		$per_page = 20;

		if(!empty($var_last) && is_numeric($var_last) && $var_last > 0)
		{
			$var_page = mysql_real_escape_string($var_last - 1);
		}
		else
		{
			$var_page = 0;
		}

		$start = $var_page * $per_page;
		
		$last_page = floor($total_records / $per_page);
		
		if($start >= $total_records)
		{
			$start = $total_pages * $per_page;
		}
		
		$page_list = "";
		
		if($var_page > 0)
		{
			$p = $var_page;
			$page_list .= "<a href=\"/external-news/list/{$var_sort}/{$var_sortdir}/{$p}/\"><< previous</a><span class=\"spacer\"> </span>";
		}
		
		for($i = 0; $i <= $last_page; $i++)
		{
			$p = $i + 1;
			$current = ($var_page == $i) ? " class=\"current\"" : "";
			$page_list .= "<a href=\"/external-news/list/{$var_sort}/{$var_sortdir}/{$p}/\"{$current}>{$p}</a><span class=\"spacer\"> </span>";
		}
		
		if($var_page < $last_page)
		{
			$p = $var_page + 2;
			$page_list .= "<a href=\"/external-news/list/{$var_sort}/{$var_sortdir}/{$p}/\">next >></a>";
		}
		
		$query_sort = ($var_sort == "date") ? "Posted" : "Rank";
		$query_dir = ($var_sortdir == "asc") ? "ASC" : "DESC";
		$query = "SELECT Id, Name, CommentCount, Rank FROM ext WHERE `Visible` = '1' AND `Deleted` = '0' ORDER BY `{$query_sort}` {$query_dir} LIMIT {$start},{$per_page}";

		if($result = mysql_query_cached($query))
		{
			$style[0] = ($var_sort == "date" && $var_sortdir == "desc") ? " class=\"active\"" : "";
			$style[1] = ($var_sort == "date" && $var_sortdir == "asc") ? " class=\"active\"" : "";
			$style[2] = ($var_sort == "rank" && $var_sortdir == "desc") ? " class=\"active\"" : "";
			$style[3] = ($var_sort == "rank" && $var_sortdir == "asc") ? " class=\"active\"" : "";
			
			echo("<div class=\"sort-options\">
				Sort order:
				<a href=\"/external-news/list/date/desc/\"{$style[0]}>Newest first</a>
				<a href=\"/external-news/list/date/asc/\"{$style[1]}>Oldest first</a>
				<a href=\"/external-news/list/rank/desc/\"{$style[2]}>Highest ranked first</a>
				<a href=\"/external-news/list/rank/asc/\"{$style[3]}>Lowest ranked first</a>
			</div>
			<div class=\"clear\"></div>");
		
			echo("<div class=\"page-list page-list-top\">
				{$page_list}
			</div>");
		
			foreach($result->data as $item)
			{
				$name = utf8entities(stripslashes($item['Name']));
				$id = $item['Id'];
				$comments = $item['CommentCount'];
				$rank = $item['Rank'];
				
				echo(template_item($name, "external-news", $id, $comments, false, 0, $rank));
			}
			
			echo("<div class=\"page-list page-list-bottom\">
				{$page_list}
			</div>");
		}
		else
		{
			$var_code = ANONNEWS_ERROR_DATABASE_ERROR;
			require("module.error.php");
		}
	}
	else
	{
		$var_code = ANONNEWS_ERROR_MALFORMED_DATA;
		require("module.error.php");
	}
}
else
{
	$var_code = ANONNEWS_ERROR_DATABASE_ERROR;
	require("module.error.php");
}
?>
