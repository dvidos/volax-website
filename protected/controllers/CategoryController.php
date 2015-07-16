<?php

class CategoryController extends Controller
{
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
		$id = @$_GET['id'];
		if (!$id)
			throw new CHttpException(400, 'Bad request, no id given');
		
		$condition = (Yii::app()->user->isGuest) ? 'status='.Category::STATUS_PUBLISHED : '';
		$category = Category::model()->findByPk($id, $condition);
		
		if ($category == null)
		{
			Yii::log('Requested Category id ' . @$_GET['id'] . ' not found, will fail using 404, http referer is "' . @$_SERVER['HTTP_REFERER'] . '"', 'warning');
			throw new CHttpException(404, 'Category not found');
		}
		
		return $category;
	}
}
