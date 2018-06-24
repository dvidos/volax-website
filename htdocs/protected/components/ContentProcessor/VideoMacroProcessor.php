<?php

class VideoMacroProcessor extends BaseMacroProcessor
{
	function __construct()
	{
		$this->shortcode = 'video';
	}
	
	function getHtml($attributes, $innerText)
	{
		$src = array_key_exists('src', $attributes) ? $attributes['src'] : '';
		if (strpos($src, 'youtube') !== false)
		{
			$pos = strpos($src, '?v=');
			if ($pos == false)
			{
				$msg = '(did not find the ?v= keyword, source was "' . $src . '")';
				Yii::warning($msg, 'VideoPlayerWidget');
				return $msg;
			}
			
			
			$v = substr($src, $pos + 3);
			$iframe = CHtml::tag('iframe', 
				array(
					// cannot set fixed aspect ratio, therefore we set max-width to 100% and padding-bottom to 51% (of width)
					'class'=>'videoPlayer',
					'width'=>'560',
					'height'=>'315',
					'frameborder'=>'0',
					'allowfullscreen'=>'allowfullscreen',
					'src'=>'https://www.youtube.com/embed/' . $v,
				),
			'');
			$div = CHtml::tag('div', array('class'=>'videoWrapper'), $iframe);
			
			return $div;
		}
		else // could support vimeo in the future...
		{
			$msg = '(only youtube videos are supported, source was "' . $src . '")';
			Yii::warning($msg, 'VideoPlayerWidget');
			return $msg;
		}
	}
	
	function getLinks($attributes, $innerText)
	{
		$a = array();
		
		if (array_key_exists('src', $attributes))
		{
			$a[] = $attributes['src'];
		}
		
		return $a;
	}
}

