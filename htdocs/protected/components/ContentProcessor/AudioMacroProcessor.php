<?php

class AudioMacroProcessor extends BaseMacroProcessor
{
	function __construct()
	{
		$this->shortcode = 'audio';
	}
	
	function getHtml($attributes, $innerText)
	{
		$src = Yii::app()->baseUrl . '/' . (array_key_exists('src', $attributes) ? $attributes['src'] : '');
		$src = str_replace('//', '/', $src);
		
		$type = (strcmp(strtolower(substr($src, -3)), "wav") == 0) ? 'audio/wav' : 'audio/mpeg';
		$source = CHtml::tag('source', array('src'=>$src, 'type'=>$type));
		$html = CHtml::tag('audio', array('controls'=>'controls'), $source);
		
		return $html;
	}
	
	function getLinks($attributes, $innerText)
	{
		$a = array();
		
		if (array_key_exists('src', $attributes))
		{
			$a[] = str_replace('//', '/', Yii::app()->baseUrl . '/' . $attributes['src']);
		}
		
		return $a;
	}
}

