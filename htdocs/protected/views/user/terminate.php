<?php
	$this->pageTitle='Τερματισμός λογαριασμού';
?>

<h1>Τερματισμός λογαριασμού</h1>

<p>Αν προχωρήσετε ο λογαριασμός σας θα διαγραφεί. Αν θέλετε να ξανασυνδεθείτε θα πρέπει να εγγραφείτε πάλι.<p>

<p>Παρακαλούμε επιβεβαιώστε τον τερματισμό του λογαριασμού σας.</p>

<div class="form">
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'terminate-form',
	'enableAjaxValidation'=>false,
)); ?>

	<div class="row checkbox">
		<?php echo $form->checkBox($model,'verify'); ?>
		<?php echo $form->label($model,'verify'); ?>
		<?php echo $form->error($model,'verify'); ?>
	</div>

	<div class="row submit">
		<?php echo CHtml::submitButton('Τερματισμός'); ?>
	</div>

<?php $this->endWidget(); ?>
</div><!-- form -->

