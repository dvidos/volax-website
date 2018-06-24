<?php

class Status extends CActiveRecord
{
	private static $_items=array();
	
	const POST_STATUS = 'PostStatus';
	const CATEGORY_STATUS = 'CategoryStatus';
	const COMMENT_STATUS = 'CommentStatus';

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
		return '{{status}}';
	}

	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name, code, type, position', 'required'),
			array('id, name, code, type, position', 'safe', 'on'=>'search'),
		);
	}
	
	public function attributeLabels()
	{
		return array(
			'id' => 'Id',
			'name' => 'Ονομα',
			'code' => 'Κωδικός',
			'type' => 'Τύπος',
			'position' => 'Σειρά εμφάνισης',
		);
	}

	/**
	 * Returns the items for the specified type.
	 * @param string item type (e.g. 'PostStatus').
	 * @return array item names indexed by item code. The items are order by their position values.
	 * An empty array is returned if the item type does not exist.
	 */
	public static function items($type)
	{
		if(!isset(self::$_items[$type]))
			self::loadItems($type);
		return self::$_items[$type];
	}

	/**
	 * Returns the item name for the specified type and code.
	 * @param string the item type (e.g. 'PostStatus').
	 * @param integer the item code (corresponding to the 'code' column value)
	 * @return string the item name for the specified the code. False is returned if the item type or code does not exist.
	 */
	public static function item($type,$code)
	{
		if(!isset(self::$_items[$type]))
			self::loadItems($type);
		return isset(self::$_items[$type][$code]) ? self::$_items[$type][$code] : false;
	}

	/**
	 * Loads the lookup items for the specified type from the database.
	 * @param string the item type
	 */
	private static function loadItems($type)
	{
		self::$_items[$type]=array();
		$models=self::model()->findAll(array(
			'condition'=>'type=:type',
			'params'=>array(':type'=>$type),
			'order'=>'position',
		));
		foreach($models as $model)
			self::$_items[$type][$model->code]=$model->name;
	}
	

	public static function getTypeOptions()
	{
		return array(
			self::POST_STATUS=>self::POST_STATUS,
			self::CATEGORY_STATUS=>self::CATEGORY_STATUS,
			self::COMMENT_STATUS=>self::COMMENT_STATUS,
		);
	}
	
	public function search()
	{
		$criteria=new CDbCriteria;

		$criteria->compare('name',$this->name, true);
		$criteria->compare('code',$this->code, true);
		$criteria->compare('type',$this->type, true);
		$criteria->compare('position',$this->position);

		return new CActiveDataProvider('Status', array(
			'criteria'=>$criteria,
			'sort'=>array(
				'defaultOrder'=>'type, position',
			),
			'pagination'=>array(
				'pageSize'=>25,
			),
		));
	}
}