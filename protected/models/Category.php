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
			array('title, content, status', 'required'),
			array('status', 'in', 'range'=>array(1,2)),
			array('title, image_filename', 'length', 'max'=>128),
			array('parent_id, view_order', 'numerical'),

			array('id, parent_id, title, content, image_filename, status, view_order', 'safe', 'on'=>'search'),
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
			'posts' => array(self::HAS_MANY, 'Post', 'category_id'),
			//'postsCount' => array(self::STAT, 'Post', 'category_id', 'condition'=>'status='.Post::STATUS_APPROVED),
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
			'parent_id' => 'Parent',
			'title' => 'Title',
			'content' => 'Content',
			'image_filename' => 'Image',
			'status' => 'Status',
			'create_time' => 'Create Time',
			'update_time' => 'Update Time',
			'view_order' => 'View Order',
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
		$criteria->compare('status',$this->status);

		return new CActiveDataProvider('Category', array(
			'criteria'=>$criteria,
			'sort'=>array(
				'defaultOrder'=>'title, view_order, update_time DESC',
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
		$models = Category::model()->findAll(array(
			'condition' => 'parent_id = :pid',
			'params'=>array(':pid'=>$parent_id),
			'order'=>'title',
		));
		foreach ($models as $model)
		{
			self::$_items[$model->id] = str_repeat('   ' , $depth) . $model->title;
			self::loadDropDownListItemsOf($model->id, $depth + 1);
		}
	}
	
	
}

