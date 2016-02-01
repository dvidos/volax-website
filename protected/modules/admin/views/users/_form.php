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

	<div class="row">
		<?php echo $form->labelEx($model,'registered_at'); ?>
		<?php echo $form->textField($model,'registered_at',array('name'=>'ra', 'readonly'=>'readonly', 'disabled'=>'disabled')); ?>
		<?php echo $form->error($model,'registered_at'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'last_login_at'); ?>
		<?php echo $form->textField($model,'last_login_at',array('name'=>'lla', 'readonly'=>'readonly', 'disabled'=>'disabled')); ?>
		<?php echo $form->error($model,'last_login_at'); ?>
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

	<?php
		if (Yii::app()->user->hasFlash('passwordChanged'))
		{
			echo CHtml::tag('div', array('class'=>'flash-success'), Yii::app()->user->getFlash('passwordChanged'));
			$js = '$(document).ready(function(){ setTimeout(function() { $(".flash-success").slideUp(); }, 4000); });';
			echo CHtml::tag('script', array(), $js);
		}
	?>
	
	<div class="row">
		<?php echo $form->labelEx($model,'initials'); ?>
		<?php echo $form->textField($model,'initials',array('size'=>5,'maxlength'=>5)); ?>
		<?php echo $form->error($model,'initials'); ?>
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

	<div class="row checkbox">
		<?php echo $form->checkBox($model,'is_banned'); ?>
		<?php echo $form->labelEx($model,'is_banned', array('style'=>'display:inline;')); ?>
		<?php echo $form->error($model,'is_banned'); ?>
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