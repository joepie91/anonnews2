<?php
if(isset($_POST['submit']))
{
	$arr = explode("\n",$_POST['strings']);
	for($i=0;$i<43;$i++)
	{
	echo("\$lang[$i] = \"".str_replace("\"","\\\"",str_replace("\r","",$arr[$i]))."\";<br>");
	}
}
else
{
	?>
	<form method="post" action="genlang.php">
		<textarea name="strings"></textarea>
		<button type="submit" name="submit">Convert</button>
	</form>
	<?php
}
?>
