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
		Read these guidelines completely before submitting a related site. Not reading them may get you banned.</p>
		<p><strong>This section is solely for Anonymous-related websites.</strong> The site must represent a group or 'part' of Anonymous. Examples are IRC networks, Anonymous event planners, specific
		Anonymous-related news sites or blogs, and so on.</p>
		<p><strong>Related sites have to be notable.</strong> This essentially means that your blog with 20 visitors a day and 3 total posts is not going to be accepted. An (almost) empty website is not going
		to be accepted either. For networks/groups/'cells', there must be an established userbase already. Websites run by one person will only be accepted if they offer considerable value (your blog
		with weekly opinion posts will probably not get accepted, whereas a blog with frequent news about various Anonymous groups will be accepted).</p>
		<p><strong>This is not Craigslist.</strong> This is not a place to advertise your new site - this section is intended to help people find useful Anonymous-related resources that already exist.
		If you are looking to start something new, and you're looking for people to join, the <a href="/forum">forums</a> would be a better place.</p>
		<p><strong>Only very few entries will be accepted.</strong> The intention is to keep this section as small as possible, offering a brief overview of related resources for people that want to 
		learn more about Anonymous or get actively involved with it. Only the most notable and useful submissions will be accepted.</p>
		<p><strong>Keep the submission title to the point.</strong> The title must be the name of the site, or, if it doesn't have a name, a brief description of what the site is. No slogans, no URLs,
		no explanations - keep that for the site itself.</p>
		<p><form method="GET" action="/related-sites/add/form/">
			If you have read the guidelines, <button name="submit" type="submit">click here to submit your related site.</button>
		</form></p>
		<?php
	}
	else
	{
		if($var_id == "form")
		{
			// Stage 1: Entering the URL.
			?>
			<h2>Submit a related site</h2>
			<form method="POST" action="/related-sites/add/check/" class="submission">

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
						$title_desc = "No website title could be suggested. Please enter one yourself.";
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
					<h2>Submit a related site</h2>
					
					<form method="POST" action="/related-sites/add/submit/" class="submission">
						
						<input type="hidden" name="url" value="<?php echo(utf8entities($_POST['url'])); ?>">
						
						<h4>Website Title</h4>
						<div class="form-notice">
							<?php echo($title_desc); ?>
						</div>
						<input type="text" name="title" value="<?php echo($title_suggestion); ?>">
					
						<h4>Tags (optional)</h4>
						<div class="form-notice">
							Enter comma-separated tags here, that indicate what the website is about. This will make it easier to find on the site.
						</div>
						<input type="text" name="tags" id="input_tags" value="<?php echo($tags_suggestion); ?>">
						
						<h4>Website Language</h4>
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
							<button type="submit" name="submit" onclick="$('#submit_loader').css({'display':'block'}); $(this).css({'display':'none'}); return true;">Submit related site &gt;&gt;</button>
						</div>
						<div class="submit-loader" id="submit_loader">
							<img src="http://tahoe-gateway.cryto.net:3719/download/VVJJOkNISzpuMzRqdGlhb3gycGxxbnZjZm5hM3k1NzdyYTpsbDRvNzc1Z2FsYjVmdzVqd3Q2ems0aGQ0bGd2ZXMzZHl1YXRkZHVwa2p0YXFnbmdtMmlxOjM6NjoxMDgxOQ==/loader-wide.gif"><br>
							<strong>Submitting related site... (this may take a while!)</strong>
						</div>
						
					</form>
					<?php
				}
				else
				{
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
						$spam_score = spam_score($_POST['url'], $_POST['title'], false);
						
						if($spam_score < 10)
						{
							$request = curl_head($_POST['url']);
							if($request->code == 200)
							{								
								$language = mysql_real_escape_string($_POST['language']);								
								$title = mysql_real_escape_string($_POST['title']);
								$url = mysql_real_escape_string($_POST['url']);
								
								$query = "INSERT INTO sites (`Name`, `Url`, `CommentCount`, `Deleted`, `Approved`, `Mod`, `Language`, `Posted`)
								VALUES ('$title', '$url', '0', '0', '0', '', '{$language}', CURRENT_TIMESTAMP)";
								
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
												$query = "INSERT INTO tags (`Table`, `ItemId`, `TagName`) VALUES ('sites', '{$insert_id}', '$tag')";
												mysql_query($query);
											}
										}
									}
								
									echo("<p><strong>Your related site was successfully submitted.</strong> It will have to be approved before it appears on the front page.</p>
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
