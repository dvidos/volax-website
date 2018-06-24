<?php
	$this->pageTitle = CHtml::encode($model->title); 
?>

<h1><?php echo $this->pageTitle; ?></h1>

<?php echo $this->renderPartial('_tabs', array('model'=>$model)); ?>
<div class="tabs-page">


	<div class="form">
		<?php $form=$this->beginWidget('CActiveForm', array(
			'id'=>'post-form',
			'enableAjaxValidation'=>false,
		)); ?>
		<?php echo $form->errorSummary($model); ?>
			<div class="row">
				<?php echo $form->labelEx($model,'discussion'); ?>
					<?php echo $form->textArea($model,'discussion', array('class'=>'discussion-area', 'style'=>'width:100%; min-height: 200px; font-family: inherit;')); ?>
				<?php echo $form->error($model,'discussion'); ?>
			</div>
			<?php echo CHtml::submitButton('Αποθήκευση'); ?>
		<?php $this->endWidget(); ?>
	</div><!-- form -->



	
	
</div>


