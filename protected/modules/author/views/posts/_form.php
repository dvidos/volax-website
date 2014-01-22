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
		<?php echo $form->labelEx($model,'content'); ?>
		<?php echo $form->textArea($model,'content', array('style'=>'width:100%; min-height:450px;')); ?>
		<?php echo $form->error($model,'content'); ?>
	</div>
	<script>
		CKEDITOR.replace('Post_content', {
			// see http://docs.cksource.com/ckeditor_api/symbols/CKEDITOR.config.html
			height: 400,
			language: 'el', // for greek button labels and dialogs.
			entities_greek: false, // for not converting greek letters to entities
			entities_latin: false, // for not converting latin1 letters to entities.
			toolbar: 'MedCms',
			toolbar_MedCms: [
				[
					'Bold','Italic','Strike','Subscript','Superscript', '-',
					'NumberedList','BulletedList','-','Blockquote', '-','JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock','-',
					'Link','Unlink','Image','Table','SpecialChar'
				],
				[ 'Format','Font','FontSize','-', 'TextColor','BGColor' ],
				[ 'Preview', 'RemoveFormat', 'Source' ],
		/*
				{ name: 'clipboard', items : [ 'Cut','Copy','Paste','PasteText','PasteFromWord','-','Undo','Redo' ] },
				{ name: 'editing', items : [ 'Find','Replace','-','SelectAll','-','SpellChecker', 'Scayt' ] },
				{ name: 'forms', items : [ 'Form', 'Checkbox', 'Radio', 'TextField', 'Textarea', 'Select', 'Button', 'ImageButton', 'HiddenField' ] },
				{ name: 'basicstyles', items : [ 'Bold','Italic','Underline','Strike','Subscript','Superscript','-','RemoveFormat' ] },
				{ name: 'paragraph', items : [ 'NumberedList','BulletedList','-','Outdent','Indent','-','Blockquote','CreateDiv', '-','JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock','-','BidiLtr','BidiRtl' ] },
				{ name: 'links', items : [ 'Link','Unlink','Anchor' ] },
				{ name: 'insert', items : [ 'Image','Flash','Table','HorizontalRule','Smiley','SpecialChar','PageBreak','Iframe' ] },
				{ name: 'styles', items : [ 'Styles','Format','Font','FontSize' ] },
				{ name: 'colors', items : [ 'TextColor','BGColor' ] },
				{ name: 'tools', items : [ 'Maximize', 'ShowBlocks','-','About' ] }
				{ name: 'document', items : [ 'Source','-','Save','NewPage','DocProps','Preview','Print','-','Templates' ] },
		*/
			],
			
			// from getSimple
			filebrowserBrowseUrl : 'filebrowser.php?type=all',
			filebrowserImageBrowseUrl : 'filebrowser.php?type=images',
			filebrowserWindowWidth : '730',
			filebrowserWindowHeight : '500'
		});
	</script>
	
	<p class="hint">
		Βάζουμε <b>[more]</b> όπου θέλουμε να εμφανίζεται το <b>read more...</b>
	</p>
	
	<?php echo CHtml::submitButton($model->isNewRecord ? 'Δημιουργία' : 'Αποθήκευση', array('name'=>'saveAndStay')); ?>
	&nbsp;
	<?php echo CHtml::submitButton($model->isNewRecord ? 'Δημιουργία κι επιστροφή' : 'Αποθήκευση κι επιστροφή', array('name'=>'saveAndReturn')); ?>
	
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
			'connectorRoute' => '/author/elfinder/connector',
			'textFieldSize'=>30,
		)); ?>
		<?php echo $form->error($model,'image_filename'); ?>
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
	
	<div class="row">
		Από τον χρήστη <b><?php echo $model->author == null ? '-' : CHtml::encode($model->author->username); ?></b><br />
		Δημιουργία <b><?php echo $model->create_time == 0 ? '-' : date('d-m-y H:i', $model->create_time); ?></b><br />
		Τελ. ενημέρωση <b><?php echo $model->update_time == 0 ? '-' : date('d-m-y H:i', $model->update_time); ?></b><br />
		
	</div>
	
</td></tr>
</table>



<?php $this->endWidget(); ?>

</div><!-- form -->