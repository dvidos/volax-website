<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'user-form',
	'enableAjaxValidation'=>false,
)); ?>
<?php echo $form->errorSummary($model); ?>

<table style="border:1px solid #ddd; background-color: #eee; padding: .5em 1em;"><tr><td>

	<div class="row">
		<?php echo $form->labelEx($model,'title'); ?>
		<?php echo $form->textField($model,'title',array('size'=>100,'maxlength'=>100)); ?>
		<?php echo $form->error($model,'title'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'image_filename'); ?>
		<?php echo $form->textField($model,'image_filename',array('size'=>100,'maxlength'=>100)); ?>
		<?php echo $form->error($model,'image_filename'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'content'); ?>
		<?php echo $form->textArea($model,'content', array('style'=>'width:100%; min-height:350px;')); ?>
		<?php echo $form->error($model,'content'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

</td></tr></table>

<?php $this->endWidget(); ?>

</div><!-- form -->