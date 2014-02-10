<?php
	$pageTitle = 'Tags';
?>

<h1>Tags</h1>

<p id="tags-list">
<?php 
	foreach ($tags as $tag)
	{
		$html = CHtml::link(CHtml::encode($tag->name), array('post/list', 'tag'=>$tag->name));
		$html .= '&nbsp;(' . $tag->frequency . ')&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; ';
		echo $html;
	}
?>
</p>
