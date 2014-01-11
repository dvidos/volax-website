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
		$id = $_GET['id'];
		if (!$id)
			throw new CHttpException(400, 'Bad request, no category given');
		
		$condition = (Yii::app()->user->isGuest) ? 'status='.Category::STATUS_PUBLISHED : '';
		$category = Category::model()->findByPk($_GET['id'], $condition);
		
		if ($category == null)
			throw new CHttpException(404, 'Category not found');
		
		return $category;
	}
}
