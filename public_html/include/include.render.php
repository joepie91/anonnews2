<?php
if(!isset($_ANONNEWS)) { die(); }  /* Protect from direct requests */
/* This include contains functions to deal with pre-rendered content. */

function render_comments($table, $id)
{
	/* This function will not use memcache for the MySQL queries, as 
	 * the data returned would be stale - which would obviously be a bad
	 * idea in a function that's going to either be used to force-update
	 * the render cache (in which case stale data defeats the point) or
	 * as first generation (in which case there is no data in memcache
	 * anyway). */
	 
	global $render_dir;

	if($table == "ext")
	{
		$section = "external-news";
	}
	elseif($table == "sites")
	{
		$section = "related-sites";
	}
	else
	{
		$section = "press";
	}
	 
	 
	$query = "SELECT * FROM {$table} WHERE `Id`='{$id}'";
	$res = mysql_query($query);
	if(mysql_num_rows($res) > 0)
	{
		$output = "";
		$res = mysql_query("SELECT * FROM comments WHERE `ItemId` = '{$id}' AND `Section` = '{$table}' AND `Visible` = '1' ORDER BY `Posted` ASC");

		if(mysql_num_rows($res) > 0)
		{
			while($row = mysql_fetch_array($res))
			{
				$obj->id = $row['Id'];
				$obj->itemid = $row['ItemId'];
				$obj->name = $row['Name'];
				$obj->body = $row['Body'];
				$obj->parentid = $row['ParentId'];
				$obj->postdate = $row['Posted'];
				$obj->linecount = $row['LineCount'];
				$obj->children = array();
				$dataset[$obj->id] = clone $obj;
			}

			foreach($dataset as $element)
			{
				if($element->parentid == 0)
				{
					$top[] = $element;
				}
				else
				{
					if(isset($dataset[$element->parentid]))
					{
						$dataset[$element->parentid]->children[] = $element;
					}
				}
			}

			foreach($top as $comment)
			{
				$output .= print_comment($comment, $table); 
				$output .= "<div class=\"c-spacer\"></div>";
			}	 
		}
		else
		{
			$output = "No comments have been posted on this entry yet.";
		}

		$output .= "
			<hr>
			<div class=\"c-comment\">
			<div class=\"c-reply-header\">
			Post a new comment
			</div>
			<form method=\"post\" action=\"/{$section}/item/{$id}/comments/post/\">
			<input type=\"text\" name=\"name\" value=\"Anonymous\" class=\"c-inline\">
			<textarea name=\"body\" class=\"c-inline\"></textarea>
			<div class=\"button\">
			<button type=\"submit\" name=\"submit\">Post comment</button>
			</div>
			</form>
			</div>
		";

		$path = "{$render_dir}/c-{$table}-{$id}.render";

		file_put_contents($path, $output); 

		mc_delete(md5($path) . md5($path . "x"));

		return $output;
	}
	else
	{
		return false;
	}
	 
}

function print_comment($comment, $table)
{
	$output = "";
	
	if($table == "ext")
	{
		$sect = "external-news";
	}
	elseif($table == "sites")
	{
		$sect = "related-sites";
	}
	else
	{
		$sect = "press";
	}
	
	$c_name = utf8entities(stripslashes($comment->name));
	$c_date = utf8entities($comment->postdate);
	$c_body = nl2br(utf8entities(stripslashes($comment->body)), false);
	if($comment->linecount == 1)
	{
		$output .= "
		<div>
			<div class=\"c-small\">
				<a name=\"c-{$comment->id}\"></a>
				<div class=\"c-small-actions\">
					<a class=\"c-actions-button\" alt=\"Respond to this comment\" href=\"/{$sect}/item/{$comment->itemid}/comments/post/{$comment->id}/\" onclick=\"return replyToComment(this);\">Reply</a>
					<a class=\"c-actions-button\" alt=\"Permalink to this comment\" href=\"/{$sect}/item/{$comment->itemid}/comments/#c-{$comment->id}\">Perma</a>
				</div>
				<div class=\"c-small-inner\">
					<strong>{$c_name}</strong>&nbsp;&nbsp;
					{$c_body}
				</div>
				<div class=\"clear\"></div>
			</div>
		";
	}
	else
	{
		$output .= "
		<div>
			<div class=\"c-outer\">
				<a name=\"c-{$comment->id}\"></a>
				<div class=\"c-meta\">
					<span class=\"c-meta-name\">{$c_name}</span>
					<span class=\"c-meta-date\">{$c_date}</span>
					<div class=\"clear\"></div>
				</div>
				
				<div class=\"c-actions\">
					<a class=\"c-actions-button\" alt=\"Respond to this comment\" href=\"/{$sect}/item/{$comment->itemid}/comments/post/{$comment->id}/\" onclick=\"return replyToComment(this);\">Reply</a>
					<a class=\"c-actions-button\" alt=\"Permalink to this comment\" href=\"/{$sect}/item/{$comment->itemid}/comments/#c-{$comment->id}\">Perma</a>
				</div>
				<div class=\"c-body\">
					{$c_body}
				</div>
			</div>
		";
	}
	
	$output .= "
		<div class=\"c-reply\">
			{$comment->id}
		</div>
	</div>
	<div class=\"c-children\">
	";
	
	foreach($comment->children as $child)
	{
		$output .= print_comment($child, $table);
	}
	$output .= "</div>";
	
	return $output;
}

?>
