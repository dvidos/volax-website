<?php
Yii::app()->clientScript->registerScript('search',
	"$('.search-button').click(function(){ $('.search-form').toggle(); return false; });".
	"$('.search-form form').submit(function(){ $.fn.yiiGridView.update('user-grid', { data: $(this).serialize() }); return false; });");
?>

<h1>Χρήστες</h1>

<p><?php
	echo CHtml::link('Νέος χρήστης', array('create'), array('class'=>'button')); 
	echo ' ';
	echo CHtml::link('Αναζήτηση','#',array('class'=>'search-button')); 
?></p>

<div class="search-form" style="display:none">
<?php $this->renderPartial('_search',array(
	'model'=>$model,
)); ?>
</div><!-- search-form -->

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'user-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		//'username',
		array(
			'name'=>'username',
			'type'=>'raw',
			'value'=>'CHtml::link(CHtml::encode($data->username), array("update", "id"=>$data->id));',
		),
		'email',
		'fullname',
		'initials',
		array(
			'name'=>'is_admin',
			'header'=>'Admin',
			'value'=>'$data->is_admin ? "Yes" : ""',
			'filter'=>array('1'=>'Yes','0'=>'No'),
		),
		array(
			'name'=>'is_author',
			'header'=>'Author',
			'value'=>'$data->is_author ? "Yes" : ""',
			'filter'=>array('1'=>'Yes','0'=>'No'),
		),
		array(
			'class'=>'CButtonColumn',
			'template'=>'{update} {delete}',
		),
	),
)); ?>
