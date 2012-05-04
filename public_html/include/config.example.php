<?php
$mysql_host 	= "localhost";
$mysql_user 	= "anonnews";
$mysql_pass 	= "";
$mysql_db 	= "anonnews";

/* Please note that all the URLs in the following section should be 
 * WITHOUT a trailing slash! */
$i2p_enabled 		= true;
$tahoe_server		= "http://localhost:3456";
$tahoe_gateway_clearnet = "http://tahoe-gateway.cryto.net:3719";
$tahoe_gateway_i2p	= "http://cryto-gateway.i2p";

/* This is an array of 'banners' that is seen on the frontpage. */
$site_messages_enabled = true;
$site_messages = array(
	'/forum'	=>	'<span class="strong">New: AnonNews Forum.</span> Discuss Anonymous and related subjects. Click here to visit the forum.',
	'/static/mods'	=>	'<span class="strong">AnonNews is looking for moderators.</span> Click here for more information.',
	'/static/anon'	=>	'<span class="strong">AnonNews is not just for AnonOps.</span> Anything Anonymous-related can be posted. Click for more info.'
);

/* These are the reCAPTCHA settings. You will need to get an API key at 
 * https://www.google.com/recaptcha/admin/create */
$recaptcha_pubkey 	= "";
$recaptcha_privkey 	= "";

/* These are the memcache settings. You will need to have memcache set
 * up on your server to use these. Compression requires zlib. */
$memcache_enabled 	= true;			// Whether to user memcache.
$memcache_server	= "localhost";	// The hostname of the memcached
$memcache_port		= 11211;		// The port number of memcached
$memcache_compressed	= true;			// Whether to compress memcache objects

/* Proxy ranges can be set if you have a custom reverse proxy setup, If
 * a request originates from any of these ranges, the getIp() function
 * will return the actual (forwarded) IP. The request IP is checked to
 * avoid header spoofing to bypass filters. Local IP ranges (such as
 * 127.0.0.1/8) and CloudFlare ranges are already implented and do not
 * have to be added here (although CloudFlare ranges may change in the
 * future). The ranges are in CIDR notation.
 * Example range definition:
 * $proxy_ranges = array(
 * 		'204.93.240.0/24',
 * 		'204.93.177.0/24'
 * );
 *  */
$proxy_ranges = array();

/* Language detection requires PEAR:Text_LanguageDetect to be installed
 * (http://pear.php.net/package/Text_LanguageDetect). */
$detect_language = true;

/* The directory where pre-rendered objects are stored. This directory
 * must be writable by PHP. */
$render_dir = "render";

/* The 'recent submissions' timespan in days. */
$recent_days = 14;

/* The static pages. */
$static_pages = array(
	"irc"		=> "irc.static.php",
	"moderation"	=> "moderation.static.php",
	"mods"		=> "mods.static.php",
	"donate"	=> "donate.static.php",
	"faq"		=> "faq.static.php",
	"anon"		=> "anon.static.php",
	"forumrules"	=> "forumrules.static.php"
);
?>
