<h1>Σχόλια</h1>

<p><?php
	echo CHtml::link('Ολα', array(''), array('class'=>'button')); 
	echo ' ';
	echo CHtml::link('Εκκρεμή', array('', 'status'=>Comment::STATUS_PENDING), array('class'=>'button')); 
	echo ' ';
	echo CHtml::link('Eγκεκριμένα', array('', 'status'=>Comment::STATUS_APPROVED), array('class'=>'button')); 
?></p>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
