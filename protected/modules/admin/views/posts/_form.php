<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'user-form',
	'enableAjaxValidation'=>false,
)); ?>

<?php echo $form->errorSummary($model); ?>

<table>
<tr><td colspan="3">

	<?php echo $form->labelEx($model,'title'); ?>
	<?php echo $form->textField($model,'title',array('size'=>100,'maxlength'=>100)); ?>
	<?php echo $form->error($model,'title'); ?>
	
</td></tr>
<tr><td>

	<?php echo $form->labelEx($model,'category_id'); ?>
	<?php echo $form->dropDownList($model,'category_id',Category::dropDownListItems()); ?>
	<?php echo $form->error($model,'category_id'); ?>

</td><td>

	<?php echo $form->labelEx($model,'status'); ?>
	<?php echo $form->dropDownList($model,'status',Status::items('PostStatus')); ?>
	<?php echo $form->error($model,'status'); ?>

</td><td>

	<?php echo $form->labelEx($model,'tags'); ?>
	<?php $this->widget('CAutoComplete', array(
		'model'=>$model,
		'attribute'=>'tags',
		'url'=>array('suggestTags'),
		'multiple'=>true,
	)); ?>
	<p class="hint">Please separate different tags with commas.</p>
	<?php echo $form->error($model,'tags'); ?>

</td></tr>
<tr><td colspan="3">

	<?php echo $form->labelEx($model,'image_filename'); ?>
	<?php /* echo $form->textField($model,'image_filename',array('size'=>100,'maxlength'=>100)); */ ?>
	<?php echo $this->widget('application.components.elFinder.ServerFileInput', array(
		'model' => $model,
		'attribute' => 'image_filename',
		'connectorRoute' => '/admin/elfinder/connector',
		'htmlOptions'=>array('size'=>50),
	)); ?>
	<?php echo $form->error($model,'image_filename'); ?>
	
</td></tr>
<tr><td colspan="3">

	<?php echo $form->labelEx($model,'content'); ?>
	<?php echo $form->textArea($model,'content', array('style'=>'width:100%; min-height:250px;')); ?>
	<?php echo $form->error($model,'content'); ?>

</td></tr>
<tr><td colspan="3">

	<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>

</td></tr>
</table>
<?php $this->endWidget(); ?>

</div><!-- form -->