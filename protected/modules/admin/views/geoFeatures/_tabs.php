<?php
	$this->widget('zii.widgets.CMenu',array(
		'htmlOptions'=>array('class'=>'tabs'),
		'activeCssClass'=>'active',
		'items'=>array(
			array('label'=>'Διόρθωση', 'url'=>array('/admin/categories/update', 'id'=>$model->id)),
			array('label'=>'Waypoints (' . $model->waypointsCount . ')', 'url'=>array('/admin/geoWaypoints/index', 'GeoWaypoint[feature_id]'=>$model->id)),
			array('label'=>'Επισκόπιση', 'url'=>array('/geo/view', 'id'=>$model->id), 'linkOptions'=>array('target'=>'_blank')),
		),
	));
?>