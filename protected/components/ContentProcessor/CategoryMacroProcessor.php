<?php

class CategoryMacroProcessor extends BaseMacroProcessor
{
	function __construct()
	{
		$this->shortcode = 'category';
	}
	
	function getHtml($attributes, $innerText)
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
	
	function getLinks($attributes, $innerText)
	{
		$a = array();
		
		if (array_key_exists('id', $attributes))
		{
			$id = array_key_exists('id', $attributes) ? $attributes['id'] : 0;
			$category = Category::model()->findByPk($id);
			if ($category == null)
				$a[] = 'category #' . $id . ' not found';
			else
				$a[] = Yii::app()->createUrl('/category/view', array('id'=>$category->id, 'title'=>$category->title));
		}
		
		return $a;
	}
}

