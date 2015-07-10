<?php
	$this->pageTitle='Ο Λογαριασμός μου';
?>

<h1>Ο λογαριασμός μου</h1>

<div class="form">
<?php $form = $this->beginWidget('CActiveForm', array(
	'id'=>'my-account-form',
	'enableAjaxValidation'=>false,
)); ?>

	<h3>Κύρια στοιχεία</h3>
	
	<div class="row">
		<?php echo $form->labelEx($user,'email'); ?>
		<?php echo $form->textField($user,'email', array('name'=>'eml', 'size'=>40, 'readonly'=>'readonly', 'disabled'=>'disabled')); ?>
		<?php echo $form->error($user,'email'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($user,'username'); ?>
		<?php echo $form->textField($user,'username'); ?>
		<?php echo $form->error($user,'username'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($user,'fullname'); ?>
		<?php echo $form->textField($user,'fullname', array('size'=>50)); ?>
		<?php echo $form->error($user,'fullname'); ?>
	</div>

	<h3>Αλλαγή κωδικού πρόσβασης</h3>
	
	<?php 
		if (Yii::app()->user->hasFlash('changePassword'))
		{
			echo CHtml::tag('div', array('class'=>'flash-notice'), Yii::app()->user->getFlash('changePassword'));
			$js = '$(document).ready(function(){ setTimeout(function() { $(".flash-notice").slideUp(); }, 4000); });';
			echo CHtml::tag('script', array(), $js);
		}
		if (Yii::app()->user->hasFlash('passwordChanged'))
		{
			echo CHtml::tag('div', array('class'=>'flash-success'), Yii::app()->user->getFlash('passwordChanged'));
			$js = '$(document).ready(function(){ setTimeout(function() { $(".flash-success").slideUp(); }, 4000); });';
			echo CHtml::tag('script', array(), $js);
		}
	?>
	<div class="row">
		<?php echo $form->labelEx($user,'password1'); ?>
		<?php echo $form->passwordField($user,'password1'); ?>
		<?php echo $form->error($user,'password1'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($user,'password2'); ?>
		<?php echo $form->passwordField($user,'password2'); ?>
		<?php echo $form->error($user,'password2'); ?>
	</div>

	<h3>Προτιμήσεις</h3>
	
	<div class="row checkbox">
		<?php echo $form->checkBox($user,'want_newsletter'); ?>
		<?php echo $form->labelEx($user,'want_newsletter', array('label'=>'Επιθυμώ να λαμβάνω νέα μέσω email')); ?>
		<?php echo $form->error($user,'want_newsletter'); ?>
	</div>
	
	<p>&nbsp;</p>
	
	<div class="row submit">
		<?php echo CHtml::submitButton('Αποθήκευση'); ?>
		&nbsp;
		(ή <?php echo CHtml::link('κατάργηση', array('/user/terminate'), array('style'=>'color: inherit; text-decoration: underline;')); ?> του λογαριασμού)
	</div>

<?php $this->endWidget(); ?>
</div><!-- form -->

