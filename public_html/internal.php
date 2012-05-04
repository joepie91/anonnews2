<?php
/* This file processes the actual pageloads, based on variables that are set by either rewrite.php or index.php.
 * It should only be allowed to run if it is included by either. */

$timing_start = microtime(true);

if(!isset($_INCLUDED))
{
	die("Direct access to this page is not permitted.");
}

if(!isset($var_table))
{
	$var_table = "";
}

if(!isset($var_id))
{
	$var_id = "";
}

$_ANONNEWS = true;
require("include/base.php");
?>

<!DOCTYPE html>
<html>
	<head>
		<title>AnonNews.org : <?php echo($lang[0]); ?></title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<link rel="stylesheet" type="text/css" href="/style.css">
		<!--[if lt IE 8]><style>.bb {behavior:url("boxsizing.htc");}</style><![endif]-->
		<script type="text/javascript" src="/jquery.js"></script>
		<script type="text/javascript">
			var var_section = "<?php echo($var_section); ?>";
			var var_id = "<?php echo($var_id); ?>";
		</script>
		<script src="/script.2.0.js"></script>
		<script type="text/javascript" >  
			var RecaptchaOptions = {  
				theme : 'custom',  
				lang: 'en',  
				custom_theme_widget: 'divrecaptcha'  
			};  
		</script> 
		<script type="text/javascript">
		/* <![CDATA[ */
			(function() {
				var s = document.createElement('script'), t = document.getElementsByTagName('script')[0];
				
				s.type = 'text/javascript';
				s.async = true;
				s.src = 'http://api.flattr.com/js/0.6/load.js?mode=auto';
				
				t.parentNode.insertBefore(s, t);
			})();
		/* ]]> */
		</script>
	</head>
	<body>
		<!-- <Obvioustroll>joepie is a huuuge faggot -->
		
		<div id="header_top">
			<span style="float: right;" id="ad_inline">
				<div class="header-button" style="font-size: 13px;">
					<!-- Site language: <?php echo($flaglist); ?><br>
					<div style="height: 6px; font-size: 1px; line-height: 1px;"></div>
					Only show submissions in <select>
						<option value="en">English</option>
						<option value="fr">French</option>
						<option value="de">German</option>
						<option value="nl">Dutch</option>
						<option value="es">Spanish</option>
						<option value="it">Italian</option>
						<option value="ar">Arabic</option>
						<option value="tr">Turkish</option>
						<option value="ba">Bosnian</option>
						<option value="rs">Serbian</option>
						<option value="pl">Polish</option>
					</select>
					<input type="checkbox" id="c_english" style="margin-left: 10px;"><label for="c_english">Also English</label><br>
					<div style="height: 6px; font-size: 1px; line-height: 1px;"></div>
					Tags: anonops metalgear libya italy backtrace &nbsp;&nbsp;more &gt;&gt; -->
					<strong>AnonNews 2.0 is finally there!</strong> Press release submission <br>
					is open again, as are comments - and we now have forums!<br>
					Other interface languages and language/tag filtering coming soon, as well <br>
					as RSS feeds.
				</div>
			</span>
			<h1><a href="/"><span class="strong">AnonNews</span> - <?php echo($lang[0]); ?></a></h1>
			<div style="z-index: 999;">
				<a class="header-button" href="/static/faq">FAQ</a>
				<a class="header-button" href="/static/irc">IRC</a>
				<a class="header-button" href="/forum" target="_blank">Forum</a>
				<a class="header-button" href="/static/donate" style="padding: 4px; padding-bottom: 1px;"><img src="http://tahoe-gateway.cryto.net:3719/download/VVJJOkNISzpuZmpiZmJhcXV0dDQyanRlcXJld2xhYzZkZTp6a3FqaTNhd291Z2JvbHJkYTM3bnFlcmc1bmQ0ZG0yYXEyc2J3eWR5cTZkYTR2ZWFqZ2xhOjM6NjoyNjIy/bitcoin.png" style="border: 0px solid black;"></a>
				<div class="header-button" style="padding: 7px; padding-top: 9px;">
					<a class="FlattrButton" style="display:none;" rev="flattr;button:compact;" href="http://www.anonnews.org/"></a>
				</div>
				<div class="header-button" style="padding: 6px;">
					<form action="https://www.paypal.com/cgi-bin/webscr" method="post" style="display: inline;">
					<input type="hidden" name="cmd" value="_s-xclick">
					<input type="hidden" name="hosted_button_id" value="T425GCPN4A7GE">
					<input type="image" src="https://www.paypal.com/en_GB/i/btn/btn_donate_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online."><img alt="" border="0" src="https://www.paypal.com/nl_NL/i/scr/pixel.gif" width="1" height="1">
					</form>
				</div>
				<div class="clear"></div>
			</div>
		</div>
		
		<div class="topbar">
			<strong>AnonNews is an independent and <a href="/static/moderation">uncensored (but moderated)</a> news platform for Anonymous.</strong> Anyone is welcome to post a submission, and can do so by clicking the "Add" button for a category.<br>
			<strong>If you need help or have questions regarding AnonNews,</strong> please <a href="/static/irc">join our IRC channel.</a>
		</div>
		<?php
		
		//$ismod=true;
		if($ismod && (!isset($_GET['p']) || $_GET['p']!="mod"))
		{
			$res = mysql_query("SELECT COUNT(*) FROM ext WHERE `Approved`='0' AND `Deleted`='0'");
			$row = mysql_fetch_array($res);
			$total = $row['COUNT(*)'];
			$res = mysql_query("SELECT COUNT(*) FROM press WHERE `Approved`='0' AND `Deleted`='0'");
			$row = mysql_fetch_array($res);
			$total += $row['COUNT(*)'];
			$res = mysql_query("SELECT COUNT(*) FROM sites WHERE `Approved`='0' AND `Deleted`='0'");
			$row = mysql_fetch_array($res);
			$total += $row['COUNT(*)'];
			if($total>0)
			{
				echo("<div class=\"item\" style=\"background-color: #FFAA99; padding: 4px;\"><a href=\"?p=mod\"><strong>There are $total unmoderated item(s)!</strong> Click here to start moderating.</a></div>");
			}
		}
		?>
		<div class="body-main">
			<?php

			///// START REQUEST HANDLING /////

			if($var_section == "static")
			{
				// Handle static pages.
				if(isset($static_pages[$var_table]))
				{
					require("static/{$static_pages[$var_table]}");
				}
				else
				{
					$var_code = ANONNEWS_ERROR_NOT_FOUND;
					require("module.error.php");
				}
			}
			elseif($var_section == "radio")
			{
				require("static/radio.static.php");
			}
			elseif($var_section == "error")
			{
				require("module.error.php");
			}
			elseif($var_section == "press")
			{
				require("module.press.php");
			}
			elseif($var_section == "external-news")
			{
				require("module.ext.php");
			}
			elseif($var_section == "related-sites")
			{
				require("module.sites.php");
			}
			elseif($var_section == "forum")
			{
				require("module.forum.php");
			}
			elseif($var_section == "home")
			{
				require("module.home.php");
			}
			elseif($var_section == "moderation")
			{
				require("module.moderation.php");
			}
			
			////// END REQUEST HANDLING //////
			?>
		</div>
		<div style="height: 30px; border-bottom: 1px solid black; margin-bottom: 10px;"></div>
		<div style="text-align: center;">
			<span style="overflow: hidden; border: 1px solid black; -webkit-border-radius: 10px; -moz-border-radius: 10px; border-radius: 10px; padding: 4px; padding-left: 0px; font-family: Arial; font-size: 12px; font-weight: bold; background-color: white;">
				<span style="padding: 4px; padding-left: 8px; background-color: #E98A0A; -webkit-border-radius: 10px; -moz-border-radius: 10px; border-radius: 10px; color: white;">
					<a href="http://www.bitcoin.org/" style="text-decoration: none; color: white; border: none;">
						<span style="-webkit-border-radius: 10px; -moz-border-radius: 10px; border-radius: 10px; font-weight: normal; color: white; font-size: 15px; background-color: white; color: #E98A0A; padding: 1px 5px; padding-top: 0px;">&#3647;</span>
						Donate using Bitcoin!
					</a>
				</span>
				<span style="padding: 5px;">
					1PPVupRRz7tHvfvJWEDBnDr7bFVFFYLu6G
				</span>
			</span>
		</div>
		<div style="height: 15px;"></div>
		<div class="cc-notice">
			<a rel="license" href="http://creativecommons.org/licenses/by/3.0/"><img alt="Creative Commons Attribution" style="border-width:0" src="http://i.creativecommons.org/l/by/3.0/88x31.png" /></a>
			All content on this website is automatically licensed under a Creative Commons Attribution license. You are free to redistribute and/or remix it, but you have to credit the author, or, if the author is unknown ("Anonymous"), place a backlink to the corresponding page on AnonNews and attribute it to "Anonymous".
			<div class="clear"></div>
		</div>
		<div style="height: 15px;"></div>
		<div class="source-notice">
			<a href="http://www.cryto.net/projects/anonnews2">Download the AnonNews 2.0 source code</a> / <a href="/moderation">Moderation panel</a>
		</div>
		<div style="height: 40px;"></div>
	</body>
</html>

<?php
	echo("<!-- page generated in " . (round(microtime(true) - $timing_start, 6)) . " seconds. -->");
?>
