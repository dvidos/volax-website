<?php
$this->pageTitle='Επικοινωνία';
$this->breadcrumbs=array(
	'Επικοινωνία',
);
?>

<h1>Επικοινωνία</h1>

<?php if(Yii::app()->user->hasFlash('contact')): ?>

<div class="flash-success">
	<?php echo Yii::app()->user->getFlash('contact'); ?>
</div>

<?php else: ?>

<p>Αν έχετε κάποιο μήνυμα ή ερώτηση, παρακαλούμε συμπληρώστε την παρακάτω φόρμα. Ευχαριστούμε.</p>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm'); ?>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'name'); ?>
		<?php echo $form->textField($model,'name'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'email'); ?>
		<?php echo $form->textField($model,'email'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'subject'); ?>
		<?php echo $form->textField($model,'subject',array('size'=>60,'maxlength'=>128)); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'body'); ?>
		<?php echo $form->textArea($model,'body',array('rows'=>6, 'cols'=>50)); ?>
	</div>

	<?php if(CCaptcha::checkRequirements()): ?>
	<div class="row">
		<?php echo $form->labelEx($model,'verifyCode'); ?>
		<div>
		<?php $this->widget('CCaptcha'); ?>
		<?php echo $form->textField($model,'verifyCode'); ?>
		</div>
		<div class="hint">Παρακαλούμε συμπληρώστε τα γράμματα όπως φαίνονται στην εικόνα. Κεφαλαία ή μικρά, δεν έχει σημασία.</div>
	</div>
	<?php endif; ?>

	<div class="row submit">
		<?php echo CHtml::submitButton('Αποστολή'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->

<?php endif; ?>