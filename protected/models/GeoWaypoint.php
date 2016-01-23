<?php

/**
 * This is the model class for table "{{geo_waypoints}}".
 *
 * The followings are the available columns in table '{{geo_waypoints}}':
 * @property integer $id
 * @property integer $feature_id
 * @property integer $waypoint_no
 * @property string $title
 * @property string $image
 * @property string $geo_long
 * @property string $geo_lat
 */
class GeoWaypoint extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return GeoWaypoint the static model class
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
		return '{{geo_waypoints}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('feature_id, waypoint_no, geo_long, geo_lat', 'required'),
			array('title, image', 'length', 'max'=>200),
			array('feature_id, waypoint_no', 'numerical', 'integerOnly'=>true),
			array('geo_long, geo_lat', 'length', 'max'=>15),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, feature_id, waypoint_no, title, image, geo_long, geo_lat', 'safe', 'on'=>'search'),
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
			'feature'=>array(self::BELONGS_TO, 'GeoFeature', 'feature_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'feature_id' => 'Εγγραφή',
			'waypoint_no' => 'Α/Α',
			'title' => 'Τίτλος',
			'image' => 'Εικόνα',
			'geo_long' => 'Γεωγραφικό μήκος',
			'geo_lat' => 'Γεωγραφικό πλάτος',
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
		$criteria->compare('feature_id',$this->feature_id);
		$criteria->compare('waypoint_no',$this->waypoint_no);
		$criteria->compare('title',$this->title,true);
		$criteria->compare('image',$this->image,true);
		$criteria->compare('geo_long',$this->geo_long,true);
		$criteria->compare('geo_lat',$this->geo_lat,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'sort'=>array(
				'defaultOrder'=>'feature_id,waypoint_no',
			),
			'pagination'=>array(
				'pageSize'=>30,
			),
		));
	}
}