<h1>Καταστάσεις</h1>

<p><?php
	echo CHtml::link('Δημιουργία', array('create'), array('class'=>'button')); 
?></p>


<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'advertisement-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		array(
			'name'=>'name',
			'type'=>'raw',
			'value'=>'CHtml::link(CHtml::encode($data->name), array("update", "id"=>$data->id))',
		),
		array(
			'name'=>'type',
			'header'=>'Type',
			'value'=>'$data->type',
			'filter'=>Status::getTypeOptions(),
		),
		array(
			'class'=>'CButtonColumn',
			'template'=>'{update} {delete}',
		),
	),
)); ?>
