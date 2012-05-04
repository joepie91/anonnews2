<?php
if(!isset($_ANONNEWS)) { die(); }  /* Protect from direct requests */
/* This module handles the thread listing. */

if($var_mode == "thread")
{
	// Post a new thread.
	$categoryname = mysql_real_escape_string($var_id);
	if($result = mysql_query_cached("SELECT * FROM forum_categories WHERE `UrlName`='{$categoryname}'"))
	{
		$category = $result->data[0];
		
		if(!isset($_POST['submit']))
		{
			$catname = utf8entities($var_id);
			$catrealname = utf8entities(stripslashes($category['Name']));
		
		echo("");
			echo("
			<h2><a href=\"/forum\">Forum</a> &gt; <a href=\"/forum/category/{$catname}/\">{$catrealname}</a> &gt; New Thread</h2>
			<div class=\"forum-reply\">
				<form class=\"forum\" method=\"post\" action=\"/forum/category/{$catname}/new\">
					<h3>Name</h3>
					<input type=\"text\" name=\"name\" value=\"Anonymous\">
					
					<h3>Topic Title</h3>
					<input type=\"text\" name=\"topic\">
					
					<h3>Contents</h3>
					<textarea name=\"body\" style=\"height: 350px;\"></textarea>
					<div class=\"forum-reply-button\">
						<button type=\"submit\" name=\"submit\">Create thread &gt;&gt;</button>
						<h3>Complete the captcha</h3>
						" . template_captcha() . "
					</div>
				</form>
			</div>
			");
		}
		else
		{
			$recaptcha = recaptcha_check_answer ($privatekey,
				$_SERVER["REMOTE_ADDR"],
				$_POST["recaptcha_challenge_field"],
				$_POST["recaptcha_response_field"]);
				
			if($recaptcha->is_valid)
			{
				$name = (!empty($_POST['name'])) ? mysql_real_escape_string($_POST['name']) : "Anonymous";
				$body = mysql_real_escape_string($_POST['body']);
				$topic = mysql_real_escape_string($_POST['topic']);
				$catname = mysql_real_escape_string($var_id);
				
				$result = mysql_query_cached("SELECT Id FROM forum_categories WHERE `UrlName`='{$catname}'");
				$catid = $result->data[0]['Id'];

				if(!empty($body))
				{
					if(!empty($topic))
					{
						$parent = $result->data[0];
						
						$query = "INSERT INTO forum_posts (`CategoryId`, `ParentId`, `Name`, `Topic`, `Posted`, `Body`, `Replies`, `LastReplyUser`, `LastReplyTime`)
						VALUES ('{$catid}', '0', '{$name}', '{$topic}', CURRENT_TIMESTAMP, '{$body}', '0', '', CURRENT_TIMESTAMP)";
						if(mysql_query($query))
						{
							$insid = mysql_insert_id();
							mysql_query("UPDATE forum_categories SET `Posts`=`Posts`+1 , `Threads`=`Threads`+1 , `LastPostTime`=CURRENT_TIMESTAMP , `LastPostTopic`='{$topic}' WHERE `Id`='{$catid}'");
							echo("<p><strong>Your post was successful!</strong> It may take a few seconds to appear.</p>
							<p><a href=\"/forum/post/{$insid}/#p-{$insid}\" class=\"page-button\">&lt;&lt; go to thread</a></p>");
						}
						else
						{
							$var_code = ANONNEWS_ERROR_DATABASE_ERROR;
							require("module.error.php");
						}
					}
					else
					{
						$var_code = ANONNEWS_ERROR_POST_TOPIC;
						require("module.error.php");
					}
				}
				else
				{
					$var_code = ANONNEWS_ERROR_POST_BODY;
					require("module.error.php");
				}
			}
			else
			{
				$var_code = ANONNEWS_ERROR_INCORRECT_CAPTCHA;
				require("module.error.php");
			}
		}
	}
}
elseif($var_mode == "reply")
{
	// Post a reply to an existing thread.
	
	$recaptcha = recaptcha_check_answer ($privatekey,
		$_SERVER["REMOTE_ADDR"],
		$_POST["recaptcha_challenge_field"],
		$_POST["recaptcha_response_field"]);
		
	if($recaptcha->is_valid)
	{
		$post_id = (is_numeric($var_id)) ? $var_id : 0;
		$name = (!empty($_POST['name'])) ? mysql_real_escape_string($_POST['name']) : "Anonymous";
		$body = mysql_real_escape_string($_POST['body']);
		
		if(!empty($body))
		{
			if($result = mysql_query_cached("SELECT * FROM forum_posts WHERE `Id`='{$post_id}'"))
			{
				$parent = $result->data[0];
				
				$query = "INSERT INTO forum_posts (`CategoryId`, `ParentId`, `Name`, `Topic`, `Posted`, `Body`, `Replies`, `LastReplyUser`, `LastReplyTime`)
				VALUES ('{$parent['CategoryId']}', '{$post_id}', '{$name}', '', CURRENT_TIMESTAMP, '{$body}', '0', '', CURRENT_TIMESTAMP)";
				if(mysql_query($query))
				{
					$insid = mysql_insert_id();
					$topic = mysql_real_escape_string(stripslashes($parent['Topic']));
					
					mysql_query("UPDATE forum_categories SET `Posts`=`Posts`+1 , `LastPostTime`=CURRENT_TIMESTAMP , `LastPostTopic`='{$topic}' WHERE `Id`='{$parent['CategoryId']}'");
					mysql_query("UPDATE forum_posts SET `Replies`=`Replies`+1 , `LastReplyUser`='{$name}' , `LastReplyTime`=CURRENT_TIMESTAMP WHERE `Id`='{$post_id}'");
					echo("<p><strong>Your post was successful!</strong> It may take a few seconds to appear.</p>
					<p><a href=\"/forum/post/{$post_id}/#p-{$insid}\" class=\"page-button\">&lt;&lt; back to thread</a></p>");
				}
				else
				{
					$var_code = ANONNEWS_ERROR_DATABASE_ERROR;
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
			$var_code = ANONNEWS_ERROR_POST_BODY;
			require("module.error.php");
		}
	}
	else
	{
		$var_code = ANONNEWS_ERROR_INCORRECT_CAPTCHA;
		require("module.error.php");
	}
}
else
{
	$var_code = ANONNEWS_ERROR_NOT_FOUND;
	require("module.error.php");
}
?>
