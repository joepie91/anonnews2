<?php
if(!isset($_ANONNEWS)) { die(); }  /* Protect from direct requests */

if(isset($_POST['submit']))
{
	// Process login.
	$sUsername = mysql_real_escape_string($_POST['username']);
	$sPassword = sha1($_POST['password']);
	
	if($result = mysql_query_cached("SELECT * FROM mods WHERE `Username` = '{$sUsername}' AND `Hash` = '{$sPassword}'"))
	{
		$_SESSION['loggedin'] = true;
		$_SESSION['userid'] = $result->data[0]['Id'];
		$_SESSION['accesslevel'] = $result->data[0]['AccessLevel'];
		echo("Successfully logged in! <a href=\"/moderation/\">Continue...</a>");
	}
	else
	{
		echo("The login details you entered are incorrect.");
	}
}
else
{
	// Show login form
	echo("
		<form method=\"post\" action=\"/moderation/login/\">
			<strong>Log in to access the moderator panel.</strong><br>
			Username: <input type=\"text\" name=\"username\"><br>
			Password: <input type=\"password\" name=\"password\"><br>
			<button type=\"submit\" name=\"submit\" value=\"submit\">Log in</button>
		</form>
	");
}
?>
