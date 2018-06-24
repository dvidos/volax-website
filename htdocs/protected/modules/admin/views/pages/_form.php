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
		<?php echo $form->labelEx($model,'url_keyword'); ?>
		<?php echo $form->textField($model,'url_keyword',array('style'=>'width:100%;','maxlength'=>100)); ?>
		<?php echo $form->error($model,'url_keyword'); ?>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($model,'content'); ?>
		<?php echo $form->textArea($model,'content', array('style'=>'width:100%;')); ?>
		<?php echo $form->error($model,'content'); ?>
		<?php $this->widget('application.components.CkEditorWidget', array('varName' => 'Page_content')); ?>
	</div>
	<?php echo CHtml::submitButton($model->isNewRecord ? 'Δημιουργία' : 'Αποθήκευση', array('name'=>'saveAndReturn')); ?>
	
</td></tr>
</table>



<?php $this->endWidget(); ?>

</div><!-- form -->