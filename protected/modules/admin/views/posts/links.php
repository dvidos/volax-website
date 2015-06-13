<h1>Links εντός αναρτήσεων</h1>

<table class="bordered">
<?php
	foreach ($data as $item)
	{
		$c1 = CHtml::link(CHtml::encode($item['title']), array('/admin/posts/update', 'id'=>$item['id']));
		$c2 = implode('<br />', $item['links']);
		
		
		echo CHtml::tag('tr', array(), CHtml::tag('td', array(), $c1) .  CHtml::tag('td', array(), $c2));
	}
?>
</table>

