<?php
/* This file processes external URLs, showing them in a frameset.
 * It does not have to be included. */

if(isset($_INCLUDED))
{
	// Output frameset.
	if(!is_numeric($var_id))
	{
		die();
	}
	
	$_ANONNEWS = true;
	require("include/base.php");
	
	$id = mysql_real_escape_string($var_id);
	
	$query = "SELECT Name, Url FROM $var_table WHERE `Id` = '$id'";
	if(!$result = mysql_query_cached($query))
	{
		header("Location: /");
		die();
	}
	?>
	<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Frameset//EN" "http://www.w3.org/TR/html4/frameset.dtd">
	<html>
		<head>
			<title>AnonNews - <?php echo(utf8entities(stripslashes($result->data[0]['Name']))); ?></title>
			<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		</head>
		<frameset framespacing="0" rows="40,*" frameborder="0" noresize>
			<frame name="top" src="/external.php?table=<?php echo($var_table); ?>&id=<?php echo(utf8entities($var_id)); ?>" target="top">
			<frame name="main" src="<?php echo(utf8entities(stripslashes($result->data[0]['Url']))); ?>" target="main">
		</frameset>
	</html>
	<?php
}
else
{
	if(empty($_GET['id']) || !is_numeric($_GET['id']) || empty($_GET['table']))
	{
		die();
	}
	
	$_ANONNEWS = true;
	require("include/base.php");
	
	if($_GET['table'] == "ext")
	{
		$table = "ext";
		$var_section = "external-news";
	}
	elseif($_GET['table'] == "sites")
	{
		$table = "sites";
		$var_section = "related-sites";
	}
	else
	{
		die();
	}
	
	$id = mysql_real_escape_string($_GET['id']);
	
	$query = "SELECT Id, Name, Url, CommentCount FROM $table WHERE `Id` = '$id'";
	if(!$result = mysql_query_cached($query))
	{
		die("The article you requested could not be found. <a href=\"/\">Click here to go back to the front page.</a>");
	}
	?>
	
	<!doctype html>
	<html>
		<head>
			<title><?php echo(utf8entities(stripslashes($result->data[0]['Name']))); ?></title>
			<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
			<style>
				body,html
				{
					background-color: #EBEBEB;
					font-family: Verdana, Arial;
					margin: 0px;
					padding: 0px;
				}
				
				h1
				{
					font-size: 24px;
					margin: 5px;
					float: left;
				}
				
				div.border
				{
					height: 2px;
					background-color: black;
					position: absolute;
					bottom: 0px;
					left: 0px;
					right: 0px;
				}
				
				a
				{
					text-decoration: none;
					color: black;
				}
				
				a.button, div.button
				{
					display: block;
					float: right;
					height: 13px;
					font-size: 13px;
					padding-top: 10px;
					padding-bottom: 15px;
					padding-left: 15px;
					padding-right: 15px;
					border-left: 1px solid black;
					text-decoration: none;
					color: black;
				}
				
				a.button:hover
				{
					background-color: #E1E1E1;
				}
				
				a.comments
				{
					font-weight: bold;
				}
				
				a.down
				{
					color: red;
				}
				
				a.up
				{
					color: green;
				}
				
				a.down, a.up
				{
					padding-top: 5px;
					padding-bottom: 20px;
				}
				
				span.icon
				{
					font-size: 150%;
					vertical-align: -15%;
				}
				
				.votestate
				{
					float: right;
					padding: 11px;
					padding-top: 8px;
					border-left: 1px solid black;
				}
			</style>
			<script type="text/javascript" src="http://tahoe-gateway.cryto.net:3719/download/VVJJOkNISzpraHNjaHNuaGtwc3NhaHh2aHVvNnFjb2xleTp5M2N2Mm1rYXZ2eWVsZ3A1cGx3ejR0NG9rZnJpbjRiYWx1dG9paHc0ZGFsZnVjZmI0amlhOjM6Njo5MTU1Ng==/jquery-1.6.2.min.js"></script>
			<script type="text/javascript" src="/script2.js"></script>
			<script type="text/javascript">
				function clearButtons(text)
				{
					$('#voting').html("<div class='button'><strong>" + text + "</strong></div>");
				}
			</script>
		</head>
		<body>
			<h1><a href="/" target="_top">AnonNews</a></h1>
			<a href="/" target="_top" class="button home">back to homepage</a>
			<?php
			echo("<a href=\"/{$var_section}/item/{$result->data[0]['Id']}/comments\" target=\"_blank\" class=\"button comments\">{$result->data[0]['CommentCount']} comments</a>");
			if($table == "ext")
			{
				echo("<span id=\"voting\" class=\"votebuttons{$result->data[0]['Id']}\">
					<a href=\"process.vote.php?id={$result->data[0]['Id']}&vote=up&nojs=true&frame=true&token={$_SESSION['vote_token']}\" target=\"_blank\" onclick=\"return voteUp({$result->data[0]['Id']}, '{$_SESSION['vote_token']}');\" class=\"button up\"><span class=\"icon\">+</span> vote up</a>
					<a href=\"process.vote.php?id={$result->data[0]['Id']}&vote=down&nojs=true&frame=true&token={$_SESSION['vote_token']}\" target=\"_blank\" onclick=\"return voteDown({$result->data[0]['Id']}, '{$_SESSION['vote_token']}');\" class=\"button down\"><span class=\"icon\">-</span> vote down</a>
				</span>");
			}
			echo("<a href=\"" . utf8entities(stripslashes($result->data[0]['Url'])) . "\" target=\"_blank\" class=\"button comments\">Doesn't work?</a>");
			?>
			<div class="border"></div>
		</body>
	</html>
	<?php
}

?>




