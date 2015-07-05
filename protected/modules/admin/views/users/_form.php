<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'user-form',
	'enableAjaxValidation'=>false,
)); ?>

<?php echo $form->errorSummary($model); ?>

<table style="border:1px solid #ddd; background-color: #eee;">
<tr><td>

	<div class="row">
		<?php echo $form->labelEx($model,'email'); ?>
		<?php echo $form->textField($model,'email',array('size'=>50,'maxlength'=>50)); ?>
		<?php echo $form->error($model,'email'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'username'); ?>
		<?php echo $form->textField($model,'username',array('size'=>50,'maxlength'=>50)); ?>
		<?php echo $form->error($model,'username'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'fullname'); ?>
		<?php echo $form->textField($model,'fullname',array('size'=>50,'maxlength'=>50)); ?>
		<?php echo $form->error($model,'fullname'); ?>
	</div>

</td><td>

	<div class="row">
		<?php echo $form->labelEx($model,'password1'); ?>
		<?php echo $form->passwordField($model,'password1'); ?>
		<?php echo $form->error($model,'password1'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'password2'); ?>
		<?php echo $form->passwordField($model,'password2'); ?>
		<?php echo $form->error($model,'password2'); ?>
	</div>

	<div class="row checkbox">
		<?php echo $form->checkBox($model,'is_admin'); ?>
		<?php echo $form->labelEx($model,'is_admin', array('style'=>'display:inline;')); ?>
		<?php echo $form->error($model,'is_owner'); ?>
	</div>

	<div class="row checkbox">
		<?php echo $form->checkBox($model,'is_author'); ?>
		<?php echo $form->labelEx($model,'is_author', array('style'=>'display:inline;')); ?>
		<?php echo $form->error($model,'is_author'); ?>
	</div>

	
</td></tr><tr><td colspan="2">

	<div class="row checkbox">
		<?php echo $form->labelEx($model,'profile'); ?>
		<?php echo $form->textArea($model,'profile', array('style'=>'width:100%; min-height:250px;')); ?>
		<?php echo $form->error($model,'profile'); ?>
	</div>
	
	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Δημιουργία' : 'Αποθήκευση'); ?>
	</div>
	
</td></tr>
</table>
	
<?php $this->endWidget(); ?>

</div><!-- form -->