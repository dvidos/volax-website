<?php

class Category extends CActiveRecord
{
	const STATUS_DRAFT=1;
	const STATUS_PUBLISHED=2;

	/**
	 * Returns the static model of the specified AR class.
	 * @return CActiveRecord the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{category}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('status, layout', 'required'),
			array('title, image_filename, image2_filename', 'length', 'max'=>128),
			array('parent_id, view_order, layout, status', 'numerical'),
			array('title, content, prologue, masthead', 'safe'),
			array('id, parent_id, title, content, prologue, masthead, image_filename, image2_filename, layout, status, view_order', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'parent' => array(self::BELONGS_TO, 'Category', 'parent_id'),
			'subcategories'=>array(self::HAS_MANY, 'Category', 'parent_id', 'order'=>'view_order,title', 'condition'=>'status='.Category::STATUS_PUBLISHED),
			'posts' => array(self::HAS_MANY, 'Post', 'category_id'),
			'postsCount' => array(self::STAT, 'Post', 'category_id', 'condition'=>'status='.'2'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'Id',
			'parent_id' => 'Πατρική κατηγορία',
			'title' => 'Τίτλος',
			'prologue' => 'Πρόλογος',
			'masthead' => 'Υπέρτιτλος',
			'content' => 'Περιεχόμενο',
			'image_filename' => 'Εικόνα',
			'image2_filename' => 'Μικρή εικόνα',
			'layout' => 'Εμφάνιση',
			'status' => 'Κατάσταση',
			'create_time' => 'Create Time',
			'update_time' => 'Update Time',
			'view_order' => 'Σειρά',
		);
	}

	/**
	 * @return string the URL that shows the detail of the post
	 */
	public function getUrl()
	{
		return Yii::app()->createUrl('category/view', array(
			'id'=>$this->id,
			'title'=>$this->title,
		));
	}

	/**
	 * This is invoked before the record is saved.
	 * @return boolean whether the record should be saved.
	 */
	protected function beforeSave()
	{
		if(parent::beforeSave())
		{
			if($this->isNewRecord)
			{
				$this->create_time=$this->update_time=time();
			}
			else
				$this->update_time=time();
			return true;
		}
		else
			return false;
	}

	/**
	 * This is invoked after the record is saved.
	 */
	protected function afterSave()
	{
		parent::afterSave();
	}

	/**
	 * This is invoked after the record is deleted.
	 */
	protected function afterDelete()
	{
		parent::afterDelete();
	}

	/**
	 * Retrieves the list of posts based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the needed posts.
	 */
	public function search()
	{
		$criteria=new CDbCriteria;

		$criteria->compare('parent_id',$this->parent_id);
		$criteria->compare('title',$this->title,true);
		$criteria->compare('prologue',$this->prologue,true);
		$criteria->compare('masthead',$this->masthead,true);
		$criteria->compare('content',$this->content,true);
		$criteria->compare('image_filename',$this->image_filename,true);
		$criteria->compare('image2_filename',$this->image2_filename,true);
		$criteria->compare('layout',$this->layout);
		$criteria->compare('status',$this->status);
		$criteria->compare('create_time',$this->create_time);
		$criteria->compare('update_time',$this->update_time);
		$criteria->compare('view_order',$this->view_order);

		return new CActiveDataProvider('Category', array(
			'criteria'=>$criteria,
			'sort'=>array(
				'defaultOrder'=>'parent_id, view_order, title, update_time DESC',
			),
			'pagination'=>array(
				'pageSize'=>15,
			),
		));
	}

	private static $_items = null;
	public static function dropDownListItems()
	{
		if(self::$_items == null)
		{
			self::$_items = array('0'=>'(None)'); // to allow for editing a root category
			self::loadDropDownListItemsOf(0, 0);
		}
		return self::$_items;
	}
	
	private static function loadDropDownListItemsOf($parent_id, $depth)
	{
		$models = self::findAllOfParent($parent_id);
		foreach ($models as $model)
		{
			self::$_items[$model->id] = str_repeat('. . . ' , $depth) . $model->title;
			self::loadDropDownListItemsOf($model->id, $depth + 1);
		}
	}

	public static function getLayoutOptions()
	{
		// remember to change protected/views/category/view.php as well.
		return array(
			1 => 'One column upto more',
			2 => 'Two columns upto more',
			3 => 'Three columns upto more',
			4 => 'Mixed 2:1 columns upto more',
			5 => 'One column title only',
			6 => 'One column full content',
		);
	}
	
	public function getLayoutItemViewFile()
	{
		if ($this->layout == 1)
			return '/post/_layoutDesiredWidth';
		else if ($this->layout == 2)
			return '/post/_layoutHalfUptoMore';
		else if ($this->layout == 3)
			return '/post/_layoutThirdUptoMore';
		else if ($this->layout == 4)
			return '/post/_layoutNarrowWideUptoMore';
		else if ($this->layout == 5)
			return '/post/_layoutWideTitleOnly';
		else if ($this->layout == 6)
			return '/post/_layoutWideFullText';

		return '/post/_layoutWideUptoMore';
	}
	
	public static function getLayoutCaption($layout)
	{
		$options = self::getLayoutOptions();
		if (array_key_exists($layout, $options))
			return $options[$layout];
		else
			return '(unknown)';
	}

	public static function findAllOfParent($parent_id)
	{
		return Category::model()->findAll(array(
			'condition' => 'parent_id = :pid',
			'params'=>array(':pid'=>$parent_id),
			'order'=>'view_order,title',
		));
	}

	public function getContentHtml()
	{
		//$parser = new CMarkdownParser();
		//$content = $parser->transform($this->content);
		$content = $this->content;
		
		return $content;
	}
	
	public static function getCMenuItems($parent_id)
	{
		// return a hierarchical array of items for CMenu.
		// each item should have: label, optional url, optional items.
		$items = array();
		
		$cats = self::findAllOfParent($parent_id);
		foreach ($cats as $cat)
		{
			$item = array('label'=>$cat->title);
			
			$subItems = self::getCMenuItems($cat->id);
			if (count($subItems) > 0)
				$item['items'] = $subItems;
			else
				$item['url'] = array('category/view', 'id'=>$cat->id);
			
			$items[] = $item;
		}
		
		return $items;
	}
}

