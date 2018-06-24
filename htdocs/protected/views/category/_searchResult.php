
<div class="category-entry">
	<?php
		$link = CHtml::link(CHtml::encode($data->title), $data->url);
		echo CHtml::tag('h3', array('class'=>'title'), $link);
	?>
</div>



