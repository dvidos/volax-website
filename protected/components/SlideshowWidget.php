<?php

class SlideshowWidget extends CWidget
{
	public $directory = 'uploads/';
	public $width = 0;
	public $height = 0;
	public $htmlOptions = array();

	public function init()
	{
		parent::init();
	}
	
	public function run()
	{
		// find images.
		$dir = $this->directory;
		if (substr($dir, -1) != '/')
			$dir .= '/';
		$dir .= '*';
		$images = glob($dir);
		
		// create the div
		$this->htmlOptions['id'] = $this->id;
		
		$style='';
		if ($this->width > 0)
			$style .= 'width:' . $this->width . 'px;';
		if ($this->height > 0)
			$style .= 'height:' . $this->height . 'px;';
		
		$html = '';
		$html .= '<div id="'.$this->id.'-img0" style="' . $style . '">';
		$html .= CHtml::image($images...
		$html .= '<div id="'.$this->id.'-img0" style="' . $style . 'display:none;">';
		
		echo CHtml::tag('div', $this->htmlOptions, $html);
		
		
		// now the scripts.
		Yii::app()->clientScript->registerCoreScript('jquery');
		$script = 'setInterval(function(){
			alert(1);
		}, 2000);';
		Yii::app()->clientScript->registerScript($this->id, $script, CClientScript::POS_READY);
	}
}