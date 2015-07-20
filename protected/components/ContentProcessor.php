<?php

class ContentProcessor extends CApplicationComponent
{
	protected $processors = array();
	protected $publish_path;
	
	public function init()
	{
		$poa = Yii::getPathOfAlias('application.components.ContentProcessor');
		$this->publish_path = Yii::app()->assetManager->publish($poa);
		Yii::app()->clientScript->registerCssFile($this->publish_path . '/content-processor.css');
		
		require_once(dirname(__FILE__).'/ContentProcessor/VideoMacroProcessor.php');
		require_once(dirname(__FILE__).'/ContentProcessor/AudioMacroProcessor.php');
		require_once(dirname(__FILE__).'/ContentProcessor/GalleryMacroProcessor.php');
		require_once(dirname(__FILE__).'/ContentProcessor/PostMacroProcessor.php');
		require_once(dirname(__FILE__).'/ContentProcessor/CategoryMacroProcessor.php');
		
		$this->processors = array(
			new VideoMacroProcessor(),
			new AudioMacroProcessor(),
			new GalleryMacroProcessor(),
			new PostMacroProcessor(),
			new CategoryMacroProcessor(),
		);
		
		parent::init();
	}
	
	public function process($content)
	{
		try
		{
			foreach ($this->processors as $processor)
				$content = $processor->parseAndConvert($content);
		}
		catch (Exception $e)
		{
			Yii::log($e->getMessage(), 'error');
			$content = $e->getMessage();
		}
		
		return $content;
	}
	
	public function getImages($content)
	{
		return $this->getAnything($content, 'getImages');
	}
	
	public function getLinks($content)
	{
		return $this->getAnything($content, 'getLinks');
	}

	protected function getAnything($content, $funcName)
	{
		$a = array();
		try
		{
			foreach ($this->processors as $processor)
				$a = array_merge($a, $processor->parseAndExtract($content, $funcName));
		}
		catch (Exception $e)
		{
			Yii::log($e->getMessage(), 'error');
			$a = array($e->getMessage());
		}
		
		return $a;
	}
}




class BaseMacroProcessor
{
	// the part after the "["
	var $shortcode = 'dummy';

	/**
	 * Searches for shortcode in the format [shortcode a="1" b="2" ...]
	 * and generates the result
	 */
	function parseAndConvert($content)
	{
		// perform simple string search first, for speed
		if (strpos($content, '[' . $this->shortcode) === false)
			return $content;
		
		// now, seek flexibly. tested and works with the following: 
		// [dummy]
		// [dummy a="1"]
		// [dummy]content[/dummy]
		// [dummy a="1" b="2"]content[/dummy]
		// content must not contain a "[" though...
		
		$re = '/\[(' . $this->shortcode . ')(.*?)\](([^\[]*?)\[\/\1\])?/s';
		$result = preg_replace_callback($re, array($this, 'parseAndConvert_callback'), $content);
		if ($result !== null)
			$content = $result;
		
		return $content;
	}
	
	function parseAndConvert_callback($match)
	{
		$attributes = $this->parseAttributes(strip_tags($match[2]));
		$innerText = count($match) > 4 ? $match[4] : '';
		return $this->getHtml($attributes, $innerText);
	}
	
	/**
	 * Searches for shortcode in the format [shortcode a="1" b="2" ...]
	 * and calls the processor with the defined action with an associative array of the shortcode attributes
	 */
	function parseAndExtract($content, $funcName)
	{
		// perform simple string search first, for speed
		if (strpos($content, '[' . $this->shortcode) === false)
			return array();
		
		// now, seek flexibly. tested and works with the following: 
		// [dummy]
		// [dummy a="1"]
		// [dummy]content[/dummy]
		// [dummy a="1" b="2"]content[/dummy]
		// content must not contain a "[" though...
		
		// here we do not replace , we extract info...
		$re = '/\[(' . $this->shortcode . ')(.*?)\](([^\[]*?)\[\/\1\])?/s';
		$found_macros = array();
		preg_match_all($re, $content, $found_macros, PREG_SET_ORDER);
		$a = array();
		foreach ($found_macros as $match)
		{
			$attributes = $this->parseAttributes(strip_tags($match[2]));
			$innerText = count($match) > 4 ? $match[4] : '';
			$a = array_merge($a, call_user_func(array($this, $funcName), $attributes, $innerText));
		}
		
		return $a;
	}
	
	
	/**
	 * breaks up text in the form a="1" b="2" into an assoc array 'a'=>'1', 'b'=>'2', etc.
	 * multiple attributes with same name create an array of values (eg, gallery images)
	 */
	protected function parseAttributes($attributes_string)
	{
		$attributes = array();
		$split_attributes = array();
		$re = '/([a-zA-Z0-9_]+?)=("|&quot;)(.*?)\2/';
		preg_match_all($re, $attributes_string, $split_attributes, PREG_SET_ORDER);
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
	
		return $attributes;
	}
	
	

	
	
	
	
	
	/** 
	 * to be overriden in child classes
	 */
	protected function getHtml($attributes, $innerText)
	{
		return '';
	}
	
	protected function getLinks($attributes, $innerText)
	{
		return array();
	}
	
	protected function getImages($attributes, $innerText)
	{
		return array();
	}
}






