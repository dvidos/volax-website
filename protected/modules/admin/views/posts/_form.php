<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'user-form',
	'enableAjaxValidation'=>false,
)); ?>
<?php echo $form->errorSummary($model); ?>

<table style="border:1px solid #ddd; background-color: #eee; padding: .5em 1em;">
<tr><td width="66%" style="vertical-align: top;">

	<div class="row">
		<?php echo $form->labelEx($model,'title'); ?>
		<?php echo $form->textField($model,'title',array('style'=>'width:100%;','maxlength'=>100)); ?>
		<?php echo $form->error($model,'title'); ?>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($model,'content'); ?>
		<?php echo $form->textArea($model,'content', array('style'=>'width:100%; min-height:450px;')); ?>
		<?php echo $form->error($model,'content'); ?>
	</div>
	
	<p class="hint">
		Χρησιμοποιούμε σύνταξη <a href="http://daringfireball.net/projects/markdown/syntax">markdown</a>.<br />
		Βάζουμε <b>[more]</b> όπου θέλουμε να εμφανίζεται το <b>read more...</b>
	</p>
	
</td><td width="33%" style="vertical-align: top;">
	
	<div class="row">
		<?php echo $form->labelEx($model,'category_id'); ?>
		<?php echo $form->dropDownList($model,'category_id',Category::dropDownListItems()); ?>
		<?php echo $form->error($model,'category_id'); ?>
	</div>
	
	<table width="100%" border="1">
	<tr><td>
		<div class="row">
			<?php echo $form->labelEx($model,'status'); ?>
			<?php echo $form->dropDownList($model,'status',Status::items('PostStatus')); ?>
			<?php echo $form->error($model,'status'); ?>
		</div>
		
		<div class="row">
			<?php echo $form->labelEx($model,'layout'); ?>
			<?php echo $form->dropDownList($model,'layout',Post::getLayoutOptions()); ?>
			<?php echo $form->error($model,'layout'); ?>
		</div>
	</td><td style="text-align: center;">
		<div class="row">
			<?php echo $form->labelEx($model,'in_home_page'); ?>
			<?php echo $form->checkBox($model, 'in_home_page'); ?>
			<?php echo $form->error($model,'in_home_page'); ?>
		</div>
	</td><td style="text-align: center;">
		<div class="row">
			<?php echo $form->labelEx($model,'allow_comments'); ?>
			<?php echo $form->checkBox($model, 'allow_comments'); ?>
			<?php echo $form->error($model,'allow_comments'); ?>
		</div>
	</td></tr>
	</table>
	
	
	
	<div class="row">
		<?php echo $form->labelEx($model,'prologue'); ?>
		<?php echo $form->textArea($model,'prologue', array('style'=>'width:100%; min-height:80px;')); ?>
		<?php echo $form->error($model,'prologue'); ?>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($model,'masthead'); ?>
		<?php echo $form->textArea($model,'masthead', array('style'=>'width:100%; min-height:80px;')); ?>
		<?php echo $form->error($model,'masthead'); ?>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($model,'image_filename'); ?>
		<?php /* echo $form->textField($model,'image_filename',array('size'=>100,'maxlength'=>100)); */ ?>
		<?php echo $this->widget('application.components.elFinder.ServerFileInput', array(
			'model' => $model,
			'attribute' => 'image_filename',
			'connectorRoute' => '/admin/elfinder/connector',
			'htmlOptions'=>array('size'=>20),
		)); ?>
		<?php echo $form->error($model,'image_filename'); ?>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($model,'image2_filename'); ?>
		<?php /* echo $form->textField($model,'image2_filename',array('size'=>100,'maxlength'=>100)); */ ?>
		<?php echo $this->widget('application.components.elFinder.ServerFileInput', array(
			'model' => $model,
			'attribute' => 'image2_filename',
			'connectorRoute' => '/admin/elfinder/connector',
			'htmlOptions'=>array('size'=>20),
		)); ?>
		<?php echo $form->error($model,'image2_filename'); ?>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($model,'tags'); ?>
		<?php $this->widget('CAutoComplete', array(
			'model'=>$model,
			'attribute'=>'tags',
			'url'=>array('suggestTags'),
			'multiple'=>true,
			'htmlOptions'=>array('size'=>40),
		)); ?>
		<p class="hint">Προτιμήστε ελληνικά tags, χωρίστε τα με κόμμα.</p>
		<?php echo $form->error($model,'tags'); ?>
	</div>
	
	
	
</td></tr>
<tr><td colspan="2">

	<?php echo CHtml::submitButton($model->isNewRecord ? 'Δημιουργία' : 'Αποθήκευση'); ?>

</td></tr>
</table>



<?php $this->endWidget(); ?>

</div><!-- form -->