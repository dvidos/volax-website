<div class="form">
	<script src="<?php echo Yii::app()->baseUrl; ?>/assets/ckeditor/ckeditor.js"></script>


<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'user-form',
	'enableAjaxValidation'=>false,
)); ?>
<?php echo $form->errorSummary($model); ?>

<table>
<tr><td width="66%" style="vertical-align: top;">

	<div class="row">
		<?php echo $form->labelEx($model,'title'); ?>
		<?php echo $form->textField($model,'title',array('style'=>'width:100%;','maxlength'=>100)); ?>
		<?php echo $form->error($model,'title'); ?>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($model,'content'); ?>
		<?php echo $form->textArea($model,'content', array('style'=>'width:100%;')); ?>
		<?php echo $form->error($model,'content'); ?>
		<?php $this->widget('application.components.CkEditorWidget', array('varName' => 'Category_content', 'height'=>200)); ?>
	</div>

	<?php echo CHtml::submitButton($model->isNewRecord ? 'Δημιουργία' : 'Αποθήκευση'); ?>
	
	
</td><td width="33%" style="vertical-align: top;">
	
	<div class="row">
		<?php echo $form->labelEx($model,'parent_id'); ?>
		<?php echo $form->dropDownList($model,'parent_id',Category::dropDownListItems()); ?>
		<?php echo $form->error($model,'parent_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'view_order', array('label'=>'Σειρά εμφάνισης στην πατρική')); ?>
		<?php echo $form->textField($model,'view_order',array('size'=>5,'maxlength'=>5)); ?>
		<?php echo $form->error($model,'view_order'); ?>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($model,'status'); ?>
		<?php echo $form->dropDownList($model,'status',Status::items('CategoryStatus')); ?>
		<?php echo $form->error($model,'status'); ?>
	</div>

</td></tr>
</table>

<?php $this->endWidget(); ?>

</div><!-- form -->