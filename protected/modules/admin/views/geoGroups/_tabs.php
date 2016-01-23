<?php
	$this->widget('zii.widgets.CMenu',array(
		'htmlOptions'=>array('class'=>'tabs'),
		'activeCssClass'=>'active',
		'items'=>array(
			array('label'=>'Διόρθωση', 'url'=>array('update', 'id'=>$model->id)),
			array('label'=>'Εγγραφές (' . $model->featuresCount . ')', 'url'=>array('/admin/geoFeatures/index', 'GeoFeature[group_id]'=>$model->id)),
			array('label'=>'Επισκόπιση', 'url'=>array('/geoGroup/view', 'id'=>$model->id), 'linkOptions'=>array('target'=>'_blank')),
		),
	));
?>