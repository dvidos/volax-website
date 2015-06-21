<?php
	
	$ckeditor_name = @$_REQUEST['CKEditor'];
	$ckeditor_func = @$_REQUEST['CKEditorFuncNum'];
	
	$this->widget('application.components.elFinder.ElFinderWidget', array(
		'connectorRoute' => '/author/elfinder/connector',
		// elFinder client settings, see https://github.com/Studio-42/elFinder/wiki/Client-configuration-options
		'settings'=>array(
			'editorCallback'=>'js: function(url) { window.opener.CKEDITOR.tools.callFunction(' . $ckeditor_func . ', url); window.close(); }',
			'closeOnEditorCallback'=>true,
		),
	));

	
