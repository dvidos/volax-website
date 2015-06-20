<?php

class ContentProcessor extends CWidget
{
	public $content = '';
	protected $publish_path;
	protected $shortcodes_callbacks;
	protected static $player_counter = 0;
	
	
	
	public function init()
	{
		$poa = Yii::getPathOfAlias('application.components.ContentProcessor');
		$this->publish_path = Yii::app()->assetManager->publish($poa);
		Yii::app()->clientScript->registerScriptFile($this->publish_path . '/audio-player.js');
		Yii::app()->clientScript->registerCssFile($this->publish_path . '/content-processor.css');
		
		$this->shortcodes_callbacks = array(
			'video' => array($this, 'handleVideoShortcode'),
			'audio' => array($this, 'handleAudioShortcode_swf'),
			'audio' => array($this, 'handleAudioShortcode'),
			'gallery' => array($this, 'handleGalleryShortcode'),
			'post' => array($this, 'handlePostShortcode'),
			'category' => array($this, 'handleCategoryShortcode'),
			'dummy' => array($this, 'handleDummyShortcode'),
		);
		
		parent::init();
	}
	
	public function run()
	{
		try
		{
			foreach ($this->shortcodes_callbacks as $tag => $callback)
				$this->processShortcode($tag);
			
			$this->content = $this->removeImageInlineStyles($this->content);
		}
		catch (Exception $e)
		{
			Yii::log($e->getMessage(), 'error');
			$this->content = $e->getMessage();
		}
		
		echo $this->content;
	}
	
	function removeImageInlineStyles($text)
	{
		// strip inline styles from images to allow master css file to render them
		//return preg_replace('/<img([^>]*?)style="[^"]*?"([^>]*)>/si', '<img$1$3>', $text);
		return $text;
	}
	
	/**
	 * Searches for shortcode in the format [shortcode a="1" b="2" ...]
	 * and calls the callback with an associative array of the shortcode attributes
	 * $shortcode should be plain english, no symbols
	 */
	function processShortcode($shortcode)
	{
		// perform simple string search first, for speed
		if (strpos($this->content, '[' . $shortcode) === false)
			return;
		
		// now, seek flexibly. tested and works with the following: 
		// [dummy]
		// [dummy a="1"]
		// [dummy]content[/dummy]
		// [dummy a="1" b="2"]content[/dummy]
		// content must not contain a "[" though...
		
		$re = '/\[(' . $shortcode . ')(.*?)\](([^\[]*?)\[\/\1\])?/s';
		$result = preg_replace_callback($re, array($this, 'processShortcodeAttributes'), $this->content);
		if ($result !== null)
			$this->content = $result;
	}
	
	function processShortcodeAttributes($matches)
	{
		$shortcode = $matches[1];
		if (!array_key_exists($shortcode, $this->shortcodes_callbacks))
		{
			Yii::log($e->getMessage(), 'error');
			return '(' . $matches[0] . ')';
		}
		
		$attributes_string = array_key_exists(2, $matches) ? $matches[2] : '';
		$content = array_key_exists(4, $matches) ? $matches[4] : '';
		$callback = $this->shortcodes_callbacks[$shortcode];
		
		
		$attributes = array();
		$split_attributes = array();
		$re = '/([a-zA-Z0-9_]+?)=("|&quot;)(.*?)\2/';
		preg_match_all($re, $matches[2], $split_attributes, PREG_SET_ORDER);
		foreach ($split_attributes as $split_attribute)
		{
			$key = $split_attribute[1];
			$val = $split_attribute[3];
			
			if (!array_key_exists($key, $attributes))
			{
				$attributes[$key] = $val; // simple, one value per attribute
			}
			else
			{
				// already exists. see if we need to convert to array
				if (is_array($attributes[$key]))
					$attributes[$key][] = $val;
				else
					$attributes[$key] = array($attributes[$key], $val);
			}
		}
		
	
		// finally call the callback, passing the shortcode and the attributes
		return call_user_func($callback, $shortcode, $attributes, $content);
	}



	
	
	
	
	
	
	
	
	function handleVideoShortcode($code, $attributes, $content)
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
		
	function handleAudioShortcode_swf($code, $attributes, $content)
	{
		$src = array_key_exists('src', $attributes) ? $attributes['src'] : '';
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
	
	function handleAudioShortcode($code, $attributes, $content)
	{
		$src = Yii::app()->baseUrl . '/' . (array_key_exists('src', $attributes) ? $attributes['src'] : '');
		$src = str_replace('//', '/', $src);
		
		$type = (strcmp(strtolower(substr($src, -3)), "wav") == 0) ? 'audio/wav' : 'audio/mpeg';
		$source = CHtml::tag('source', array('src'=>$src, 'type'=>$type));
		$html = CHtml::tag('audio', array('controls'=>'controls'), $source);
		
		return $html;
	}
	
	function handleGalleryShortcode($code, $attributes, $content)
	{
		$cols = array_key_exists('cols', $attributes) ? $attributes['cols'] : 0;
		$folder = array_key_exists('folder', $attributes) ? $attributes['folder'] : '';
		
		// now find as many "img" attributes as there are, 
		// plus as many "caption" attributes as there are...
		
		$images = array_key_exists('img', $attributes) ? $attributes['img'] : '';
		if (!is_array($images))
			$images = array($images);
		
		$thumbs = array_key_exists('thumb', $attributes) ? $attributes['thumb'] : '';
		if (!is_array($thumbs))
			$thumbs = array($thumbs);
		
		$captions = array_key_exists('caption', $attributes) ? $attributes['caption'] : '';
		if (!is_array($captions))
			$captions = array($captions);
		
		
		while (count($thumbs) < count($images))
			$thumbs[] = '';
		while (count($captions) < count($images))
			$captions[] = '';
		
		$margin_percent = 1;
		$item_style = 'display: inline-block; margin: 0 '.$margin_percent.'% 1% 0;';
		if ($cols > 0)
		{
			$item_width = (100.0 / $cols) - $margin_percent;
			$item_style .= 'width: '.$item_width.'%; overflow: hidden;';
		}
		
		
		$items_html = array();
		for ($i = 0; $i < count($images); $i++)
		{
			$image_href = str_replace('//', '/', Yii::app()->baseUrl . '/' . $folder . '/' . $images[$i]);
			$thumb_href = empty($thumbs[$i]) ? $image_href : str_replace('//', '/', Yii::app()->baseUrl . '/' . $folder . '/' . $thumbs[$i]);
			$thumb_img = CHtml::image($thumb_href, '', array('class'=>'gallery-item-thumb',));
			
			$cell = CHtml::link($thumb_img, $image_href, array('class'=>'gallery-item-link'));
			if (strlen($captions[$i]) > 0)
				$cell .= CHtml::tag('p', array('class'=>'gallery-item-caption'), $captions[$i]);
		
			$items_html[] = CHtml::tag('div', array('class'=>'gallery-item', 'style'=>$item_style), $cell);
		}
		
		$html = CHtml::tag('div', array('class'=>'gallery'), implode('', $items_html));
		//Yii::log("gallery macro result:\r\n$html", 'trace', 'ContentProcessor');
		return $html;
	}
	
	function handlePostShortcode($code, $attributes, $content)
	{
		$id = array_key_exists('id', $attributes) ? $attributes['id'] : 0;
		$text = array_key_exists('text', $attributes) ? $attributes['text'] : '';
		
		$post = Post::model()->findByPk($id);
		if ($post == null)
		{
			return CHtml::link(CHtml::encode(empty($text) ? '#' : $text), array('/post/view', 'id'=>$id));
		}
		else
		{
			if (empty($text))
			{
				return CHtml::link(CHtml::encode($post->title), array('/post/view', 'id'=>$post->id, 'title'=>$post->title));
			}
			else
			{
				return CHtml::link(CHtml::encode($text), array('/post/view', 'id'=>$post->id, 'title'=>$post->title), array('title'=>$post->title));
			}
		}
	}
		
	function handleCategoryShortcode($code, $attributes, $content)
	{
		$id = array_key_exists('id', $attributes) ? $attributes['id'] : 0;
		$text = array_key_exists('text', $attributes) ? $attributes['text'] : '';
		
		$category = Category::model()->findByPk($id);
		if ($category == null)
		{
			return CHtml::link(CHtml::encode(empty($text) ? '#' : $text), array('/category/view', 'id'=>$id));
		}
		else
		{
			if (empty($text))
			{
				return CHtml::link(CHtml::encode($category->title), array('/category/view', 'id'=>$category->id, 'title'=>$category->title));
			}
			else
			{
				return CHtml::link(CHtml::encode($text), array('/category/view', 'id'=>$category->id, 'title'=>$category->title), array('title'=>$category->title));
			}
		}
	}

	function handleDummyShortcode($code, $attributes, $content)
	{
		return '(shortcode is "' . var_export($code, true) . '", content is "' . var_export($content, true) . '", attributes are ' . var_export($attributes, true) . '.)';
	}
}



