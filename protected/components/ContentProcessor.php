<?php

class ContentProcessor extends CWidget
{
	public $content = '';
	protected $publish_path;
	protected static $player_counter = 0;
	
	
	public function init()
	{
		$poa = Yii::getPathOfAlias('application.components.ContentProcessor');
		$this->publish_path = Yii::app()->assetManager->publish($poa);
		Yii::app()->clientScript->registerScriptFile($this->publish_path . '/audio-player.js');
		Yii::app()->clientScript->registerCssFile($this->publish_path . '/content-processor.css');
		
		parent::init();
	}
	
	
	
	public function run()
	{
		try
		{
			//throw new Exception("Arghhhh... php ver. " . phpversion());
			$this->content = $this->removeImageInlineStyles($this->content);
			
			// [video src="https://www.youtube.com/play?v=XXXXXXXXX"]
			if (($pos = strpos($this->content, '[video ')) !== false)
			{
				// could not use the inline anonymous function in Forthnet PHP...
				$this->content = preg_replace_callback('/\[video\s+src=(&quot;|")(.+?)\1\]/i', array($this, 'processVideo'), $this->content);
			}
			
			// [audio src="/uploads/jimel/old_site/aeras.mp3"]
			if (($pos = strpos($this->content, '[audio ')) !== false)
			{
				// could not use the inline anonymous function in Forthnet PHP...
				$this->content = preg_replace_callback('/\[audio\s+src=(&quot;|")(.+?)\1\]/i', array($this, 'processAudio'), $this->content);
			}
			
			// [download ...]
			
			
			// [gallery ...]
			
			
			// [post id="xxx" title="xxx"]
			
			
			// [category id="xxx" title="xxx"]
			
		}
		catch (Exception $e)
		{
			$this->content = $e->getMessage();
		}
		
		echo $this->content;
	}
	

	function removeImageInlineStyles($text)
	{
		// strip inline styles from images to allow master css file to render them
		return preg_replace('/<img(.+?)style="[^"]+"([^>]*)>/si', '<img$1$3>', $text);
	}

	
	function processVideo($matches)
	{
		$src = $matches[2];
		
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

	
	function processAudio($matches)
	{
		$src = $matches[2];
		
		if (substr($src, 0, 1) != '/')
			$src = '/' . $src;
		
		if (strpos($src, '/uploads') === false)
			$src = '/uploads' . $src;
		
		$bu = Yii::app()->baseUrl;
		if (substr($src, 0, strlen($bu)) != $bu)
			$src = $bu . $src;
		
		// audio player by 1pixelout: http://www.1pixelout.net/code/audio-player-wordpress-plugin/
		$player_swf = $this->publish_path . '/player.swf';
		$html = 
			'<object class="audioPlayer" type="application/x-shockwave-flash" data="' . $player_swf . '" id="audioplayer' . self::$player_counter . '" height="32" width="280">'.
				'<param name="movie" value="'.$player_swf.'">'.
				'<param name="FlashVars" value="playerID='.self::$player_counter.'&amp;soundFile=' . $src . '">'.
				'<param name="quality" value="high">'.
				'<param name="menu" value="false">'.
				'<param name="wmode" value="transparent">'.
			'</object>';

		$html = CHtml::tag('div', array('class'=>'audioWrapper'), $html);
		
		//$html .= '<br />(src=' . $src . ', baseUrl='.Yii::app()->baseUrl.')';
		
		self::$player_counter++;
		
		return $html;
	}
}



