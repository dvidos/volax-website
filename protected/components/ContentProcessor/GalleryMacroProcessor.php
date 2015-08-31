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
		
		
		
		
		
		$table_cols_count = max($cols, 1);
		$table_rows_count = ceil(count($images) / $table_cols_count);
		$table_col_width = round(100 / $table_cols_count);
		$index = 0;
		for ($r = 0; $r < $table_rows_count; $r++)
		{
			$thumbs_cells = array();
			$captions_cells = array();
			$row_has_caption = false;
			
			for ($c = 0; $c < $table_cols_count; $c++)
			{
				if ($index < count($images))
				{
					$thumb = $thumbs[$index];
					$image = $images[$index];
					$caption = $captions[$index];
					$row_has_caption = (strlen($caption) > 0) ? true : $row_has_caption;
					
					$image_href = str_replace('//', '/', Yii::app()->baseUrl . '/' . $folder . '/' . $image);
					$thumb_href = empty($thumb) ? $image_href : str_replace('//', '/', Yii::app()->baseUrl . '/' . $folder . '/' . $thumb);
					$thumb_img = CHtml::image($thumb_href, '', array('class'=>'gallery-item-thumb',));
					//$thumb_cell = CHtml::link($thumb_img, $image_href, array('class'=>'gallery-item-link'));
					$thumb_cell = Yii::app()->controller->widget('application.components.LightboxLinkWidget', array(
						'href'=>$image_href,
						'group'=>'gallery',
						'html'=>$thumb_img,
					), true);
					$caption_cell = $caption;
				}
				else
				{
					$thumb_cell = '&nbsp;';
					$caption_cell = '&nbsp;';
				}
				
				$thumbs_cells[] = CHtml::tag('td', array('style'=>'width:'.$table_col_width.'%'), $thumb_cell);
				$captions_cells[] = CHtml::tag('td', array(), $caption_cell);
				$index++;
			}
			
			$table_rows[] = CHtml::tag('tr', array(), implode('', $thumbs_cells));
			if ($row_has_caption)
				$table_rows[] = CHtml::tag('tr', array(), implode('', $captions_cells));
		}
		
		$html = CHtml::tag('table', array('class'=>'img-gallery'), implode('', $table_rows));
		
		
		
		
		// $margin_percent = 1;
		// $item_style = 'display: inline-block; margin: 0 '.$margin_percent.'% 1% 0;';
		// if ($cols > 0)
		// {
			// $item_width = (100.0 / $cols) - $margin_percent;
			// $item_style .= 'width: '.$item_width.'%; overflow: hidden;';
		// }
		
		
		// $items_html = array();
		// for ($i = 0; $i < count($images); $i++)
		// {
			// $image_href = str_replace('//', '/', Yii::app()->baseUrl . '/' . $folder . '/' . $images[$i]);
			// $thumb_href = empty($thumbs[$i]) ? $image_href : str_replace('//', '/', Yii::app()->baseUrl . '/' . $folder . '/' . $thumbs[$i]);
			// $thumb_img = CHtml::image($thumb_href, '', array('class'=>'gallery-item-thumb',));
			
			// $cell = CHtml::link($thumb_img, $image_href, array('class'=>'gallery-item-link'));
			// if (strlen($captions[$i]) > 0)
				// $cell .= CHtml::tag('p', array('class'=>'gallery-item-caption'), $captions[$i]);
		
			// $items_html[] = CHtml::tag('div', array('class'=>'gallery-item', 'style'=>$item_style), $cell);
		// }
		
		// $html = CHtml::tag('div', array('class'=>'gallery'), implode('', $items_html));
		// //Yii::log("gallery macro result:\r\n$html", 'trace', 'GalleryMacroProcessor');
		
		
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

