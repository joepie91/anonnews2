<?php
if(!isset($_ANONNEWS)) { die(); }  /* Protect from direct requests */
/* This module handles all comments. */

$var_id = (is_numeric($var_id)) ? $var_id : 0;

$error = false;

if(!$output = file_get_contents_cached("{$render_dir}/c-{$var_table}-{$var_id}.render"))
{
	if(!$res = render_comments($var_table, $var_id))
	{
		$var_code = ANONNEWS_ERROR_NOT_FOUND;
		require("module.error.php");
		$error = true;
	}
	else
	{
		$output->source = "render";
		$output->data = $res;
	}
}

if($error === false)
{
	if($var_section == "press")
	{
		echo("<div class=\"form-notice\" style=\"width: 100%;\"><strong>Note:</strong> To be able to upvote a press release, you need to leave a comment that is 2 or more lines, and 100 or more characters.</div>");
	}
	echo($output->data);
}

?>
