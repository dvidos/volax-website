<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'comment-form',
	// ajax validation causes captcha to fail on first attempt
	'enableAjaxValidation'=>false,
)); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'author'); ?>
		<?php echo $form->textField($model,'author',array('size'=>60,'maxlength'=>128)); ?>
		<?php echo $form->error($model,'author'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'email'); ?>
		<?php echo $form->textField($model,'email',array('size'=>60,'maxlength'=>128)); ?>
		<?php echo $form->error($model,'email'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'url'); ?>
		<?php echo $form->textField($model,'url',array('size'=>60,'maxlength'=>128)); ?>
		<?php echo $form->error($model,'url'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'content'); ?>
		<?php echo $form->textArea($model,'content',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'content'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'captcha_content'); ?>
		<?php $this->widget('CCaptcha', array('imageOptions'=>array('style'=>'vertical-align: middle;'),'buttonLabel'=>'Αλλαγή', 'buttonType'=>'button')); ?><br />
		<?php echo $form->textField($model,'captcha_content',array('size'=>10,'maxlength'=>10)); ?>
		<?php echo $form->error($model,'captcha_content'); ?>
	</div>

	
	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Προσθήκη' : 'Αποθήκευση'); ?>
		&nbsp;
		<?php echo CHtml::link('Ακυρο', '#', array('class'=>'gray', 'onClick'=>'$("#add-comment-form").slideUp(); return false;')); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->
