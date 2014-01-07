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
		<?php echo $form->labelEx($model,'parent_id'); ?>
		<?php echo $form->dropDownList($model,'parent_id',Category::dropDownListItems()); ?>
		<?php echo $form->error($model,'parent_id'); ?>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($model,'content'); ?>
		<?php echo $form->textArea($model,'content', array('style'=>'width:100%;')); ?>
		<?php echo $form->error($model,'content'); ?>
	</div>
	<script>
		CKEDITOR.replace('Category_content', {
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

	<!-- <p class="hint">Using <a href="http://daringfireball.net/projects/markdown/syntax">markdown</a> syntax.</p> -->
	<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>

	
</td><td width="33%" style="vertical-align: top;">
	
	<table class="skinny">
		<tr><td width="66%">
			<div class="row">
				<?php echo $form->labelEx($model,'status'); ?>
				<?php echo $form->dropDownList($model,'status',Status::items('CategoryStatus')); ?>
				<?php echo $form->error($model,'status'); ?>
			</div>
		</td><td width="33%">
			<div class="row">
				<?php echo $form->labelEx($model,'view_order'); ?>
				<?php echo $form->textField($model,'view_order',array('size'=>5,'maxlength'=>5)); ?>
				<?php echo $form->error($model,'view_order'); ?>
			</div>
		</td></tr>
	</table>
	
	<div class="row">
		<?php echo $form->labelEx($model,'layout'); ?>
		<?php echo $form->dropDownList($model,'layout',Category::getLayoutOptions()); ?>
		<?php echo $form->error($model,'layout'); ?>
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
			'connectorRoute' => '/admin/elfinder/connector',
			'textFieldSize'=>30,
		)); ?>
		<?php echo $form->error($model,'image_filename'); ?>
	</div>
	
</td></tr>
</table>

<?php $this->endWidget(); ?>

</div><!-- form -->