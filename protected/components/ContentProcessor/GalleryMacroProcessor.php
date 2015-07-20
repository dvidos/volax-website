<?php

class GalleryMacroProcessor extends BaseMacroProcessor
{
	function __construct()
	{
		$this->shortcode = 'gallery';
	}
	
	function getHtml($attributes, $innerText)
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
		//Yii::log("gallery macro result:\r\n$html", 'trace', 'GalleryMacroProcessor');
		return $html;
	}
	
	function getImages($attributes, $innerText)
	{
		$a = array();
		$folder = array_key_exists('folder', $attributes) ? $attributes['folder'] : '';
		
		$images = array_key_exists('img', $attributes) ? $attributes['img'] : '';
		if (!is_array($images))
			$images = array($images);
		
		$thumbs = array_key_exists('thumb', $attributes) ? $attributes['thumb'] : '';
		if (!is_array($thumbs))
			$thumbs = array($thumbs);
		
		for ($i = 0; $i < count($images); $i++)
		{
			$image_href = str_replace('//', '/', Yii::app()->baseUrl . '/' . $folder . '/' . $images[$i]);
			$thumb_href = empty($thumbs[$i]) ? $image_href : str_replace('//', '/', Yii::app()->baseUrl . '/' . $folder . '/' . $thumbs[$i]);
			$a[] = $thumb_href;
		}
		
		return $a;
	}
	
	function getLinks($attributes, $innerText)
	{
		$a = array();
		$folder = array_key_exists('folder', $attributes) ? $attributes['folder'] : '';
		
		$images = array_key_exists('img', $attributes) ? $attributes['img'] : '';
		if (!is_array($images))
			$images = array($images);
		
		for ($i = 0; $i < count($images); $i++)
		{
			$image_href = str_replace('//', '/', Yii::app()->baseUrl . '/' . $folder . '/' . $images[$i]);
			$a[] = $image_href;
		}
		
		return $a;
	}
}

