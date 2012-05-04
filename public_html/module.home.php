<?php
if(!isset($_ANONNEWS)) { die(); }  /* Protect from direct requests */
/* This module takes care of the home page. */

$result = mysql_query_cached("SELECT COUNT(*) FROM press WHERE `Deleted`='0' AND `Approved`='1'", 600);
$total_press = $result->data[0]['COUNT(*)'];

$result = mysql_query_cached("SELECT COUNT(*) FROM ext WHERE `Deleted`='0' AND `Visible`='1'", 600);
$total_ext = $result->data[0]['COUNT(*)'];

$result = mysql_query_cached("SELECT COUNT(*) FROM sites WHERE `Deleted`='0' AND `Approved`='1'", 600);
$total_sites = $result->data[0]['COUNT(*)'];

if($site_messages_enabled === true)
{
	$sitemsg_id = floor(rand(0, count($site_messages)-1));

	$i = 0;
	$sitemsg_url = "";
	$sitemsg_message = "";
	foreach($site_messages as $key => $message)
	{
		if($i == $sitemsg_id)
		{
			$sitemsg_url = $key;
			$sitemsg_message = $message;
		}
		$i += 1;
	}

	echo("<div class=\"section-wrapper\">
		<a class=\"header-button\" style=\"float: none; display: block;\" href=\"$sitemsg_url\" target=\"_blank\">$sitemsg_message</a>
		<div class=\"clear\"></div>
	</div>");
}
?>

<div class="section-wrapper">
	<div class="section-header">
		<?php echo($lang[3]); ?>
		<a href="#" class="tab-active" onclick="switchTab(this); $('#load_press')[0].style.display = 'block'; $('#container_press').load('process.frontpage.php?q=press_overview', function(){$('#load_press')[0].style.display = 'none';}); return false;">Overview</a>
		<a href="/press/list/upvotes/desc/" class="tab" onclick="switchTab(this); $('#load_press')[0].style.display = 'block'; $('#container_press').load('process.frontpage.php?q=press_top', function(){$('#load_press')[0].style.display = 'none';}); return false;">Highest rated</a>
		<a href="/press/list/date/desc/" class="tab" onclick="switchTab(this); $('#load_press')[0].style.display = 'block'; $('#container_press').load('process.frontpage.php?q=press_latest', function(){$('#load_press')[0].style.display = 'none';}); return false;">Most recent</a>
	</div>
	<div class="section">
		<span id="container_press">
			<?php
			$query = "SELECT * FROM press WHERE `Deleted`='0' AND `Approved`='1' AND `Posted` >= DATE_SUB(CURRENT_DATE(), INTERVAL {$recent_days} DAY) ORDER BY `Upvotes` DESC LIMIT 3";
			if($result = mysql_query_cached($query))
			{
				foreach($result->data as $item)
				{
					$name = utf8entities(stripslashes($item['Name']));
					$id = $item['Id'];
					$comments = $item['CommentCount'];
					$upvotes = $item['Upvotes'];
					
					echo(template_item($name, "press", $id, $comments, true, $upvotes, 0));
				}
			}

			$query = "SELECT * FROM press WHERE `Deleted`='0' AND `Approved`='1' ORDER BY `Posted` DESC LIMIT 3";
			if($result = mysql_query_cached($query))
			{
				foreach($result->data as $item)
				{
					$name = utf8entities(stripslashes($item['Name']));
					$id = $item['Id'];
					$comments = $item['CommentCount'];
					$upvotes = $item['Upvotes'];
					
					echo(template_item($name, "press", $id, $comments, false, $upvotes, 0));
				}
			}
			?>
		</span>
		<img src="http://tahoe-gateway.cryto.net:3719/download/VVJJOkNISzozM2l5cW90ZWNhY2NoazczY295NHd5YjY0aTp6YjM3aXpiNW5mZmtzc243eDRhaGM3dm10cWpzbHpub21leWdjYjJsbGN3Y3lmM3p4YzVxOjM6NjoyNTQ1/loading.gif" class="loader" id="load_press">
		<a href="/press/list/date/desc/" class="section-button">More (<?php echo($total_press); ?>) <span class="bold">&gt;&gt;</span></a>
		<a href="/press/add/" class="section-button"><span class="bold">+</span> Add</a>
		<div class="clear"></div>
	</div>
</div>


<div class="section-wrapper">
	<div class="section-header">
		<?php echo($lang[6]); ?>
	</div>
	<div class="section">
		<?php
		$query = "SELECT * FROM ext WHERE `Deleted`='0' AND `Visible`='1' ORDER BY `Posted` DESC LIMIT 4";
		if($result = mysql_query_cached($query))
		{
			foreach($result->data as $item)
			{
				$name = utf8entities(stripslashes($item['Name']));
				$id = $item['Id'];
				$comments = $item['CommentCount'];
				$rank = $item['Rank'];
				
				echo(template_item($name, "external-news", $id, $comments, false, 0, $rank));
			}
		}
		?>
		<a href="/external-news/list/date/desc/" class="section-button">More (<?php echo($total_ext); ?>) <span class="bold">&gt;&gt;</span></a>
		<a href="/external-news/add/" class="section-button"><span class="bold">+</span> Add</a>
		<div class="clear"></div>
	</div>
</div>

<div class="section-wrapper">
	<div class="section-header">
		<?php echo($lang[4]); ?>
		<a href="#" class="tab-active" onclick="switchTab(this); $('#load_topext')[0].style.display = 'block'; $('#container_topext').load('process.frontpage.php?q=ext_top_7days', function(){$('#load_topext')[0].style.display = 'none';}); return false;">Last <?php echo($recent_days); ?> days</a>
		<a href="#" class="tab" onclick="switchTab(this); $('#load_topext')[0].style.display = 'block'; $('#container_topext').load('process.frontpage.php?q=ext_top_all', function(){$('#load_topext')[0].style.display = 'none';}); return false;">Forever</a>
	</div>
	<div class="section">
		<span id="container_topext">
			<?php
			$query = "SELECT * FROM ext WHERE `Deleted`='0' AND `Visible`='1' AND `Posted` >= DATE_SUB(CURRENT_DATE(), INTERVAL {$recent_days} DAY) ORDER BY `Rank` DESC LIMIT 4";
			if($result = mysql_query_cached($query))
			{
				foreach($result->data as $item)
				{
					$name = utf8entities(stripslashes($item['Name']));
					$id = $item['Id'];
					$comments = $item['CommentCount'];
					$rank = $item['Rank'];
					
					echo(template_item($name, "external-news", $id, $comments, false, 0, $rank));
				}
			}
			?>
		</span>
		<img src="http://tahoe-gateway.cryto.net:3719/download/VVJJOkNISzozM2l5cW90ZWNhY2NoazczY295NHd5YjY0aTp6YjM3aXpiNW5mZmtzc243eDRhaGM3dm10cWpzbHpub21leWdjYjJsbGN3Y3lmM3p4YzVxOjM6NjoyNTQ1/loading.gif" class="loader" id="load_topext">
		<a href="/external-news/list/rank/desc/" class="section-button">More (<?php echo($total_ext); ?>) <span class="bold">&gt;&gt;</span></a>
		<a href="/external-news/add/" class="section-button"><span class="bold">+</span> Add</a>
		<div class="clear"></div>
	</div>
</div>

<div class="section-wrapper">
	<div class="section-header">
		<?php echo($lang[5]); ?>
		<a href="#" class="tab-active" onclick="switchTab(this); $('#load_bottomext')[0].style.display = 'block'; $('#container_bottomext').load('process.frontpage.php?q=ext_bottom_7days', function(){$('#load_bottomext')[0].style.display = 'none';}); return false;">Last <?php echo($recent_days); ?> days</a>
		<a href="#" class="tab" onclick="switchTab(this); $('#load_bottomext')[0].style.display = 'block'; $('#container_bottomext').load('process.frontpage.php?q=ext_bottom_all', function(){$('#load_bottomext')[0].style.display = 'none';}); return false;">Forever</a>
	</div>
	<div class="section">
		<span id="container_bottomext">
			<?php
			$query = "SELECT * FROM ext WHERE `Deleted`='0' AND `Visible`='1' AND `Posted` >= DATE_SUB(CURRENT_DATE(), INTERVAL 7 DAY) ORDER BY `Rank` ASC LIMIT 4";
			if($result = mysql_query_cached($query))
			{
				foreach($result->data as $item)
				{
					$name = utf8entities(stripslashes($item['Name']));
					$id = $item['Id'];
					$comments = $item['CommentCount'];
					$rank = $item['Rank'];
					
					echo(template_item($name, "external-news", $id, $comments, false, 0, $rank));
				}
			}
			?>
		</span>
		<img src="http://tahoe-gateway.cryto.net:3719/download/VVJJOkNISzozM2l5cW90ZWNhY2NoazczY295NHd5YjY0aTp6YjM3aXpiNW5mZmtzc243eDRhaGM3dm10cWpzbHpub21leWdjYjJsbGN3Y3lmM3p4YzVxOjM6NjoyNTQ1/loading.gif" class="loader" id="load_bottomext">
		<a href="/external-news/list/rank/asc/" class="section-button">More (<?php echo($total_ext); ?>) <span class="bold">&gt;&gt;</span></a>
		<a href="/external-news/add/" class="section-button"><span class="bold">+</span> Add</a>
		<div class="clear"></div>
	</div>
</div>

<div class="section-wrapper">
	<div class="section-header">
		<?php echo($lang[7]); ?>
	</div>
	<div class="section">
		<?php
		$query = "SELECT * FROM sites WHERE `Deleted`='0' AND `Approved`='1' ORDER BY `Posted` DESC LIMIT 4";
		if($result = mysql_query_cached($query))
		{
			foreach($result->data as $item)
			{
				$name = utf8entities(stripslashes($item['Name']));
				$id = $item['Id'];
				$comments = $item['CommentCount'];
				
				echo(template_item($name, "related-sites", $id, $comments, false, 0, 0));
			}
		}
		?>
		<a href="/related-sites/list/desc/" class="section-button">More (<?php echo($total_sites); ?>) <span class="bold">&gt;&gt;</span></a>
		<a href="/related-sites/add/" class="section-button"><span class="bold">+</span> Add</a>
		<div class="clear"></div>
	</div>
</div>
