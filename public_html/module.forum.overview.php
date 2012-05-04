<?php
if(!isset($_ANONNEWS)) { die(); }  /* Protect from direct requests */
/* This module handles the category overview. */
?>

<h2>Forum</h2>

<div class="forum-header">
	Be sure to read the <a href="/static/forumrules">Forum Rules</a>! All posting is anonymous, no registration is necessary and no IPs are kept.
</div>

<table class="forum-table">
	<tr>
		<th class="forum-header-category-name">Category</th>
		<th class="forum-header-category-threads">Threads</th>
		<th class="forum-header-category-posts">Posts</th>
	</tr>
	<?php
	$result = mysql_query_cached("SELECT * FROM forum_categories ORDER BY `Name` ASC", 10);

	foreach($result->data as $category)
	{
		if($category['Posts'] > 0)
		{
			$posttime = date("F j, Y H:i:s", strtotime($category['LastPostTime']));
			$lasttopic = utf8entities($category['LastPostTopic']);
			$lastpost = "Last post: <strong>{$lasttopic}</strong> @ {$posttime}";
		}
		else
		{
			$lastpost = "There are no posts in this category yet.";
		}
		
		echo("<tr>
			<td class=\"forum-item-category-name\">
				<a class=\"forum-table-link\" href=\"/forum/category/{$category['UrlName']}\">
					<div class=\"forum-table-name\">{$category['Name']}</div>
					<div class=\"forum-table-date\">{$lastpost}</div>
				</a>
			</td>
			<td class=\"forum-item-category-threads\">{$category['Threads']}</td>
			<td class=\"forum-item-category-posts\">{$category['Posts']}</td>
		</tr>");
	}
	?>
</table>
