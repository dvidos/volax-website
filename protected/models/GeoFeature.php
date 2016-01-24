<?php

/**
 * This is the model class for table "{{geo_features}}".
 *
 * The followings are the available columns in table '{{geo_features}}':
 * @property integer $id
 * @property string $feature_type
 * @property integer $group_id
 * @property string $title
 * @property string $description
 * @property string $geo_long
 * @property string $geo_lat
 * @property integer $active
 * @property integer $author_id
 * @property string $create_time
 * @property string $update_time
 */
class GeoFeature extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return GeoFeature the static model class
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
		return '{{geo_features}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('feature_type, group_id, title, geo_long, geo_lat, active', 'required'),
			array('group_id, author_id', 'numerical', 'integerOnly'=>true),
			array('active', 'boolean'),
			array('feature_type', 'length', 'max'=>5),
			array('title', 'length', 'max'=>200),
			array('geo_long, geo_lat', 'length', 'max'=>15),
			array('create_time, update_time', 'length', 'max'=>20),
			array('description', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, feature_type, group_id, title, description, geo_long, geo_lat, active, author_id, create_time, update_time', 'safe', 'on'=>'search'),
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
			'group'=>array(self::BELONGS_TO, 'GeoGroup', 'group_id'),
			'waypoints'=>array(self::HAS_MANY, 'GeoWaypoint', 'feature_id', 'order'=>'waypoint_no'),
			'waypointsCount'=>array(self::STAT, 'GeoWaypoint', 'feature_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'feature_type' => 'Τύπος',
			'group_id' => 'Ομάδα',
			'title' => 'Τίτλος',
			'description' => 'Περιγραφή',
			'geo_long' => 'Γεογραφικό μήκος',
			'geo_lat' => 'Γεωγραφικό πλάτος',
			'active' => 'Δημόσιο',
			'author_id' => 'Χρήστης',
			'create_time' => 'Δημιουργία',
			'update_time' => 'Τελ. διόρθωση',
		);
	}

	public static function getFeatureTypeOptions()
	{
		return array(
			'point'=>'Σημείο',
			'route'=>'Διαδρομή',
			'area'=>'Περιοχή',
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
		$criteria->compare('feature_type',$this->feature_type,true);
		$criteria->compare('group_id',$this->group_id);
		$criteria->compare('title',$this->title,true);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('geo_long',$this->geo_long,true);
		$criteria->compare('geo_lat',$this->geo_lat,true);
		$criteria->compare('active',$this->active);
		$criteria->compare('author_id',$this->author_id);
		$criteria->compare('create_time',$this->create_time,true);
		$criteria->compare('update_time',$this->update_time,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'pagination'=>array(
				'pageSize'=>20,
			),
		));
	}
	
	protected function afterConstruct()
	{
		parent::afterConstruct();
		
		$this->feature_type = 'point';
		$this->geo_lat = 37.916034;
		$this->geo_long = 22.895508;
		$this->create_time = time();
	}
	
	protected function beforeSave()
	{
		if ($this->isNewRecord)
			$this->create_time = time();
		$this->update_time = time();

		return parent::beforeSave();
	}
	
	protected function afterInsert()
	{
		// see if we have any importedWaypoints (as imported by file) and save them
		parent::afterInsert();
	}
	
	protected function afterDelete()
	{
		parent::afterDelete();
		GeoWaypoint::model()->deleteAll('feature_id='.$this->id);
	}
	
	public function toAjaxAssoc()
	{
		$ajax = array(
			'id'=>$this->id,
			'featureType'=>$this->feature_type,
			'groupId'=>$this->group_id,
			'title'=>$this->title,
			'description'=>$this->description,
			'geoLong'=>$this->geo_long,
			'geoLat'=>$this->geo_lat,
		);
		
		if ($this->waypointsCount > 0)
		{
			$ajax['waypoints'] = array();
			foreach ($this->waypoints as $waypoint)
			{
				$ajax['waypoints'][] = array(
					'id'=>$waypoint->id,
					'waypointNo'=>$waypoint->waypoint_no,
					'geoLong'=>$waypoint->geo_long,
					'geoLat'=>$waypoint->geo_lat,
				);
			}
		}
		
		return $ajax;
	}
}