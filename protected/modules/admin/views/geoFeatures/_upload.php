<div class="wide form">

<?php echo CHtml::beginForm($this->createUrl('upload'), 'post', array('enctype'=>'multipart/form-data')); ?>

	<div class="row">
		Υποστηριζόμενες επεκτάσεις αρχείων: <?php echo implode(', ', Yii::app()->geoFileConverter->supportedFormats); ?>
	</div>
	
	<div class="row">
		<?php echo CHtml::fileField('waypoints_file'); ?>
	</div>

	<div class="row">
		<?php echo CHtml::submitButton('Αποστολή'); ?>
	</div>

<?php echo CHtml::endForm(); ?>

</div><!-- search-form -->