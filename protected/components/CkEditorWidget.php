<?php

class CkEditorWidget extends CWidget
{
	public $varName = 'content';
	public $imagesBrowseUrl = null;
	public $filesBrowseUrl = null;
	public $height = 400; // i guess in pixels
	
	public function init()
	{
		parent::init();
		
		// files browsing depends on whether we are under admin or author module
		if ($this->imagesBrowseUrl == null)
			$this->imagesBrowseUrl = Yii::app()->createUrl('/' . Yii::app()->controller->module->name . '/files/browse');

		if ($this->filesBrowseUrl == null)
			$this->filesBrowseUrl = Yii::app()->createUrl('/' . Yii::app()->controller->module->name . '/files/browse');
		
	}
	
	public function run()
	{
		// see http://docs.cksource.com/ckeditor_api/symbols/CKEDITOR.config.html
		$editor_config = array(
			'height'=>$this->height, // i guess in pixels
			'language'=>'el', // for greek button labels and dialogs.
			'entities_greek'=>false, // for not converting greek letters to entities
			'entities_latin'=>false, // for not converting latin1 letters to entities.
			'toolbar'=>'volax1',
			'toolbar_volax1'=>array(
				array(
					'Bold','Italic','Strike','Subscript','Superscript', '-',
					'NumberedList','BulletedList','-','Blockquote', '-',
					'Link','Unlink','Anchor','Image','Table','SpecialChar','HorizontalRule','Iframe'
				),
				array('Format','Styles','-', 'JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock','-','TextColor','BGColor'),
				array('Preview', 'ShowBlocks', 'RemoveFormat', 'Source'),
				/*
				array('name'=>'clipboard', 'items'=>array('Cut','Copy','Paste','PasteText','PasteFromWord','-','Undo','Redo')),
				array('name'=>'editing', 'items'=>array('Find','Replace','-','SelectAll','-','SpellChecker', 'Scayt')),
				array('name'=>'forms', 'items'=>array('Form', 'Checkbox', 'Radio', 'TextField', 'Textarea', 'Select', 'Button', 'ImageButton', 'HiddenField')),
				array('name'=>'basicstyles', 'items'=>array('Bold','Italic','Underline','Strike','Subscript','Superscript','-','RemoveFormat')),
				array('name'=>'paragraph', 'items'=>array('NumberedList','BulletedList','-','Outdent','Indent','-','Blockquote','CreateDiv', '-','JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock','-','BidiLtr','BidiRtl')),
				array('name'=>'links', 'items'=>array('Link','Unlink','Anchor')),
				array('name'=>'insert', 'items'=>array('Image','Flash','Table','HorizontalRule','Smiley','SpecialChar','PageBreak','Iframe')),
				array('name'=>'styles', 'items'=>array('Styles','Format','Font','FontSize')),
				array('name'=>'colors', 'items'=>array('TextColor','BGColor')),
				array('name'=>'tools', 'items'=>array('Maximize', 'ShowBlocks','-','About'))
				array('name'=>'document', 'items'=>array('Source','-','Save','NewPage','DocProps','Preview','Print','-','Templates')),
				*/
			),
			'stylesSet'=>array(
				array('name'=>'None', 'element'=>'p', 'attributes'=>array('class'=>'')),
				array('name'=>'Alt Color', 'element'=>'p', 'attributes'=>array('class'=>'x-style-alt-color')),
				array('name'=>'Footnotes', 'element'=>'p', 'attributes'=>array('class'=>'x-style-footnotes')),
				array('name'=>'Reference', 'element'=>'span', 'attributes'=>array('class'=>'x-style-reference')),
				array('name'=>'None', 'element'=>'span', 'attributes'=>array('class'=>'')),
				array('name'=>'Low Text', 'element'=>'p', 'attributes'=>array('class'=>'x-style-low-text'))
			),
			'contentsCss'=> Yii::app()->baseUrl . '/assets/css/stylistic.css',
			'filebrowserImageBrowseUrl'=> $this->imagesBrowseUrl,
			'filebrowserBrowseUrl'=> $this->filesBrowseUrl,
		);
			
		$js = 'var editor = CKEDITOR.replace("' . $this->varName . '", ' . CJSON::encode($editor_config) . ');';
		$html = CHtml::tag('script', array(), $js);

		echo $html;
	}
}