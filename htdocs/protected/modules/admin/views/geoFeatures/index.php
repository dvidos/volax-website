<?php
Yii::app()->clientScript->registerScript('search',
	"$('#search-button').click(function(){ $('.search-form').toggle(); return false; });".
	"$('.search-form form').submit(function(){ $.fn.yiiGridView.update('user-grid', { data: $(this).serialize() }); return false; });");
Yii::app()->clientScript->registerScript('upload',
	"$('#upload-button').click(function(){ $('.upload-form').toggle(); return false; });");
?>

<h1>Γεωδαιτικές εγγραφές</h1>

<p><?php
	echo CHtml::link('Δημιουργία', array('create'), array('class'=>'button')); 
	echo ' ';
	echo CHtml::link('Αποστολή αρχείου','#',array('class'=>'button', 'id'=>'upload-button')); 
	echo ' ';
	echo CHtml::link('Αναζήτηση','#',array('class'=>'button', 'id'=>'search-button')); 
?></p>

<div class="search-form" style="display:none">
<?php $this->renderPartial('_search',array(
	'model'=>$model,
)); ?>
</div><!-- search-form -->
<div class="upload-form" style="display:none">
<?php $this->renderPartial('_upload',array(
	'model'=>$model,
)); ?>
</div><!-- search-form -->

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'user-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		array(
			'name'=>'title',
			'type'=>'raw',
			'value'=>'CHtml::link(CHtml::encode($data->title), array("update", "id"=>$data->id));',
		),
		array(
			'name'=>'feature_type',
			//'type'=>'raw',
			//'value'=>'CHtml::link(CHtml::encode($data->title), array("update", "id"=>$data->id));',
			'filter'=>GeoFeature::getFeatureTypeOptions(),
		),
		array(
			'name'=>'group_id',
			'value'=>'$data->group == null ? "None" : $data->group->title',
			'filter'=>CHtml::listData(GeoGroup::model()->findAll(array('order'=>'view_order,title')), 'id', 'title'),
		),
		array(
			'name'=>'active',
			'header'=>'Δημόσιο',
			'value'=>'$data->active ? "Yes" : ""',
			'filter'=>array('1'=>'Yes','0'=>'No'),
		),
		array(
			'class'=>'CButtonColumn',
			'template'=>'{update} {delete}',
		),
	),
)); ?>
