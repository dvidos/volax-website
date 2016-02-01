<?php
	$this->widget('zii.widgets.CMenu',array(
		'htmlOptions'=>array('class'=>'tabs'),
		'activeCssClass'=>'active',
		'items'=>array(
			array('label'=>Yii::app()->user->user->fullname, 'url'=>array('/admin/dashboard/index'), 'visible'=>true),
			array('label'=>'Γενικά', 'url'=>array('/admin/dashboard/everyone'), 'visible'=>Yii::app()->user->isAdmin),
			array('label'=>'Κατηγορίες', 'url'=>array('/admin/dashboard/categories'), 'visible'=>true),
			array('label'=>'Ιστορικό', 'url'=>array('/admin/dashboard/history'), 'visible'=>Yii::app()->user->isAdmin),
		),
	));
?>