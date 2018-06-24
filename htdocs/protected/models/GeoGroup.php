<?php

/**
 * This is the model class for table "{{geo_groups}}".
 *
 * The followings are the available columns in table '{{geo_groups}}':
 * @property integer $id
 * @property integer $active
 * @property integer $view_order
 * @property string $title
 * @property string $description
 */
class GeoGroup extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return GeoGroup the static model class
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
		return '{{geo_groups}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('active, view_order, title', 'required'),
			array('active, view_order', 'numerical', 'integerOnly'=>true),
			array('title', 'length', 'max'=>200),
			array('description', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, active, view_order, title, description', 'safe', 'on'=>'search'),
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
			'features'=>array(self::HAS_MANY, 'GeoFeature', 'group_id'),
			'featuresCount'=>array(self::STAT, 'GeoFeature', 'group_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'active' => 'Δημόσιο',
			'view_order' => 'Σειρά εμφάνισης',
			'title' => 'Τίτλος',
			'description' => 'Περιγραφή',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('active',$this->active);
		$criteria->compare('view_order',$this->view_order);
		$criteria->compare('title',$this->title,true);
		$criteria->compare('description',$this->description,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
	public function toAjaxAssoc()
	{
		return array(
			'id'=>$this->id,
			'title'=>$this->title,
			'description'=>$this->description,
		);
	}
}