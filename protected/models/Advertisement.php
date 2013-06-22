<?php

class Advertisement extends CActiveRecord
{
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public function tableName()
	{
		return '{{advertisements}}';
	}

	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('title, customer, image_filename', 'required'),
			array('title, customer', 'length', 'max'=>128),
			array('title, customer, image_filename, target_url, notes, image_title, from_time, to_time, is_active', 'safe'),
			
			array('title, image_filename, content', 'safe', 'on'=>'search'),
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
		);
	}

	protected function afterFind()
    {
		// convert to display format (x-x-yyyy assumes european, x/x/yyyy assumes american)
        $this->from_time = date('d-m-Y', $this->from_time);
        $this->to_time = date('d-m-Y', $this->to_time);

        parent::afterFind();
    }

    protected function beforeValidate()
    {
            // convert to storage format
        $this->from_time = strtotime($this->from_time);
        $this->to_time = strtotime($this->to_time);

        return parent::beforeValidate();
    }	
	
	
	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'Id',
			'title' => 'Τίτλος',
			'customer' => 'Πελάτης',
			'image_filename' => 'Εικόνα',
			'image_title' => 'Τίτλος εικόνας',
			'target_url' => 'URL κατάληξης',
			'is_active' => 'Ενεργή',
			'from_time' => 'Από ημ/νία',
			'to_time' => 'Εως ημ/νία',
			'times_shown' => 'Εμφανίσεις',
			'times_clicked' => 'Clicks',
			'notes' => 'Σημειώσεις',
		);
	}

	public function search()
	{
		$criteria=new CDbCriteria;

		$criteria->compare('title',$this->title, true);
		$criteria->compare('customer',$this->customer, true);
		$criteria->compare('image_filename',$this->image_filename, true);
		$criteria->compare('image_title',$this->image_filename, true);
		$criteria->compare('target_url',$this->target_url, true);
		$criteria->compare('is_active',$this->is_active);
		$criteria->compare('from_time',$this->from_time);
		$criteria->compare('to_time',$this->to_time);
		$criteria->compare('times_shown',$this->times_shown);
		$criteria->compare('times_clicked',$this->times_clicked, true);
		$criteria->compare('notes',$this->notes, true);

		return new CActiveDataProvider('Advertisement', array(
			'criteria'=>$criteria,
			'sort'=>array(
				'defaultOrder'=>'title ASC',
			),
			'pagination'=>array(
				'pageSize'=>25,
			),
		));
	}
}