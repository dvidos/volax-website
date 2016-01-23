<h1>Νέα Γεωδαιτική εγγραφή</h1>

<?php
	$this->widget('zii.widgets.CMenu',array(
		'htmlOptions'=>array('class'=>'tabs'),
		'activeCssClass'=>'active',
		'items'=>array(
			array('label'=>'Νέα εγγραφή', 'url'=>array('/admin/geoFeatures/create')),
		),
	));
?><div class="tabs-page">
	<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
</div>