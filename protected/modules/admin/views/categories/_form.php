<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'user-form',
	'enableAjaxValidation'=>false,
)); ?>
<?php echo $form->errorSummary($model); ?>

<table>
<tr><td colspan="3">

	<div class="not-row">
		<?php echo $form->labelEx($model,'title'); ?>
		<?php echo $form->textField($model,'title',array('size'=>100,'maxlength'=>100)); ?>
		<?php echo $form->error($model,'title'); ?>
	</div>
	
<tr><td>
<tr><td colspan="3">

	<div class="not-row">
		<?php echo $form->labelEx($model,'image_filename'); ?>
		<?php /* echo $form->textField($model,'image_filename',array('size'=>100,'maxlength'=>100)); */ ?>
		<?php echo $this->widget('application.components.elFinder.ServerFileInput', array(
			'model' => $model,
			'attribute' => 'image_filename',
			'connectorRoute' => '/admin/elfinder/connector',
		)); ?>
		<?php echo $form->error($model,'image_filename'); ?>
	</div>

</td></tr>
<tr><td>

	<div class="not-row">
		<?php echo $form->labelEx($model,'parent_id'); ?>
		<?php echo $form->dropDownList($model,'parent_id',Category::dropDownListItems()); ?>
		<?php echo $form->error($model,'parent_id'); ?>
	</div>
	
</td><td>

	<div class="not-row">
		<?php echo $form->labelEx($model,'view_order'); ?>
		<?php echo $form->textField($model,'view_order',array('size'=>5,'maxlength'=>5)); ?>
		<?php echo $form->error($model,'view_order'); ?>
	</div>

</td><td>

	<div class="not-row">
		<?php echo $form->labelEx($model,'status'); ?>
		<?php echo $form->dropDownList($model,'status',Status::items('CategoryStatus')); ?>
		<?php echo $form->error($model,'status'); ?>
	</div>

</td></tr>
<tr><td colspan="3">

	<div class="not-row">
		<?php echo $form->labelEx($model,'content'); ?>
		<?php echo $form->textArea($model,'content', array('style'=>'width:100%; min-height:250px;')); ?>
		<?php echo $form->error($model,'content'); ?>
	</div>

</td></tr>
<tr><td colspan="3">

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

</td></tr>
</table>
<?php $this->endWidget(); ?>

</div><!-- form -->