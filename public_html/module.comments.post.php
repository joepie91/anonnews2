<?php
if(!isset($_ANONNEWS)) { die(); }  /* Protect from direct requests */
/* This module handles the posting of comments. */

$var_id = (is_numeric($var_id)) ? $var_id : 0;
$parent = (isset($parts[$var_start + 5]) && is_numeric($parts[$var_start + 5])) ? $parts[$var_start + 5] : 0;


if(!isset($_POST['submit']))
{
	// User does not have javascript, give him a comment form.
	if($parent == 0 || $result = mysql_query_cached("SELECT * FROM comments WHERE `Id`='{$parent}' AND `ItemId`='{$var_id}'"))
	{
		if($result = mysql_query_cached("SELECT Name FROM {$var_table} WHERE `Id`='{$var_id}'"))
		{
			echo("
			<div class=\"c-comment\">
				 <div class=\"c-reply-header\">
					Post a new comment
				 </div>
				 <form method=\"post\" action=\"/{$var_section}/item/{$var_id}/comments/post/{$parent}/\">
					<input type=\"text\" name=\"name\" value=\"Anonymous\" class=\"c-inline\">
					<textarea name=\"body\" class=\"c-inline\"></textarea>
					<div class=\"button\">
						<button type=\"submit\" name=\"submit\">Post comment</button>
					</div>
				</form>
			</div>
			");
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
}
else
{
	// Process a new comment
	$validated = false;
	$error = false;
	
	if(isset($_POST['validate']))
	{
		$posted = false;
	}
	else
	{
		$posted = true;
	}
	
	if($posted === true)
	{
		if(!empty($_POST['name']) || isset($_POST['validate']))
		{
			if(!empty($_POST['body']) || isset($_POST['validate']))
			{
				$name = mysql_real_escape_string($_POST['name']);
				$body = mysql_real_escape_string($_POST['body']);
				$linecount = count(explode("\n", $_POST['body']));
				
				$query = "INSERT INTO comments (`Section`, `ItemId`, `ParentId`, `Name`, `Body`, `Visible`, `LineCount`) VALUES ('{$var_table}', '{$var_id}', '{$parent}', '{$name}', '{$body}', '0', '{$linecount}')";
				if(mysql_query($query))
				{
					$comment_id = mysql_insert_id();
					
					if(!isset($_POST['validate']))
					{
						echo("<strong>Your comment was successfully submitted.</strong> To verify that you are human and not a spambot, complete the following captcha to make your comment visible.<br><br>");
					}
				}
				else
				{
					$error = true;
					$var_code = ANONNEWS_ERROR_DATABASE_ERROR;
					require("module.error.php");
				}
			}
			else
			{
				$error = true;
				$var_code = ANONNEWS_ERROR_COMMENT_BODY;
				require("module.error.php");
			}
		}
		else
		{
			$error = true;
			$var_code = ANONNEWS_ERROR_COMMENT_NAME;
			require("module.error.php");
		}
	}
	
	if($posted === false)
	{
		if(isset($_POST['commentid']) && is_numeric($var_id))
		{
			$recaptcha = recaptcha_check_answer ($privatekey,
				$_SERVER["REMOTE_ADDR"],
				$_POST["recaptcha_challenge_field"],
				$_POST["recaptcha_response_field"]);
			$comment_id = is_numeric($_POST['commentid']) ? $_POST['commentid'] : 0;
			
			if($result = mysql_query_cached("SELECT * FROM comments WHERE `Id`='{$comment_id}'"))
			{
				$linecount = count(explode("\n", $result->data[0]['Body']));
				$charcount = strlen($result->data[0]['Body']);
					
				if($recaptcha->is_valid)
				{
					mysql_query("UPDATE comments SET `Visible`='1' WHERE `Id`='{$comment_id}'");
					
					$res = mysql_query("SELECT COUNT(*) FROM comments WHERE `Section`='{$var_table}' AND `ItemId`='{$var_id}' AND `Visible`='1'");
					$row = mysql_fetch_array($res);
					$total = $row['COUNT(*)'];
					mysql_query("UPDATE {$var_table} SET `CommentCount`='{$total}' WHERE `Id`='{$var_id}'");
					
					if($var_section == "press" && $linecount >= 2 && $charcount >= 100 && isset($_POST['upvote']) && $_POST['upvote'] == "true")
					{
						$res = mysql_query("SELECT Upvotes FROM press WHERE `Id`='{$var_id}'");
						if(mysql_num_rows($res) > 0)
						{
							$row = mysql_fetch_array($res);
							$total = $row['Upvotes'] + 1;
							mysql_query("UPDATE press SET `Upvotes`='{$total}' WHERE `Id`='{$var_id}'");
						}
					}
					
					echo("<strong>Your comment is now visible.</strong>
					<p><a href=\"/{$var_section}/item/{$var_id}/comments/#c-{$comment_id}\" class=\"page-button\"><< back to thread</a></p>");
					render_comments($var_table, $var_id);
					$validated = true;
				}
				else
				{
					echo("<strong>The captcha you entered was incorrect.</strong> Try again.<br><br>");
				}
			}
		}
		else
		{
			$error = true;
			$var_code = ANONNEWS_ERROR_NOT_FOUND;
			require("module.error.php");
		}
	}
	
	if($error === false && ($posted === true || $validated === false))
	{
		if($result = mysql_query_cached("SELECT * FROM comments WHERE `Id`='{$comment_id}'"))
		{
			$linecount = count(explode("\n", $result->data[0]['Body']));
			$charcount = strlen($result->data[0]['Body']);
			
			echo("
			<form method=\"post\" action=\"/{$var_section}/item/{$var_id}/comments/post/{$parent}/\">
				<input type=\"hidden\" name=\"commentid\" value=\"{$comment_id}\">
				<input type=\"hidden\" name=\"validate\" value=\"true\">
				" . template_captcha());
				
				if($var_section == "press" && $linecount >= 2 && $charcount >= 100)
				{
					echo("<br><input type=\"checkbox\" name=\"upvote\" value=\"true\"> Upvote this press release<br><br>");
				}
				
				echo("<button type=\"submit\" name=\"submit\">Verify</button>
			</form>
			");
		}
	}
}

?>
