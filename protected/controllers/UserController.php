<?php

class UserController extends Controller
{
	public $layout='column1';

	/**
	 * Displays a particular model.
	 */
	public function actionView()
	{
		$model=$this->loadModel();

		$this->render('view',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 */
	public function loadModel()
	{
		$model = null;
		if(isset($_GET['id']))
		{
			$model = User::model()->findByAttributes(array('id'=>$_GET['id']));
		}
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
			
		return $model;
	}
	
}
