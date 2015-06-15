<?php

class PostRevisionsController extends Controller
{
	// public $layout = 'column2';
	
	public function actionIndex()
	{
		$model=new PostRevision('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['PostRevision']))
			$model->attributes=$_GET['PostRevision'];

		if (isset($_GET['post_id']))
			$model->post_id = $_GET['post_id'];
		if (isset($_GET['user_id']))
			$model->user_id = $_GET['user_id'];
		
		$this->render('index',array(
			'model'=>$model,
		));
	}

	public function actionView($id)
	{
		$model = $this->loadModel($id);

		$this->render('view',array(
			'model'=>$model,
		));
	}

	public function loadModel($id)
	{
		$model=PostRevision::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}
}
