<?php

class CategoryController extends Controller
{
	public $layout='column1';

	public function actionView()
	{
		$category=$this->loadModel();
		
		$this->render('view',array(
			'model'=>$category,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 */
	public function loadModel()
	{
		if(isset($_GET['id']))
		{
			if(Yii::app()->user->isGuest)
				$condition='status='.Category::STATUS_PUBLISHED;
			else
				$condition='';
			return Category::model()->findByPk($_GET['id'], $condition);
		}
		if($this->_model===null)
			throw new CHttpException(404,'The requested page does not exist.');
	}
}
