<?php
Yii::app()->clientScript->registerScript('search',
	"$('.search-button').click(function(){ $('.search-form').toggle(); return false; });".
	"$('.search-form form').submit(function(){ $.fn.yiiGridView.update('user-grid', { data: $(this).serialize() }); return false; });");
?>

<h1>Waypoints</h1>

<p><?php
	echo CHtml::link('Δημιουργία', array('create'), array('class'=>'button')); 
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
		//'feature_id',
		array(
			'name'=>'feature_id',
			'value'=>'$data->feature == null ? "None" : $data->feature->title',
			'filter'=>CHtml::listData(GeoFeature::model()->findAll(array('order'=>'title')), 'id', 'title'),
		),
		'waypoint_no',
		'title',
		'geo_lat',
		'geo_long',
		array(
			'class'=>'CButtonColumn',
			'template'=>'{update} {delete}',
		),
	),
)); ?>
