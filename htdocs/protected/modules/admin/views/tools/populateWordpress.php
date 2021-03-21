
<h1>Populate Wordpress</h1>
<h2>Settings</h2>

<p>Url arguments supported, variables below with values: "all", "1,5,10", "200-300", "165", ""<br />
Media is used as a prefix match (e.g. "/uploads/dvidos/2012")
</p>

<ul>
<?php
	echo "<li>Posts: {$desired_posts}</li>";
	echo "<li>Categories: {$desired_categories}</li>";
	echo "<li>Tags: {$desired_tags}</li>";
	echo "<li>Pages: {$desired_pages}</li>";
	echo "<li>Media: {$desired_media}</li>";
?>
</ul>


<h2>History</h2>
<pre>
<?php echo $log; ?>
</pre>
