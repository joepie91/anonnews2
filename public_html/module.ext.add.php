<?php
if(!isset($_ANONNEWS)) { die(); }  /* Protect from direct requests */
/* This module allows a user to submit a new external news item. */

if(check_banlist() == false)
{
	if(empty($var_id))
	{
		// Stage 0: The page with guidelines.
		?>
		<h2>Read these guidelines. Not reading them may get you banned.</h2>
		<p><strong>While no censorship based on opinion, views, etc. takes place on AnonNews, there are several guidelines in place to keep content on the site relevant.</strong>
		Read these guidelines completely before submitting an external news item. Not reading them may get you banned.</p>
		<p><strong>Anonymous is not your personal army.</strong> Don't post anything asking anons to undertake some sort of action.</p>
		<p><strong>This is a news section.</strong> Only post actual news articles. No blog posts, no signup pages, no forums, no press releases, no dox, no satire, and so on. If it's on pastebin, it doesn't belong here. If it's on YouTube, it doesn't belong here-
		unless it's an actual recording of a news channel. If you have anything that may be of interest to anons like satire or blog posts, post them on <a href="/forum">the forum.</a></p>
		<p><strong>AnonNews is about Anonymous.</strong> While you may think your local political party, a phone tapping scandal, or anything else is important, this is not the place for it. Any submission has to be specifically about
		Anonymous. Other topics that may be of interest to other anons can be discussed on the <a href="/forum">forum</a>.</p>
		<p><strong>Give your submissions correct titles.</strong> After entering a URL, a title will be suggested. Usually the suggested title is correct, but make that you check it. <strong>Do not enter a personal comment as title.</strong>
		This is not Reddit. The article title should be the actual title of the article (optionally with the site name attached).</p>
		<p><strong>On the IP retention policy: we normally do not store IP addresses of anyone submitting content to AnonNews (feel free to use TOR or a proxy to be completely sure). If you hit a spam filter,
		however (there is almost zero chance for a false positive), your IP may be recorded and banned. If an IP is incorrectly recorded (a false positive) it will be reviewed and removed from the log within 24 hours, without exception.</strong></p>
		<p><form method="GET" action="/external-news/add/form/">
			If you have read the guidelines, <button name="submit" type="submit">click here to submit your external news item.</button>
		</form></p>
		<?php
	}
	else
	{
		if($var_id == "form")
		{
			// Stage 1: Entering the URL.
			?>
			<h2>Submit an external news article</h2>
			<form method="POST" action="/external-news/add/check/" class="submission">

				<h3>First of all, enter a URL. After the URL has been checked and found to be valid, you will be able to enter the rest.</h3>
				
				<h4>Article URL</h4>
				<input type="text" name="url" id="input_url" class="empty" value="http://">

				<script type="text/javascript">
					$(function(){
						$('#input_url').focus(function(){
							if($('#input_url').val() == "http://")
							{
								$('#input_url').val("");
							}
							$('#input_url').removeClass("empty");
						});
						
						$('#input_url').blur(function(){
							if($('#input_url').val() == "")
							{
								$('#input_url').val("http://");
								$('#input_url').addClass("empty");
							}
						});
					});
				</script>

				<div class="submit">
					<button type="submit" name="submit" onclick="$('#submit_loader').css({'display':'block'}); $(this).css({'display':'none'}); return true;">Submit URL &gt;&gt;</button>
				</div>
				<div class="submit-loader" id="submit_loader">
					<img src="http://tahoe-gateway.cryto.net:3719/download/VVJJOkNISzpuMzRqdGlhb3gycGxxbnZjZm5hM3k1NzdyYTpsbDRvNzc1Z2FsYjVmdzVqd3Q2ems0aGQ0bGd2ZXMzZHl1YXRkZHVwa2p0YXFnbmdtMmlxOjM6NjoxMDgxOQ==/loader-wide.gif"><br>
					<strong>Scanning URL... (this may take a while!)</strong>
				</div>
			</form>
			<?php
		}
		elseif($var_id == "check")
		{
			echo("<!-- Live debugging: check -->");
			// Stage 2: Verifying that the URL is indeed valid. If it is valid, suggest a title and allow the user to enter more details
			if(spam_score($_POST['url'], "", false) < 10)
			{
				$request = curl_head($_POST['url']);
				
				if($request->code == 999)
				{
					$var_code = ANONNEWS_ERROR_MALFORMED_DATA;
					require("module.error.php");
				}
				elseif($request->code == 300 || $request->code == 301 || $request->code == 302)
				{
					$var_code = ANONNEWS_ERROR_SHORTENER_DETECTED;
					require("module.error.php");
				}
				elseif($request->code == 200)
				{
					$request = curl_get($_POST['url']);
					if(!preg_match("/<title>?(.*?)<\/title>/i", $request->result, $matches))
					{
						$title = "";
						$title_desc = "No article title could be suggested. Please enter one yourself.";
					}
					else
					{
						$title = $matches[1];
						
						$title_desc = "The below suggestion was made based on the full page title (<em>$title</em>). Make sure it's correct before submitting.";
						
						$title_suggestion = utf8_entities_if_needed(suggest_title($title));
						
						$raw_suggestion = html_entity_decode($title_suggestion, ENT_QUOTES, "UTF-8");
						
						// Load noise dictionary, for tag generation
						$noise = split_lines(file_get_contents_cached("english.dic")->data);
						$noise = arraytolower($noise);
						
						foreach(explode(" ", $raw_suggestion) as $tag)
						{
							$tag = trim(clean_tag($tag));
							if(strlen(trim($tag)) > 1 && in_array(strtolower(trim($tag)), $noise) === false)
							{
								$tag_list[] = strtolower($tag);
							} 
						}
						
						$tag_list = array_unique($tag_list);
						
						$tags_suggestion = utf8_entities_if_needed(implode(", ", $tag_list));
					}
					
					if($detect_language)
					{
						require_once("Text/LanguageDetect.php");
						$detector = new Text_LanguageDetect;
						$detected_language = $detector->detectSimple(strip_tags($request->result));
					}
					else
					{
						$detected_language = "English";
					}
					
					?>
					<h2>Submit an external news article</h2>
					
					<form method="POST" action="/external-news/add/submit/" class="submission">
						
						<input type="hidden" name="url" value="<?php echo(utf8entities($_POST['url'])); ?>">
						
						<h4>Article Title</h4>
						<div class="form-notice">
							<?php echo($title_desc); ?>
						</div>
						<input type="text" name="title" value="<?php echo($title_suggestion); ?>">
					
						<h4>Tags (optional)</h4>
						<div class="form-notice">
							Enter comma-separated tags here, that indicate what the press release is about. This will make it easier to find on the site.
						</div>
						<input type="text" name="tags" id="input_tags" value="<?php echo($tags_suggestion); ?>">
						
						<h4>Article Language</h4>
						<select name="language">
							<?php
							foreach($languages as $iso => $lang)
							{
								$sel = (strtolower($lang) == strtolower($detected_language)) ? " selected" : "";
								echo("<option value=\"{$iso}\"{$sel}>{$lang}</option>");
							}
							?>
						</select>
						<div class="note">
							Detected language: <?php echo($detected_language); ?>
						</div>
						
						<h4>Complete the CAPTCHA</h4>
						<?php echo(template_captcha()); ?>
						
						<div class="submit">
							<button type="submit" name="submit" onclick="$('#submit_loader').css({'display':'block'}); $(this).css({'display':'none'}); return true;">Submit external news item &gt;&gt;</button>
						</div>
						<div class="submit-loader" id="submit_loader">
							<img src="http://tahoe-gateway.cryto.net:3719/download/VVJJOkNISzpuMzRqdGlhb3gycGxxbnZjZm5hM3k1NzdyYTpsbDRvNzc1Z2FsYjVmdzVqd3Q2ems0aGQ0bGd2ZXMzZHl1YXRkZHVwa2p0YXFnbmdtMmlxOjM6NjoxMDgxOQ==/loader-wide.gif"><br>
							<strong>Submitting external news item... (this may take a while!)</strong>
						</div>
						
					</form>
					<?php
				}
				else
				{
					echo("<!--  {$request->code}  -->");
					$var_code = ANONNEWS_ERROR_NONEXISTENT_URL;
					require("module.error.php");
				}
			}
			else
			{
				$var_code = ANONNEWS_ERROR_URL_BLACKLISTED;
				require("module.error.php");
			}
		}
		elseif($var_id == "submit")
		{
			// Stage 3: Processing the submission.
			$recaptcha = recaptcha_check_answer ($privatekey,
				$_SERVER["REMOTE_ADDR"],
				$_POST["recaptcha_challenge_field"],
				$_POST["recaptcha_response_field"]);
				
			if($recaptcha->is_valid)
			{
				if(!empty($_POST['title']))
				{
					if(!empty($_POST['url']))
					{
						// It will have to be approved before it appears on the front page.
						$spam_score = spam_score($_POST['url'], $_POST['title'], true);
						
						if($spam_score < 10)
						{
							$request = curl_head($_POST['url']);
							if($request->code == 200)
							{
								if($spam_score < 5)
								{
									$visible = true;
									$approval_status = "Your submission is now visible on the frontpage.";
								}
								else
								{
									$visible = false;
									$approval_status = "Your submission was however flagged as potential spam, and will be manually reviewed before appearing on the frontpage.";
								}
								
								$language = mysql_real_escape_string($_POST['language']);								
								$title = mysql_real_escape_string($_POST['title']);
								$url = mysql_real_escape_string($_POST['url']);
								
								$query = "INSERT INTO ext (`Name`, `Url`, `CommentCount`, `Deleted`, `Approved`, `Visible`, `Rank`, `Mod`, `Language`, `Posted`)
								VALUES ('$title', '$url', '0', '0', '0', '{$visible}', '0', '', '{$language}', CURRENT_TIMESTAMP)";
								
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
												$query = "INSERT INTO tags (`Table`, `ItemId`, `TagName`) VALUES ('ext', '{$insert_id}', '$tag')";
												mysql_query($query);
											}
										}
									}
								
									echo("<p><strong>Your external news item was successfully submitted.</strong> {$approval_status}</p>
									<p><a href=\"/\" class=\"page-button\">&lt;&lt; back to front page</a></p>");
								}
								else
								{
									echo(mysql_error());
									$var_code = ANONNEWS_ERROR_DATABASE_ERROR;  // Generic upload error
									require("module.error.php");
								}
							}
							elseif($request->code == 300 || $request->code == 301 || $request->code == 302)
							{
								$var_code = ANONNEWS_ERROR_SHORTENER_DETECTED;
								require("module.error.php");
							}
							else
							{
								echo("<!--  {$request->code} 7 -->");
								$var_code = ANONNEWS_ERROR_NONEXISTENT_URL;
								require("module.error.php");
							}
						}
						else
						{
							$var_code = ANONNEWS_ERROR_SPAM;
							require("module.error.php");
						}
					} 
					else
					{
						$var_code = ANONNEWS_ERROR_EMPTY_URL;  // Empty body
						require("module.error.php");
					}
				}
				else
				{
					$var_code = ANONNEWS_ERROR_EMPTY_TITLE;  // Empty title
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
	}
}
else
{
	$var_code = ANONNEWS_ERROR_BANNED;  // Banned from submission.
	require("module.error.php");
}
?>
