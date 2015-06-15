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
			'value'=>'$data->was_deleted ? $data->title . " (Διαγραφή)": ($data->post == null ? $data->post_id : $data->post->title . " (" . $data->post_id . ")")',
			'header'=>'Ανάρτηση (id)',
			//'filter'=>'',
		),
		array(
			'name'=>'revision_no',
			'type'=>'raw',
			'value'=>'$data->revision_no',
			//'filter'=>'',
		),
		array(
			'name'=>'was_deleted',
			'type'=>'raw',
			'value'=>'$data->was_deleted ? "Ναι" : ""',
			'header'=>'Διαγρ',
			'filter'=>array(
				0=>'Οχι',
				1=>'Ναι',
			),
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
