<?php

if(!isset($_ANONNEWS))
{
	die("You are not supposed to be here.");
}

session_set_cookie_params(0);
session_start();

require("config.php");

if(!@mysql_connect($mysql_host,$mysql_user,$mysql_pass))
{
	die("An error occurred: Could not connect to database server. Please notify the site administrator.");
}
if(!@mysql_select_db($mysql_db))
{
	die("An error occurred: Could not select database. Please notify the site administrator.");
}


require("include.constants.php");

require("comment.class");
require("functions.php");

require("include.template.php");
require("include.ip.php");
require("include.blacklist.php");
require("include.utf8.php");
require("include.string.php");
require("include.filter.php");

require("include.tahoe.php");
require("include.curl.php");
require("include.memcache.php");
require("include.render.php");

$referer = (isset($_SERVER['HTTP_REFERER'])) ? $_SERVER['HTTP_REFERER'] : "/";

$tahoe_gateway = $tahoe_gateway_clearnet;

if(!isset($_SESSION['vote_token']))
{
	$_SESSION['vote_token'] = random_string(20);
}

if(isset($_GET['setlang']))
{
	$_SESSION['curlang'] = $_GET['setlang'];
}

if(!isset($_SESSION['curlang']))
{
	$curlang = "en";
}
else
{
	$curlang = $_SESSION['curlang'];
}

include("language/en.lang");
@include("language/".$langfiles[$curlang]);

require_once('recaptchalib.php');
$publickey = "6Lfscb8SAAAAADYzomjqiMyOdCo-Tmt0kmN66KOM";
$privatekey = "6Lfscb8SAAAAAM_59mhDJnu13xlNWNW5tt2tMvK0";

if(!isset($_SESSION['auth']))
{
	$ismod = false;
}
else
{
	// check credentials
	$user = $_SESSION['user'];
	$pass = $_SESSION['hash'];
	$res = mysql_query("SELECT * FROM mods WHERE `Username`='$user' AND `Hash`='$pass'");
	if(mysql_num_rows($res)==0)
	{
		//print_r($_SESSION);
		session_destroy();
		die("Either your login details changed, your session died, or you tried breaking into the system. Reload the page and, unless it's the latter, try again.");
	}
	else
	{
		$ismod = true;
	}
}


// define where the flags are stored
$flagurl['en'] = "{$tahoe_gateway}/download/VVJJOkNISzpjYXlqdGp6dDI2Ym1zZnNyZnptd2JubGtxeTo3dWgyaGVoZjdza25rZ3k2dXZkb2Y1cjZibDZ4aHU2NHV6cG5iNXBzM3d3ZWd6YXg2N29xOjM6NjoyNjA=/en.gif";
$flagurl['de'] = "{$tahoe_gateway}/download/VVJJOkNISzp6czZ4cDZzZW0zdHE3ZHdhZWdsYmlncGZrZTpydm1tc2xlcHl5dWR1NXdvdWxrN2RlbTU1N3ZucXpldzY0NG5paWFpdWIyNWhjczJsamVxOjM6NjozNjI=/de.gif";
$flagurl['nl'] = "{$tahoe_gateway}/download/VVJJOkNISzpuNmVmcWJ6aWtuc3VxeXd3emtseDJ1bjZ3YTpwMzNvcW14eHk2bHljMmpkcXJyeG50bXlieW5od280cnFtbWFrZTd4cHNqMjRrb3VxY2VxOjM6NjozNjA=/nl.gif";
$flagurl['fr'] = "{$tahoe_gateway}/download/VVJJOkNISzozMnhvbzVha2I0Y2xrZGcybnB1ejV4MmozaTpzbm83bzM3aWE3cGpwZXZvcnVweXVvdW9iY2pzZ3I1YnJ6bzRubG5kcDRuYW90N2ZmejVhOjM6NjozNjY=/fr.gif";
$flagurl['it'] = "{$tahoe_gateway}/download/VVJJOkNISzo0b2JzZ2FsbWJtdHFtaDVlcGYydnNrcWtrcTptaTZpamI1aXBuM3drZWZ0YzVnenZydmJheHAzYnFxeHY1Y2F6dnI2cmwyaXl6cGNxYXhhOjM6NjozNjY=/it.gif";
$flagurl['es'] = "{$tahoe_gateway}/download/VVJJOkNISzpoMjVtNHd3NXF4anNvM2Jwem50cDd1MzUyNDp5bnl6aGltZGE3aTR2b2ltY2V0cG9yc2h3bGxreWx5d3o1ZGdzaGo2dzJrenBmcm9senRxOjM6NjozNjA=/es.gif";
$flagurl['no'] = "{$tahoe_gateway}/download/VVJJOkNISzprNHQ0YjRnNW1teGNtdHV2djZkZDNkY25kNDp3aml2NWhnYXdldzZheDV4ZWxyYmlwZ2Z0Y3JqY2hpYTM3dTVybmFiMmt5Y3RxcWh2a2RxOjM6NjozNzY=/no.gif";
$flagurl['se'] = "{$tahoe_gateway}/download/VVJJOkNISzp4aDQ0Zjd6c2E0Y2Q0ZTY3Nm9uc2pxdG43cTo0bmxybGJpMmRpN2tmaWticXd3eDR2bnhoNnFxZzZrNXg3eXc0aDJsY3lsbmpsdG5sNGJxOjM6NjozNjc=/se.gif";
$flagurl['fi'] = "{$tahoe_gateway}/download/VVJJOkNISzprbG9maGF1ancyZmtxM251eWtnanR5eGV5YTp5eWtkczJ5d3VncXpvaGpkNmV6cnJkNm42b3A2MnpoZ3FqdnprN3R3cnA2cHZ0dGZmZ2phOjM6NjozNzE=/fi.gif";
$flagurl['kr'] = "{$tahoe_gateway}/download/VVJJOkNISzp1Ym1mbnRubGIyYXVlbjRlYXE0dHVkbWVjNDp6bmx5emNkN2N1eTI1NnZmZXp5eHBuZm1idW8ybmFrNHRhNG53eXk0cHVodmRqMnY0ZTJhOjM6NjozODU=/kr.gif";
$flagurl['ru'] = "{$tahoe_gateway}/download/VVJJOkNISzpiNHpvcW5qdnAzdjdwNGJ5cWU1ZDVpcW0zbTpnNmtieHZ6aTdvenFnamppMmVkaWJ5emI0YWRxNGtyZWgyanZmNzIyejRlaHNiN2tibHNhOjM6NjozNjE=/ru.gif";
$flagurl['mk'] = "{$tahoe_gateway}/download/VVJJOkNISzp2YW1xN3E2NW9zcWp0NXBwdHkzc3N4ZWh2dTpjaHJybnBoYnhucWlvc3BmdGl1aTJqYnV1aHNwem92Y3hmY3Nwb3Z0N2szbDVibWtpZWthOjM6NjozODI=/mk.gif";
$flagurl['pl'] = "{$tahoe_gateway}/download/VVJJOkNISzo2Nm13cGxsams2NXpseDd3a3lzZnpza3c1YToyYmEyZGR1NXh2dTRnczRkZXVjN2x2YmYyZ2xycmJwNG94NXQ3aW9mZzZyNGljbWxhcGNxOjM6NjozNjA=/pl.gif";
$flagurl['ba'] = "{$tahoe_gateway}/download/VVJJOkNISzpkcnUzbTZvc2JqYW50b2NvNDdyaGhqZ2FxNDpxdWF2cGpobWNwbDV5ZnpqazRveHkzcnFreTNqdHlkcWFjcHc0M2ZhMmFmM2ppYmR0NHdxOjM6NjozNjM=/ba.gif";
$flagurl['si'] = "{$tahoe_gateway}/download/VVJJOkNISzpycmptNXBmeXZ4N3p3cHR1YXU1b2Npc2xscTpiaHFuamp6N2ltaWVqYXg3ZXl6ZmF2bjRtbWc1bnR4eHF3NTRqamdhaWdnb2xwcGZiNXVxOjM6NjozNjI=/si.gif";

$languages = array(
	"en" => "English",
	"fr" => "French",
	"de" => "German",
	"nl" => "Dutch",
	"es" => "Spanish",
	"it" => "Italian",
	"ar" => "Arabic",
	"tr" => "Turkish",
	"ba" => "Bosnian",
	"rs" => "Serbian",
	"pl" => "Polish",
	"hr" => "Croatian",
	"00" => "Other"
);

if(!isset($parts[0]) || $parts[0] !== "localize")
{
	$target_uri = $_SERVER['REQUEST_URI'];
}
else
{
	$target_uri = preg_replace("/localize\/[a-z]{1,3}\/?/", "", $_SERVER['REQUEST_URI']);
}

// generate the list of flags
$flaglist = "";
foreach($langfiles as $key => $value)
{
	$flaglist .= "<a href=\"/localize/$key{$target_uri}\" style=\"border: 0px;\"><img src=\"{$flagurl[$key]}\" style=\"border: 0px; padding-right: 4px;\"></a>";
	//$flaglist .= "<a href=\"?setlang=$key\" style=\"border: 0px;\"><img src=\"/flags/$key.gif\" style=\"border: 0px; padding-right: 4px;\"></a>";
}

?>
