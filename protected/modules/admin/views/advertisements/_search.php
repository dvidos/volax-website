<p>
You may optionally enter a comparison operator (<b>&lt;</b>, <b>&lt;=</b>, <b>&gt;</b>, <b>&gt;=</b>, <b>&lt;&gt;</b>
or <b>=</b>) at the beginning of each of your search values to specify how the comparison should be done.
</p>

<div class="wide form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

	<div class="row">
		<?php echo $form->label($model,'title'); ?>
		<?php echo $form->textField($model,'title'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'customer'); ?>
		<?php echo $form->textField($model,'customer'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'image_filename'); ?>
		<?php echo $form->textField($model,'image_filename'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'target_url'); ?>
		<?php echo $form->textField($model,'target_url'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'is_active'); ?>
		<?php echo $form->textField($model,'is_active'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'from_time'); ?>
		<?php echo $form->textField($model,'from_time'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'to_time'); ?>
		<?php echo $form->textField($model,'to_time'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'times_shown'); ?>
		<?php echo $form->textField($model,'times_shown'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'times_clicked'); ?>
		<?php echo $form->textField($model,'times_clicked'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Search'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->