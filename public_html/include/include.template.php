<?php
if(!isset($_ANONNEWS)) { die(); } // Protect against direct access.
/* This include contains template functions for site elements. */

function template_item($title, $section, $id, $comments, $highlight = false, $upvotes = 0, $rank = 0)
{
	global $lang;
	
	$var_highlight = ($highlight === true) ? " highlighted" : "";
	$var_target = ($section == "external-news") ? " target=\"_blank\"" : "";
	
	$output = "<div class=\"item{$var_highlight}\">
		<a href=\"/{$section}/item/{$id}/\" class=\"name\"{$var_target}>{$title}</a>
		<span class=\"votes\">";
		
	if($section == "external-news")
	{
		$output .= "<span class=\"votebuttons{$id}\">
			<a rel=\"nofollow\" href=\"/process.vote.php?id={$id}&vote=down&nojs=true&token={$_SESSION['vote_token']}\" onclick=\"return voteDown({$id}, '{$_SESSION['vote_token']}');\" class=\"minus\">-</a>
			<a rel=\"nofollow\" href=\"/process.vote.php?id={$id}&vote=up&nojs=true&token={$_SESSION['vote_token']}\" onclick=\"return voteUp({$id}, '{$_SESSION['vote_token']}');\" class=\"plus\">+</a>
		</span>
		<span class=\"votecount votecount{$id}\" id=\"vote{$id}\">{$rank}</span>";
	}
	
	$output .= "<a class=\"comments\" href=\"/{$section}/item/{$id}/comments/\" target=\"_blank\">
				<span class=\"count\">{$comments}</span>
				<span class=\"under\">{$lang[35]}</span>
			</a>";
			
	if($section == "press")
	{
		$output .= "<div class=\"upvotes\">+{$upvotes}</div>";
	}
			
	$output .= "</span>
		<div class=\"clear\"></div>
	</div>";
	
	return $output;
}

function template_post($post)
{
	$first = ($post['ParentId'] == 0) ? " forum-post-first" : "";
	
	$user = utf8entities(stripslashes($post['Name']));
	$body = nl2br(utf8entities(stripslashes($post['Body'])));
	$date = date("F j, Y <b\\r>H:i:s", strtotime($post['Posted']));
	
	$output = "
	<a name=\"p-{$post['Id']}\"></a>
	<div class=\"forum-post{$first}\">
		<div class=\"forum-post-meta\">
			<div class=\"forum-post-user\">
				{$user}
			</div>
			<div class=\"forum-post-date\">
				{$date}
			</div>
		</div>
		<div class=\"forum-post-body\">
			{$body}
		</div>
		<div class=\"clear\"></div>
	</div>
	";
	
	return $output;
}

function template_captcha()
{
	global $recaptcha_pubkey;
	return "
	<div id=\"divrecaptcha\" style=\"display:none;\">  

		<div id=\"recaptcha_image\"></div>
		<div id=\"recaptcha_arrow\">&gt;</div>
		<input type=\"text\" name=\"recaptcha_response_field\" id=\"recaptcha_response_field\">
		<div class=\"clear\"></div>
		
		<div id=\"recaptcha_links\">
			<a href=\"#\" onclick=\"Recaptcha.reload(); return false;\">Get another Captcha</a>
			<a href=\"#\" onclick=\"Recaptcha.switch_type('audio'); return false;\" class=\"recaptcha_only_if_image\">Get Audio Captcha</a> 
			<a href=\"#\" onclick=\"Recaptcha.switch_type('image'); return false;\" class=\"recaptcha_only_if_audio\">Get Text Captcha</a>
			<a href=\"#\" onclick=\"Recaptcha.showhelp(); return false;\">Help</a>
			<a href=\"http://www.google.com/recaptcha\" target=\"_blank\">Captcha by reCAPTCHA</a>.
		</div>  

		
		<!-- <p>  
			<span class=\"recaptcha_only_if_image\">Enter the words shown above separated by space</span>  
			<span class=\"recaptcha_only_if_audio\">Enter the numbers you hear</span>
		</p> --> 

	</div> 
	" . recaptcha_get_html($recaptcha_pubkey);
}

?>
