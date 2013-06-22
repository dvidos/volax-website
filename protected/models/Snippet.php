<?php

class Snippet  extends CActiveRecord
{
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public function tableName()
	{
		return '{{snippets}}';
	}

	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('title, content', 'required'),
			array('title, image_filename', 'length', 'max'=>100),
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

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'Id',
			'title' => 'Τίτλος',
			'image_filename' => 'Εικόνα',
			'content' => 'Περιεχόμενο',
		);
	}

	public function search()
	{
		$criteria=new CDbCriteria;

		$criteria->compare('title',$this->title, true);
		$criteria->compare('image_filename',$this->image_filename, true);
		$criteria->compare('content',$this->content, true);

		return new CActiveDataProvider('Snippet', array(
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