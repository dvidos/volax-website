<?php

class RenameTagsFormModel extends CFormModel
{
	public $initialTag;
	public $targetTag;
	public $danger;
	
	
	// this will search for the posts
	var $has_searched = false;
	var $posts = array();

	public function rules()
	{
		return array(
			// username and password are required
			array('initialTag', 'required', 'on'=>'search, rename'),
			array('targetTag, danger', 'required', 'on'=>'rename'),
			array('initialTag, targetTag', 'match', 'pattern'=>'/^[^\,]+$/', 'message'=>'Tags must not contain comma.'),
			array('initialTag, targetTag', 'application.components.validators.NoMixedLangValidator'),
			array('danger', 'required', 'requiredValue' => 1, 'message' => 'Πρέπει να καταλάβετε πως η ενέργεια ΔΕΝ μπορεί να αναιρεθεί', 'on'=>'rename'),
		);
	}
	
	public function attributeLabels()
	{
		return array(
			'initialTag'=>'Υπάρχον tag',
			'targetTag'=>'Νέο tag',
			'danger'=>'Καταλαβαίνω πως η ενέργεια ΔΕΝ μπορεί να αναιρεθεί!',
		);
	}
	
	public function searchPosts()
	{
		$criteria=new CDbCriteria();
		$criteria->addSearchCondition('tags',$this->initialTag);
		$this->posts = Post::model()->findAll($criteria);
		$this->has_searched = true;
	}
	
	public function renamePosts()
	{
		foreach ($this->posts as $post)
		{
			// here we (hopefully) do not need "mb_xxxx" because both tags and our strings are in utf-8
			$post->tags = str_replace($this->initialTag, $this->targetTag, $post->tags);
			$post->update(array('tags'));
		}
	}
}
