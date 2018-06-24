<h1>Μαζική μετονομασία tags</h1>

<p><?php
	echo CHtml::link('Λίστα', array('index'), array('class'=>'button')); 
	echo ' ';
	echo CHtml::link('Μαζική μετονομασία', array('rename'), array('class'=>'button')); 
?></p>

<?php if (!$model->has_searched): ?>




<div class="form">
<p>Δώστε το tag που θέλετε να αλλάξετε για να βρείτε τις αναρτήσεις που το περιέχουν</p>

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'rename-form',
	'enableAjaxValidation'=>false,
)); ?>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">

		<?php echo $form->labelEx($model,'initialTag'); ?>
		<?php $this->widget('CAutoComplete', array(
			'model'=>$model,
			'attribute'=>'initialTag',
			'url'=>array('suggestTags'),
			'multiple'=>false,
			'max'=>50,
			'htmlOptions'=>array('size'=>40),
		)); ?>
		<?php echo $form->error($model,'initialTag'); ?>
	</div>

	<?php echo CHtml::submitButton('Εύρεση αναρτήσεων', array('name'=>'search')); ?>

<?php $this->endWidget(); ?>
</div><!-- form -->



<?php else: // model has searched ?>



<h2>Αποτελέσματα</h2>
<p>Βρέθηκαν <?php echo count($model->posts); ?> αναρτήσεις με το tag <?php echo CHtml::encode($model->initialTag); ?></p> 
<p><?php
	foreach ($model->posts as $post)
	{
		echo CHtml::link(CHtml::encode($post->title), array('/admin/posts/update', 'id'=>$post->id));
		echo ' &nbsp; <span style="color:#aaa;">(' . CHtml::encode($post->tags) . ')</span>';
		echo '<br />';
	}
?></p>


<div class="form">
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'rename-form',
	'enableAjaxValidation'=>false,
)); ?>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'initialTag'); ?>
		<?php $this->widget('CAutoComplete', array(
			'model'=>$model,
			'attribute'=>'initialTag',
			'url'=>array('suggestTags'),
			'multiple'=>false,
			'max'=>50,
			'htmlOptions'=>array('size'=>40),
		)); ?>
		<?php echo $form->error($model,'initialTag'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'targetTag'); ?>
		<?php $this->widget('CAutoComplete', array(
			'model'=>$model,
			'attribute'=>'targetTag',
			'url'=>array('suggestTags'),
			'multiple'=>false,
			'max'=>50,
			'htmlOptions'=>array('size'=>40),
		)); ?>
		<?php echo $form->error($model,'targetTag'); ?>
	</div>

	<div class="row">
		<?php echo $form->checkBox($model,'danger'); ?>
		<?php echo $form->labelEx($model,'danger', array('style'=>'display:inline')); ?>
		<?php echo $form->error($model,'danger'); ?>

	</div>

	<?php echo CHtml::submitButton('Μετονομασία', array('name'=>'rename')); ?>
<?php $this->endWidget(); ?>
</div><!-- form -->


<?php endif; ?>
