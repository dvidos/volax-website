<?php

class PostMacroProcessor extends BaseMacroProcessor
{
	function __construct()
	{
		$this->shortcode = 'post';
	}
	
	function getHtml($attributes, $innerText)
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
	
	function getLinks($attributes, $innerText)
	{
		$a = array();
		
		if (array_key_exists('id', $attributes))
		{
			$id = array_key_exists('id', $attributes) ? $attributes['id'] : 0;
			$post = Post::model()->findByPk($id);
			if ($post == null)
				$a[] = 'post #' . $id . ' not found';
			else
				$a[] = Yii::app()->createUrl('/post/view', array('id'=>$post->id, 'title'=>$post->title));
		}
		
		return $a;
	}
}

