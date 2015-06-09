<?php

class TagsController extends Controller
{
	// public $layout='column2';

	public function actionIndex()
	{
		$dataProvider=new CActiveDataProvider('Tag', array(
			'criteria'=>array(
				'order'=>'t.name',
			),
			'pagination'=>array(
				'pageSize'=>200,
			),
		));
		
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}
}
