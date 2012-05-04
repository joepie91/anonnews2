<?php
$_ANONNEWS = true;
require("include/base.php");

if(isset($_GET['id']) && isset($_GET['vote']))
{
	if(!isset($_GET['token']) || $_GET['token'] != $_SESSION['vote_token'])
	{
		die("<div class=\"votestate\">X</div>");
	}
	
	$nojs = (isset($_GET['nojs'])) ? true : false;
	$frame = (isset($_GET['frame'])) ? true : false;
	$item_id = (is_numeric($_GET['id'])) ? $_GET['id'] : 0;
	$vote = $_GET['vote'];
	$ip_hash = ip_hash(get_ip());

	if(mysql_num_rows(mysql_query("SELECT Rank FROM ext WHERE `Id`='{$item_id}'")) > 0)
	{
		if(mysql_num_rows(mysql_query("SELECT * FROM votes WHERE `Id`='{$item_id}' AND `Ip`='{$ip_hash}'")) == 0)
		{			
			if($vote == "up")
			{
				mysql_query("UPDATE ext SET `Rank`=`Rank`+1 WHERE `Id`='{$item_id}'");
				
				if(!$nojs)
				{
					echo("<div class=\"votestate\">+</div>");
				}
			}
			elseif($vote == "down")
			{
				mysql_query("UPDATE ext SET `Rank`=`Rank`-1 WHERE `Id`='{$item_id}'");
				
				if(!$nojs)
				{
					echo("<div class=\"votestate\">-</div>");
				}
			}
			else
			{
				die("X");
			}
			
			if($nojs && $frame)
			{
				echo("Your vote was counted.");
			}
			elseif($nojs)
			{
				echo("Your vote was counted. <a href=\"{$referer}\">Click here to go back to the page you came from.</a>");
			}
			
			mysql_query("INSERT INTO votes (`Id`, `Ip`) VALUES ('{$item_id}', '{$ip_hash}')");
		}
		else
		{
			if($nojs && $frame)
			{
				echo("You already voted.");
			}
			elseif($nojs)
			{
				header("Location: {$referer}");
			}
			else
			{
				die("<div class=\"votestate\">X</div>");
			}
		}
	}
}
?>
