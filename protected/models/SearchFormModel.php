<?php

class SearchFormModel extends CFormModel
{
	public $keyword = '';
	public $searchTitlesOnly = false;
	
	public $posts_results = array();
	public $tags_results = array();
	public $categories_results = array();

	/**
	 * Declares the validation rules.
	 */
	public function rules()
	{
		return array(
			array('keyword', 'length', 'min'=>3, 'allowEmpty'=>false),
			array('searchTitlesOnly', 'boolean'),
		);
	}

	/**
	 * Declares customized attribute labels.
	 * If not declared here, an attribute would have a label that is
	 * the same as its name with the first letter in upper case.
	 */
	public function attributeLabels()
	{
		return array(
			'keyword'=>'Όροι αναζήτησης',
			'searchTitlesOnly'=>'Αναζήτηση μόνο στους τίτλους',
		);
	}
	
	
	public function doSearch()
	{
		Yii::log('Search Keyword: [' . $this->keyword . ']', 'debug');
		
		$terms = preg_split('/\s+/', $this->keyword);
		Yii::log('Search Terms: [' . implode(', ', $terms) . ']', 'debug');
		
		$posts_conditions = array();
		$categories_conditions = array();
		$tags_conditions = array();
		$params = array();
		for ($i = 0; $i < count($terms); $i++)
		{
			if ($this->searchTitlesOnly)
				$posts_conditions[] = '(title LIKE :p'.$i.')';
			else
				$posts_conditions[] = '(title LIKE :p'.$i.' OR content LiKE :p'.$i.')';
			
			$categories_conditions[] = 'title LIKE :p'.$i;
			$tags_conditions[] = 'name LIKE :p'.$i;
			
			
			$params[':p'.$i] = '%'.addcslashes($terms[$i], '%_').'%';
		}
		
		$posts_criteria = array(
			'condition'=>'status = '.Post::STATUS_PUBLISHED.' AND (' . implode(' AND ', $posts_conditions) . ')',
			'params'=>$params,
			'order'=>'create_time DESC',
		);
		$categories_criteria = array(
			'condition'=>'status = '.Category::STATUS_PUBLISHED.' AND (' . implode(' AND ', $categories_conditions) . ')',
			'params'=>$params,
			'order'=>'title',
		);
		$tags_criteria = array(
			'condition'=>'(' . implode(' AND ', $tags_conditions) . ')',
			'params'=>$params,
			'order'=>'name',
		);
		
		Yii::log('Search Posts Criteria: [' . var_export($posts_criteria, true) . ']', 'debug');
		
		$this->posts_results = Post::model()->findAll($posts_criteria);
		$this->categories_results = Category::model()->findAll($categories_criteria);
		$this->tags_results = Tag::model()->findAll($tags_criteria);
	}
	
	
	
}