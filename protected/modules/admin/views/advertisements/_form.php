<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'user-form',
	'enableAjaxValidation'=>false,
)); ?>
<?php echo $form->errorSummary($model); ?>

<table style="border:1px solid #ddd; background-color: #eee; padding: .5em 1em;"><tr><td>

	<div class="row">
		<?php echo $form->labelEx($model,'customer', array('label'=>'Επωνυμία διαφημιζόμενου')); ?>
		<?php echo $form->textField($model,'customer', array('size'=>100)); ?>
		<?php echo $form->error($model,'customer'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'title', array('label'=>'Τίτλος καμπάνιας')); ?>
		<?php echo $form->textField($model,'title', array('size'=>100)); ?>
		<?php echo $form->error($model,'title'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'image_filename', array('label'=>'Εικόνα (λογικά στο uploads/ads, διαστάσεων περίπου 700 x 100)')); ?>
		<?php /* echo $form->textField($model,'image_filename',array('size'=>100,'maxlength'=>100)); */ ?>
		<?php echo $this->widget('application.components.elFinder.ServerFileInput', array(
			'model' => $model,
			'attribute' => 'image_filename',
			'connectorRoute' => '/admin/elfinder/connector',
			'htmlOptions'=>array('size'=>40),
		)); ?>
		<?php echo $form->error($model,'image_filename'); ?>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($model,'image_title', array('label'=>'Κείμενο tooltip')); ?>
		<?php echo $form->textField($model,'image_title', array('size'=>100)); ?>
		<?php echo $form->error($model,'image_title'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'target_url', array('label'=>'URL Κατάληξης')); ?>
		<?php echo $form->textField($model,'target_url', array('size'=>100)); ?>
		<?php echo $form->error($model,'target_url'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'from_time', array('label'=>'Εμφάνιση από ημ/νία (HH-MM-EEEE)')); ?>
		<?php echo $form->textField($model,'from_time', array('size'=>14)); ?>
		<?php echo $form->error($model,'from_time'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'to_time', array('label'=>'Εμφάνιση έως ημ/νία (HH-MM-EEEE)')); ?>
		<?php echo $form->textField($model,'to_time', array('size'=>14)); ?>
		<?php echo $form->error($model,'to_time'); ?>
	</div>

	<div class="row">
		<?php echo $form->checkBox($model,'is_active'); ?>
		<?php echo $form->labelEx($model,'is_active', array('style'=>'display:inline;')); ?>
		<?php echo $form->error($model,'is_active'); ?>
	</div>

	<div class="row">
		<p>
			Εμφανίστηκε <?php echo $model->times_shown; ?> φορές, 
			έγιναν <?php echo $model->times_clicked; ?> click
			<?php
				if ($model->times_shown == 0)
					$clickthrough = 0;
				else
					$clickthrough = round(($model->times_clicked / $model->times_shown) * 100, 1);
				echo '(' . $clickthrough . '%)';
			?>
		</p>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'notes'); ?>
		<?php echo $form->textArea($model,'notes', array('style'=>'width:100%; min-height:150px;')); ?>
		<?php echo $form->error($model,'notes'); ?>
	</div>
	
	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

</td></tr></table>


<?php $this->endWidget(); ?>

</div><!-- form -->