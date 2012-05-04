<?php
if(!isset($_ANONNEWS)) { die(); }  /* Protect from direct requests */
/* This module allows a user to submit a new press release. */

if(check_banlist() == false)
{
	if(empty($var_id))
	{
		// Stage 0: The page with guidelines.
		?>
		<h2>Read these guidelines. Not reading them may get you banned.</h2>
		<p><strong>While no censorship based on opinion, views, etc. takes place on AnonNews, there are several guidelines in place to keep content on the site relevant.</strong>
		Read these guidelines completely before submitting a press release. Not reading them may get you banned.</p>
		<p><strong>This is not a forum.</strong> Opinion posts, questions to anons, and other similar things do not belong here. <a href="/forum">Use the forum.</a></p>
		<p><strong>AnonNews is about Anonymous.</strong> While you may think your local political party, a phone tapping scandal, or anything else is important, this is not the place for personal army requests.
		If you wish to discuss a topic that may be of interest to other anons, you can do so on the <a href="/forum">forum</a>. If it's not a press release or manifesto from Anonymous, it doesn't belong here - period.</p>
		<p><strong>Format your press releases properly.</strong> Press releases and manifestos are expected to be readable and in proper formatting. While we certainly don't expect perfect grammar, a press
		release that uses an abbreviation every other word or contains excessive 'leetspeak' is not going to be accepted. If your press release is in bright pink with images of red flowers on the side, it will
		probably not be accepted either.</p>
		<p><strong>No copypasting.</strong> This section is intended for those that wish to submit a press release about an operation they are involved with (not necessarily being part of staff). Don't copypaste
		news articles or press releases from others that you have nothing to do with. Submitting it for someone else who is involved with an operation, is of course not an issue at all.</p>
		<p><strong>You are not the leader of Anonymous. Noone is.</strong> Don't try to imply that <em>all</em> of Anonymous agrees with something or condemns it - your press release will be rejected. Unless you
		have talked through your press release with literally every single anon out there, you cannot speak for all of them. Making a generic 'from Anonymous' statement is fine, as long as you don't try to say that
		'person X and operation Y were not Anonymous' or try to impose alleged 'universal values or ideologies' onto Anonymous - they simply do not exist.</p>
		<p><strong>On the IP retention policy: we normally do not store IP addresses of anyone submitting content to AnonNews (feel free to use TOR or a proxy to be completely sure). If you hit a spam filter,
		however (there is almost zero chance for a false positive), your IP may be recorded and banned. If an IP is incorrectly recorded (a false positive) it will be reviewed and removed from the log within 24 hours, without exception.</strong></p>
		<p><form method="GET" action="/press/add/form/">
			If you have read the guidelines, <button name="submit" type="submit">click here to submit your press release.</button>
		</form></p>
		<?php
	}
	else
	{
		if($var_id == "form")
		{
			// Stage 1: Entering the actual press release information.
			?>
			<h2>Submit a press release</h2>
				<form enctype="multipart/form-data" method="POST" action="/press/add/submit/" class="submission">
				<input type="hidden" name="js_enabled" value="false" id="input_js_enabled">
				<script type="text/javascript">
					$(function(){
						$('#input_js_enabled').val("true");
						$('#notice_javascript').css({'display':'none'});
					});
				</script>
				<h4>Press release title</h4>
				<input type="text" name="title">
				<h4>Press release text</h4>
				<div class="form-notice" id="notice_javascript">
					To make use of the WYSIWYG editor, Javascript is required. If Javascript is turned off, you can make use of HTML instead - line breaks will automatically be inserted.
				</div>
				<textarea name="body"></textarea>
				
				<script type="text/javascript" src="/tiny_mce/tiny_mce.js"></script>
				<script type="text/javascript">
				tinyMCE.init({
					// General options
					mode : "textareas",
					theme : "advanced",
					plugins : "safari,spellchecker,pagebreak,style,layer,table,save,advhr,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template",
					// Theme options
					theme_advanced_buttons1 : "spellchecker,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,|,fontselect,fontsizeselect",
					theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,undo,redo,|,link,unlink,|,insertdate,inserttime,preview,|,forecolor,backcolor",
					theme_advanced_toolbar_location : "top",
					theme_advanced_toolbar_align : "left",
					theme_advanced_statusbar_location : "bottom",
					theme_advanced_resizing : true,

					// Office example CSS
					content_css : "css/office.css",
				});
				</script>
				
				<h4>Upload an image (optional)</h4>
				<div class="form-notice">
					Allowed: PNG, GIF, JPG. Maximum filesize: 20MB. <strong>If possible, please use PNG instead of JPG for better image quality.</strong>
				</div>
				<input type="hidden" name="MAX_FILE_SIZE" value="20000000">
				<input name="file" type="file" class="upload">
				
				<h4>Tags (optional)</h4>
				<div class="form-notice">
					Enter comma-separated tags here, that indicate what the article is about. This will make it easier to find on the site.
				</div>
				<input type="text" name="tags" id="input_tags" value="">
				
				<h4>Press Release Language</h4>
				<select name="language">
					<?php
					foreach($languages as $iso => $lang)
					{
						echo("<option value=\"{$iso}\">{$lang}</option>");
					}
					?>
				</select>
				
				<h4>Complete the CAPTCHA</h4>
				<?php echo(template_captcha()); ?>
				
				<div class="submit">
					<button type="submit" name="submit" onclick="$('#submit_loader').css({'display':'block'}); $(this).css({'display':'none'}); return true;">Submit press release &gt;&gt;</button>
				</div>
				<div class="submit-loader" id="submit_loader">
					<img src="http://tahoe-gateway.cryto.net:3719/download/VVJJOkNISzpuMzRqdGlhb3gycGxxbnZjZm5hM3k1NzdyYTpsbDRvNzc1Z2FsYjVmdzVqd3Q2ems0aGQ0bGd2ZXMzZHl1YXRkZHVwa2p0YXFnbmdtMmlxOjM6NjoxMDgxOQ==/loader-wide.gif"><br>
					<strong>Submitting press release... (this may take a while!)</strong>
				</div>
			</form>
			<?php
		}
		elseif($var_id == "submit")
		{
			// Stage 2: Processing the upload and press release.
			$recaptcha = recaptcha_check_answer ($privatekey,
				$_SERVER["REMOTE_ADDR"],
				$_POST["recaptcha_challenge_field"],
				$_POST["recaptcha_response_field"]);
				
			if($recaptcha->is_valid)
			{
				$error = false;
				if(isset($_FILES['file']) && $_FILES['file']['error'] == 0)
				{
					$file_uploaded = true;
					if(ends_with($_FILES['file']['name'], ".jpg") || ends_with($_FILES['file']['name'], ".jpeg") || ends_with($_FILES['file']['name'], ".png") || ends_with($_FILES['file']['name'], ".gif"))
					{
						if($_FILES['file']['size'] <= 20000000)
						{
							$upload_result = curl_put("{$tahoe_server}/uri", $_FILES['file']['tmp_name']);
							if($upload_result !== false)
							{
								$upload_b64 = urlsafe_b64encode($upload_result);
								$upload_url = "/download/$upload_b64/{$_FILES['file']['name']}";
							}
						}
						else
						{
							$error = true;
							$var_code = ANONNEWS_ERROR_TOO_LARGE;  // Upload filesize error
							require("module.error.php");
						}
					}
					else
					{
						$error = true;
						$var_code = ANONNEWS_ERROR_INCORRECT_FORMAT;  // Upload file format error
						require("module.error.php");
					}
				}
				elseif(isset($_FILES['file']) && $_FILES['file']['error'] == 4)
				{
					// No file was uploaded.
					$file_uploaded = false;
				}
				else
				{
					$error = true;
					$var_code = ANONNEWS_ERROR_UPLOAD_ERR;  // Generic upload error
					require("module.error.php");
				}
				
				if($error === false)
				{
					// Either no file was uploaded or the file was successfully uploaded, continue...
					if(!empty($_POST['title']))
					{
						if(!empty($_POST['body']))
						{
							if($file_uploaded === false)
							{
								$upload_url = "";
							}
							
							$body = $_POST['body'];
							
							if($_POST['js_enabled'] === "false")
							{
								$body = nl2br($body, false);
							}
							
							$body = mysql_real_escape_string(str_replace("javascript:", "",strip_tags_attributes($body,
							"<a><b><i><u><span><div><p><br><hr><font><ul><li><ol><dt><dd><h1><h2><h3><h4><h5><h6><h7><del><map><area><strong><em><big><small><sub><sup><ins><pre><blockquote><cite><q><center><marquee><table><tr><td><th>",
							"href,src,alt,class,style,align,valign,color,face,size,width,height,shape,coords,target,border,cellpadding,cellspacing,colspan,rowspan")));
							$title = mysql_real_escape_string($_POST['title']);
							
							$language = mysql_real_escape_string($_POST['language']);
							
							$query = "INSERT INTO press (`Name`, `Body`, `CommentCount`, `Deleted`, `Approved`, `Attachment`, `Upvotes`, `Mod`, `ExternalAttachment`, `Language`, `Posted`)
							VALUES ('{$title}', '{$body}', '0', '0', '0', '{$upload_url}', '0', '', '1', '{$language}', CURRENT_TIMESTAMP)";
							
							if(mysql_query($query))
							{
								$insert_id = mysql_insert_id();
								
								if(!empty($_POST['tags']))
								{
									// tags were entered.
									$tags = $_POST['tags'];
									$tags_list = explode(",", $tags);
									foreach($tags_list as $tag)
									{
										$tag = mysql_real_escape_string(trim(clean_tag($tag)));
										if(!empty($tag))
										{
											$query = "INSERT INTO tags (`Table`, `ItemId`, `TagName`) VALUES ('press', '{$insert_id}', '$tag')";
											mysql_query($query);
										}
									}
								}
								
								echo("<p><strong>Your press release was successfully submitted.</strong> It will have to be approved before it appears on the front page.</p>
								<p><a href=\"/\" class=\"page-button\">&lt;&lt; back to front page</a></p>");
							}
							else
							{
								echo(mysql_error());
								$var_code = ANONNEWS_ERROR_DATABASE_ERROR;  // Generic upload error
								require("module.error.php");
							}
						} 
						else
						{
							$var_code = ANONNEWS_ERROR_EMPTY_BODY;  // Empty body
							require("module.error.php");
						}
					}
					else
					{
						$var_code = ANONNEWS_ERROR_EMPTY_TITLE;  // Empty title
						require("module.error.php");
					}				
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
	}
}
else
{
	$var_code = ANONNEWS_ERROR_BANNED;  // Banned from submission.
	require("module.error.php");
}
?>
