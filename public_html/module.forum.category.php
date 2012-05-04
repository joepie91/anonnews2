<?php
if(!isset($_ANONNEWS)) { die(); }  /* Protect from direct requests */
/* This module handles the thread listing. */
$categoryname = mysql_real_escape_string($var_id);

if($result = mysql_query_cached("SELECT * FROM forum_categories WHERE `UrlName`='{$categoryname}'"))
{
	$category = $result->data[0];
	$catname = utf8entities(stripslashes($category['Name']));
	$catid = $category['Id'];
	$catthreads = (is_numeric($category['Name'])) ? $category['Name'] : 0;
	echo("<h2><a href=\"/forum\">Forum</a> &gt; {$catname}</h2>");
	?>

	
	<div class="forum-buttons">
		<a href="/forum/category/<?php echo($var_id); ?>/new">Create new thread</a>
		<div class="clear"></div>
	</div>
	
	<table class="forum-table">
		<tr>
			<th class="forum-header-threads-name">Thread Title</th>
			<th class="forum-header-threads-replies">Replies</th>
		</tr>
		<?php
		if($result = mysql_query_cached("SELECT * FROM forum_posts WHERE `CategoryId`='{$catid}' AND `ParentId`='0' ORDER BY `LastReplyTime` DESC", 10))
		{

			foreach($result->data as $post)
			{
				$teaser = cut_text(utf8entities(stripslashes($post['Body'])), 90);
				$topic = utf8entities(stripslashes($post['Topic']));
				
				echo("<tr>
					<td class=\"forum-item-threads-name\">
						<a class=\"forum-table-link\" href=\"/forum/post/{$post['Id']}\">
							<div class=\"forum-table-name\">{$topic}</div>
							<div class=\"forum-table-teaser\">{$teaser}</div>
						</a>
					</td>
					<td class=\"forum-item-threads-replies\">{$post['Replies']}</td>
				</tr>");
			}
		}
		else
		{
			echo("<tr>
				<td colspan=\"2\">There are no threads in this category yet.</td>
			</tr>");
		}
		?>
	</table>
	
	<?php
}
else
{
	$var_code = ANONNEWS_ERROR_NOT_FOUND;
	require("module.error.php");
}
?>
