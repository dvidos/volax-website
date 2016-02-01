<h1>Tags</h1>

<p><?php
	echo CHtml::link('Λίστα', array('index'), array('class'=>'button')); 
	echo ' ';
	echo CHtml::link('Μαζική μετονομασία', array('rename'), array('class'=>'button')); 
?></p>


<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>

