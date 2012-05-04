<?php
$_ANONNEWS = true;
require("include/base.php");

if(isset($_GET['q']))
{
	if($_GET['q'] == "press_overview")
	{
		// We will handle the press release overview separately since it requires two queries rather than one.
		
		$query = "SELECT * FROM press WHERE `Deleted`='0' AND `Approved`='1' AND `Posted` >= DATE_SUB(CURRENT_DATE(), INTERVAL {$recent_days} DAY) ORDER BY `Upvotes` DESC LIMIT 3";
		if($result = mysql_query_cached($query))
		{
			foreach($result->data as $item)
			{
				$name = utf8entities(stripslashes($item['Name']));
				$id = $item['Id'];
				$comments = $item['CommentCount'];
				$upvotes = $item['Upvotes'];
				
				echo(template_item($name, "press", $id, $comments, true, $upvotes, 0));
			}
		}

		$query = "SELECT * FROM press WHERE `Deleted`='0' AND `Approved`='1' ORDER BY `Posted` DESC LIMIT 3";
		if($result = mysql_query_cached($query))
		{
			foreach($result->data as $item)
			{
				$name = utf8entities(stripslashes($item['Name']));
				$id = $item['Id'];
				$comments = $item['CommentCount'];
				$upvotes = $item['Upvotes'];
				
				echo(template_item($name, "press", $id, $comments, false, $upvotes, 0));
			}
		}
	}
	else
	{
		// Process all other queries here.
		
		if($_GET['q'] == "press_top")
		{
			$query = "SELECT * FROM press WHERE `Deleted`='0' AND `Approved`='1' ORDER BY `Upvotes` DESC LIMIT 6";
			$section = "press";
		}
		elseif($_GET['q'] == "press_latest")
		{
			$query = "SELECT * FROM press WHERE `Deleted`='0' AND `Approved`='1' ORDER BY `Posted` DESC LIMIT 6";
			$section = "press";
		}
		elseif($_GET['q'] == "ext_top_7days")
		{
			$query = "SELECT * FROM ext WHERE `Deleted`='0' AND `Visible`='1' AND `Posted` >= DATE_SUB(CURRENT_DATE(), INTERVAL {$recent_days} DAY) ORDER BY `Rank` DESC LIMIT 4";
			$section = "external-news";
		}
		elseif($_GET['q'] == "ext_top_all")
		{
			$query = "SELECT * FROM ext WHERE `Deleted`='0' AND `Visible`='1' ORDER BY `Rank` DESC LIMIT 4";
			$section = "external-news";
		}
		elseif($_GET['q'] == "ext_bottom_7days")
		{
			$query = "SELECT * FROM ext WHERE `Deleted`='0' AND `Visible`='1' AND `Posted` >= DATE_SUB(CURRENT_DATE(), INTERVAL {$recent_days} DAY) ORDER BY `Rank` ASC LIMIT 4";
			$section = "external-news";
		}
		elseif($_GET['q'] == "ext_bottom_all")
		{
			$query = "SELECT * FROM ext WHERE `Deleted`='0' AND `Visible`='1' ORDER BY `Rank` ASC LIMIT 4";
			$section = "external-news";
		}
		
		if($result = mysql_query_cached($query))
		{
			foreach($result->data as $item)
			{
				$name = utf8entities(stripslashes($item['Name']));
				$id = $item['Id'];
				$comments = $item['CommentCount'];
				$rank = ($section == "external-news") ? $item['Rank'] : 0;
				$upvotes = ($section == "press") ? $item['Upvotes'] : 0;
				
				echo(template_item($name, $section, $id, $comments, false, $upvotes, $rank));
			}
		}
		
	}
}
else
{
	die("Error: No valid query was passed on.");
}
/*
if(!isset($_GET['s']) || !isset($_GET['f']) || !isset($_GET['o']) || !isset($_GET['p']))
{
	die("An internal error occurred. Not all variables were set.");
}

if($_GET['s'] == "ext")
{
	$section = "ext";
	$sectionname = "external-news";
	$rules = "WHERE `Deleted`='0'";
}
elseif($_GET['s'] == "sites")
{
	$section = "sites";
	$sectionname = "related-sites";
	$rules = "WHERE `Deleted`='0' AND `Approved`='1'";
}
elseif($_GET['s'] == "press")
{
	$section = "press";
	$sectionname = "press";
	$rules = "WHERE `Deleted`='0' AND `Approved`='1'";
}
else
{
	die("An internal error occurred. 's' was not correctly defined.");
}

if($_GET['o'] == "a")
{
	$order = "ASC";
}
elseif($_GET['o'] == "d")
{
	$order = "DESC";
}
else
{
	die("An internal error occurred. 'o' was not correctly defined.");
}

if($_GET['f'] == "rank")
{
	if($section == "press")
	{
		$field = "Upvotes";
	}
	elseif($section == "ext")
	{
		$field = "Rank";
	}
	else
	{
		die("An internal error occurred. 'fS' was not correctly defined.");
	}
}
elseif($_GET['f'] == "date")
{
	$field = "Posted";
}
else
{
	die("An internal error occurred. 'f' was not correctly defined.");
}

if($_GET['p'] == "all")
{
	$query = $rules;
}
elseif($_GET['p'] == "week")
{
	$query = "{$rules} AND `Posted` >= DATE_SUB(CURRENT_DATE(), INTERVAL 7 DAY)";
}
else
{
	die("An internal error occurred. 'p' was not correctly defined.");
}

if(isset($_GET['l']) && is_numeric($_GET['l']))
{
	$limit = $_GET['l'];
}
else
{
	$limit = "5";
}

$query = "{$query} ORDER BY `{$field}` {$order} LIMIT {$limit}";

if(isset($_GET['hl']))
{
	$highlight = " highlighted";
}
else
{
	$highlight = "";
}

echo("SELECT * FROM {$section} {$query}");

//echo("SELECT * FROM {$section} {$query}");
/*
$result = mysql_query_cached("SELECT * FROM {$section} {$query}");

foreach($result->data as $item)
{
	$name = utf8entities(stripslashes($item['Name']));
	$id = $item['Id'];
	$comments = $item['CommentCount'];
	$upvotes = ($sectionname == "press") ? $item['Upvotes'] : 0;
	$rank = ($sectionname == "ext") ? $item['Rank'] : 0;
	
	echo(template_item($name, $sectionname, $id, $comments, isset($_GET['hl']), $upvotes, $rank));
}*/

?>
