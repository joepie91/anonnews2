<?php
if(!isset($_ANONNEWS)) { die(); }  /* Protect from direct requests */

/* Custom error codes:
 * ANONNEWS_ERROR_BANNED - Banned from submission
 * ANONNEWS_ERROR_EMPTY_TITLE - Empty title
 * ANONNEWS_ERROR_EMPTY_BODY - Empty body
 * ANONNEWS_ERROR_EMPTY_URL - Empty URL
 * ANONNEWS_ERROR_INCORRECT_FORMAT - Incorrect format for uploaded file
 * ANONNEWS_ERROR_TOO_LARGE - Uploaded file too large
 * ANONNEWS_ERROR_UPLOAD_ERR - Unknown upload error
 * ANONNEWS_ERROR_SHORTENER_DETECTED - URL shortener detected
 * ANONNEWS_ERROR_URL_BLACKLISTED - URL blacklisted
 * ANONNEWS_ERROR_SPAM - Spam detected
 * ANONNEWS_ERROR_DATABASE_ERROR - Database error
 * ANONNEWS_ERROR_INCORRECT_CAPTCHA - Incorrect CAPTCHA entered
 * ANONNEWS_ERROR_NO_RECORDS_FOUND - No records found
 * ANONNEWS_ERROR_NONEXISTENT_URL - URL does not exist
 * ANONNEWS_ERROR_MALFORMED_DATA - Malformed data was received
 * ANONNEWS_ERROR_COMMENT_BODY - Comment body was not entered
 * ANONNEWS_ERROR_COMMENT_NAME - Comment name was not entered
 * ANONNEWS_ERROR_POST_TOPIC - Forum post topic was not entered
 * ANONNEWS_ERROR_POST_BODY - Forum post body was not entered
 */


if($var_code == ANONNEWS_ERROR_BANNED)
{
	$var_header = "Error: You are banned from submission.";
	$var_message = "Your IP address was banned for spam or other abuse in the past, and you cannot make any submissions to AnonNews.
	If you believe this is in error, contact an administrator on IRC. Otherwise, <a href=\"/\">click here</a> to go back to the homepage.";
}
elseif($var_code == ANONNEWS_ERROR_EMPTY_TITLE)
{
	$var_header = "Error: Empty title field.";
	$var_message = "The title field cannot be empty. Go back and try again.";
}
elseif($var_code == ANONNEWS_ERROR_EMPTY_BODY)
{
	$var_header = "Error: Empty press release text.";
	$var_message = "The press release text cannot be empty. Go back and try again.";
}
elseif($var_code == ANONNEWS_ERROR_EMPTY_URL)
{
	$var_header = "Error: Empty URL field.";
	$var_message = "The URL field cannot be empty. Go back and try again.";
}
elseif($var_code == ANONNEWS_ERROR_INCORRECT_FORMAT)
{
	$var_header = "Error: File format not allowed.";
	$var_message = "The file you uploaded is in a format that is not allowed. Only PNG, GIF, and JPG files are allowed. Go back and try again.";
}
elseif($var_code == ANONNEWS_ERROR_TOO_LARGE)
{
	$var_header = "Error: File too large.";
	$var_message = "The file you uploaded is too large. The maximum filesize is approximately 20 megabytes. Go back and try again.";
}
elseif($var_code == ANONNEWS_ERROR_UPLOAD_ERR)
{
	$var_header = "Error: Unknown uploading error.";
	$var_message = "The upload did not succeed, but it's not clear why. Go back and try again - if the error persists, contact an administrator on IRC.";
}
elseif($var_code == ANONNEWS_ERROR_SHORTENER_DETECTED)
{
	$var_header = "Error: URL shortener / redirector detected.";
	$var_message = "You cannot submit shortened or redirected URLs to AnonNews. Go back and try again with the real URL.";
}
elseif($var_code == ANONNEWS_ERROR_URL_BLACKLISTED)
{
	$var_header = "Error: URL blacklisted.";
	$var_message = "The URL you tried to submit is blacklisted or detected as a link shortener / redirector. Please only submit full-length URLs that directly lead to the intended page.
	If you believe this is in error, contact an administrator on IRC.";
}
elseif($var_code == ANONNEWS_ERROR_SPAM)
{
	$var_header = "Error: Spam detected.";
	$var_message = "The spam filter has flagged your submission as spam. You will not be able to submit it to AnonNews. If you believe this is in error, contact an administrator on IRC.";
}
elseif($var_code == ANONNEWS_ERROR_DATABASE_ERROR)
{
	$var_header = "Error: Database Error.";
	$var_message = "A database error occurred. Go back and try again. If this error persists, contact an administrator on IRC.";
}
elseif($var_code == ANONNEWS_ERROR_INCORRECT_CAPTCHA)
{
	$var_header = "Error: Incorrect CAPTCHA entered.";
	$var_message = "You did not enter the correct CAPTCHA. Go back and enter the characters in the CAPTCHA image into the input field, to verify you are not a bot.";
}
elseif($var_code == ANONNEWS_ERROR_NO_RECORDS_FOUND)
{
	$var_header = "Error: No records found in database.";
	$var_message = "There are no records to display. Possibly nothing has been submitted yet.";
}
elseif($var_code == ANONNEWS_ERROR_NONEXISTENT_URL)
{
	$var_header = "Error: URL does not exist.";
	$var_message = "The URL you tried to submit can not be retrieved. Either the site is unreachable, or the specific page you linked to does not exist. If you believe this is in error, contact an administrator on IRC.";
}
elseif($var_code == ANONNEWS_ERROR_MALFORMED_DATA)
{
	$var_header = "Error: Malformed data was received.";
	$var_message = "Retrieving the URL you tried to submit, resulted in a malformed response. Please contact an administrator on IRC.";
}
elseif($var_code == ANONNEWS_ERROR_COMMENT_BODY)
{
	$var_header = "Error: No comment entered.";
	$var_message = "You did not enter a comment. Go back and try again.";
}
elseif($var_code == ANONNEWS_ERROR_COMMENT_NAME)
{
	$var_header = "Error: No name entered.";
	$var_message = "You did not enter a name. Go back and try again.";
}
elseif($var_code == ANONNEWS_ERROR_POST_TOPIC)
{
	$var_header = "Error: No topic entered.";
	$var_message = "You did not enter a topic. Go back and try again.";
}
elseif($var_code == ANONNEWS_ERROR_POST_BODY)
{
	$var_header = "Error: No message body entered.";
	$var_message = "You did not enter a message body. Go back and try again.";
}
elseif($var_code == ANONNEWS_ERROR_NOT_FOUND)
{
	$var_header = "404 Error: Requested resource could not be found";
	$var_message = "The requested resource could not be located. If you followed a valid link, contact an administrator on IRC. Otherwise, <a href=\"/\">click here</a> to go back to the homepage.";
}
else
{
	$var_header = "Unknown error";
	$var_message = "An unknown error occurred. If you followed a valid link, contact an administrator on IRC. Otherwise, <a href=\"/\">click here</a> to go back to the homepage.";
}

echo("<h3>$var_header</h3> $var_message");
?>
