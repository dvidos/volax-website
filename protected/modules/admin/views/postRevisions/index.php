<h1>Ιστορικό αναρτήσεων</h1>

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'user-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		array(
			'name'=>'datetime',
			'type'=>'raw',
			'value'=>'$data->datetime',
			//'filter'=>'',
		),
		array(
			'name'=>'post_id',
			'value'=>'$data->post == null ? "None" : $data->post->title',
			//'filter'=>'',
		),
		array(
			'name'=>'revision_no',
			'type'=>'raw',
			'value'=>'$data->revision_no',
			//'filter'=>'',
		),
		array(
			'name'=>'user_id',
			'value'=>'$data->user == null ? "None" : $data->user->username',
			'filter'=>User::dropDownListItems(),
		),
		array(
			'class'=>'CButtonColumn',
			'template'=>'{view}',
		),
	),
)); ?>
