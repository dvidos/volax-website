<div class="form">
	<script src="<?php echo Yii::app()->baseUrl; ?>/assets/ckeditor/ckeditor.js"></script>

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
		<?php echo $form->labelEx($model,'content',array('label'=>'Κείμενο <span id="content-size-indicator"></span>')); ?>
		<?php echo $form->textArea($model,'content', array('class'=>'content-area', 'style'=>'width:100%;')); ?>
		<?php echo $form->error($model,'content'); ?>
		<?php $this->widget('application.components.CkEditorWidget', array('varName' => 'Post_content')); ?>
	</div>
	<p class="hint">
		Βάζουμε <b>[more]</b> όπου θέλουμε να εμφανίζεται το <b>read more...</b>
	</p>
	
	<?php echo CHtml::submitButton($model->isNewRecord ? 'Δημιουργία' : 'Αποθήκευση', array('name'=>'saveAndStay')); ?>
	&nbsp;
	<?php // echo CHtml::submitButton($model->isNewRecord ? 'Δημιουργία κι επιστροφή' : 'Αποθήκευση κι επιστροφή', array('name'=>'saveAndReturn')); ?>
	
</td><td width="33%" style="vertical-align: top;">
	
	<div class="row">
		<?php echo $form->labelEx($model,'category_id'); ?>
		<?php echo $form->dropDownList($model,'category_id',Category::dropDownListItems()); ?>
		<?php echo $form->error($model,'category_id'); ?>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($model,'status'); ?>
		<?php echo $form->dropDownList($model,'status',Status::items('PostStatus')); ?>
		<?php echo $form->error($model,'status'); ?>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($model,'desired_width'); ?>
		<?php echo $form->dropDownList($model,'desired_width',Post::getDesiredWidthOptions()); ?>
		<?php echo $form->error($model,'desired_width'); ?>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($model,'layout'); ?>
		<?php echo $form->dropDownList($model,'layout',Post::getLayoutOptions()); ?>
		<?php echo $form->error($model,'layout'); ?>
	</div>
	
	<div class="row">
		<?php echo $form->checkBox($model, 'in_home_page'); ?>
		<?php echo $form->labelEx($model,'in_home_page', array('style'=>'display:inline; margin-left:.5em;')); ?>
		<?php echo $form->error($model,'in_home_page'); ?>
	</div>
	
	<div class="row">
		<?php echo $form->checkBox($model, 'allow_comments'); ?>
		<?php echo $form->labelEx($model,'allow_comments', array('style'=>'display:inline; margin-left:.5em;')); ?>
		<?php echo $form->error($model,'allow_comments'); ?>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($model,'masthead'); ?>
		<?php echo $form->textField($model,'masthead', array('style'=>'width:100%;')); ?>
		<?php echo $form->error($model,'masthead'); ?>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($model,'tags'); ?>
		<?php $this->widget('CAutoComplete', array(
			'model'=>$model,
			'attribute'=>'tags',
			'url'=>array('suggestTags'),
			'multiple'=>true,
			'max'=>50,
			'htmlOptions'=>array('size'=>40, 'style'=>'width:100%;'),
		)); ?>
		<p class="hint">Χρησιμοποιήστε ελληνικά, χωρίστε τα με κόμμα. Tags σε παρένθεση δεν εμφανίζονται δημόσια.</p>
		<?php echo $form->error($model,'tags'); ?>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($model,'author_id'); ?>
		<?php echo $form->dropDownList($model,'author_id', CHtml::listData(User::model()->findAll(), 'id', 'fullname')); ?>
		<?php echo $form->error($model,'author_id'); ?>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($model,'editable_create_time', array('label'=>'Δημιουργία (ΗΗ-ΜΜ-ΕΕΕΕ ΩΩ:ΛΛ)')); ?>
		<?php echo $form->textField($model,'editable_create_time'); ?>
		<?php echo $form->error($model,'editable_create_time'); ?>
	</div>
	
	<div class="row">
		Τελ. ενημέρωση στις <?php echo $model->update_time == 0 ? '(όχι ακόμα)' : date('d-m-y H:i', $model->update_time); ?>
	</div>
	
</td></tr>
</table>



<?php $this->endWidget(); ?>

</div><!-- form -->