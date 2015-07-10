<?php
	$this->pageTitle='Είσοδος';
?>

<h1>Είσοδος</h1>

<p>Παρακαλούμε συμπληρώστε το όνομα χρήστη και τον κωδικό πρόσβασης.</p>

<div class="form">
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'login-form',
	'enableAjaxValidation'=>false,
)); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'email'); ?>
		<?php echo $form->textField($model,'email',array('size'=>40)); ?>
		<?php echo $form->error($model,'email'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'password'); ?>
		<?php echo $form->passwordField($model,'password'); ?>
		<?php echo $form->error($model,'password'); ?>
		<div class="hint"><?php echo CHtml::link('Εχω ξεχάσει τον κωδικό πρόσβασής μου', array('/user/forgotPassword'), array('style'=>'text-decoration:underline;color:inherit;')); ?></div>
	</div>

	<p>&nbsp;</p>
	
	<div class="row checkbox">
		<?php echo $form->checkBox($model,'rememberMe'); ?>
		<?php echo $form->label($model,'rememberMe',array('label'=>'Αυτόματη σύνδεση την επόμενη φορά')); ?>
		<?php echo $form->error($model,'rememberMe'); ?>
	</div>

	<div class="row submit">
		<?php echo CHtml::submitButton('Είσοδος'); ?>
	</div>

<?php $this->endWidget(); ?>
</div><!-- form -->

