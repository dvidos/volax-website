<?php
	$this->pageTitle='Απώλεια κωδικού πρόσβασης';
?>

<h1>Απώλεια κωδικού πρόσβασης</h1>

<p>Συμπληρώστε το email σας και θα σας αποστείλουμε ένα μήνυμα με οδηγίες πως να συνεχίσετε.</p>

<div class="form">
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'forgot-password-form',
	'enableAjaxValidation'=>false,
)); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'email'); ?>
		<?php echo $form->textField($model,'email',array('size'=>40)); ?>
		<?php echo $form->error($model,'email'); ?>
	</div>

	<?php if(CCaptcha::checkRequirements()): ?>
	<div class="row">
		<?php echo $form->labelEx($model,'verifyCode'); ?>
		<?php $this->widget('CCaptcha', array('imageOptions'=>array('style'=>'vertical-align: middle;'),'buttonLabel'=>'Αλλαγή', 'buttonType'=>'button')); ?><br />
		<?php echo $form->textField($model,'verifyCode'); ?>
		<?php echo $form->error($model,'verifyCode'); ?>
		<div class="hint">Συμπληρώστε τα γράμματα όπως φαίνονται στην εικόνα. Κεφαλαία ή μικρά, δεν έχει σημασία.</div>
	</div>
	<?php endif; ?>


	<p>&nbsp;</p>	

	<div class="row submit">
		<?php echo CHtml::submitButton('Αποστολή'); ?>
	</div>

<?php $this->endWidget(); ?>
</div><!-- form -->

