<h1>Δημιουργία κατηγορίας</h1>

<?php
	$this->widget('zii.widgets.CMenu',array(
		'htmlOptions'=>array('class'=>'tabs'),
		'activeCssClass'=>'active',
		'items'=>array(
			array('label'=>'Νέα κατηγορία', 'url'=>array('/admin/categories/create')),
		),
	));
?><div class="tabs-page">
	<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
</div>