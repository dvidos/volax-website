<?php

/**
 * Holds info for facebook open graph protocol
 */
class OpenGraph extends CApplicationComponent
{
	public $locale = '';
	public $type = '';
	public $title = '';
	public $description = '';
	public $url = '';
	public $image = '';
	
	public function init()
	{
	}
	
	public function render()
	{
		$tags = array();
		
		if ($this->locale != '')
			$tags[] = CHtml::tag('meta', array('property'=>'og:locale', 'content'=>$this->locale), false, true);
		
		if ($this->type != '')
			$tags[] = CHtml::tag('meta', array('property'=>'og:type', 'content'=>$this->type), false, true);
		
		if ($this->title != '')
			$tags[] = CHtml::tag('meta', array('property'=>'og:title', 'content'=>$this->title), false, true);
		
		if ($this->description != '')
			$tags[] = CHtml::tag('meta', array('property'=>'og:description', 'content'=>$this->description), false, true);
		
		if ($this->url != '')
			$tags[] = CHtml::tag('meta', array('property'=>'og:url', 'content'=>$this->url), false, true);
		
		if ($this->image != '')
			$tags[] = CHtml::tag('meta', array('property'=>'og:image', 'content'=>$this->image), false, true);
		
		echo implode("\r\n", $tags) . "\r\n";
	}
}


?>