<?php
	$this->pageTitle='Εγγραφή';
?>

<h1>Εγγραφή</h1>

<p>Παρακαλούμε συμπληρώστε το email σας και έναν επιθυμητό κωδικό πρόσβασης.</p>

<div class="form">
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'register-form',
	'enableAjaxValidation'=>false,
)); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'email'); ?>
		<?php echo $form->textField($model,'email',array('size'=>40)); ?>
		<?php echo $form->error($model,'email'); ?>
	</div>
	
	<p class="hint">Ενα ενημερωτικό email θα σταλεί σε αυτή τη διεύθυνση</p>	

	<div class="row">
		<?php echo $form->labelEx($model,'password'); ?>
		<?php echo $form->passwordField($model,'password'); ?>
		<?php echo $form->error($model,'password'); ?>
	</div>

	<?php if(CCaptcha::checkRequirements()): ?>
	<div class="row">
		<?php echo $form->labelEx($model,'verifyCode'); ?>
		<?php $this->widget('CCaptcha', array('imageOptions'=>array('style'=>'vertical-align: middle;'),'buttonLabel'=>'Αλλαγή', 'buttonType'=>'button')); ?><br />
		<?php echo $form->textField($model,'verifyCode'); ?>
		<?php echo $form->error($model,'verifyCode'); ?>
		<div class="hint">Παρακαλούμε συμπληρώστε τα γράμματα όπως φαίνονται στην εικόνα. Κεφαλαία ή μικρά, δεν έχει σημασία.</div>
	</div>
	<?php endif; ?>


	<p>&nbsp;</p>	

	<div class="row checkbox">
		<?php echo $form->checkBox($model,'accept_terms'); ?>
		<?php echo $form->label($model,'accept_terms',array('label'=>'Αποδέχομαι τους ' . CHtml::link('Ορους Χρήσης', array('/page/view', 'url_keyword'=>'terms'), array('style'=>'text-decoration:underline;color:inherit')) . ' του παρόντος ιστότοπου.')); ?>
		<?php echo $form->error($model,'accept_terms'); ?>
	</div>

	<div class="row submit">
		<?php echo CHtml::submitButton('Εγγραφή'); ?>
	</div>

<?php $this->endWidget(); ?>
</div><!-- form -->

