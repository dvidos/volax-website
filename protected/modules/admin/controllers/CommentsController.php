<?php

class CommentsController extends Controller
{
	// public $layout='column2';

	public function actionIndex()
	{
		$criteria = new CDbCriteria(array(
			'condition'=>'1',
			'with'=>'post',
			'order'=>'t.create_time DESC',
		));
		if (isset($_GET['status']))
			$criteria->addSearchCondition('t.status',$_GET['status']);
		if (isset($_GET['post_id']))
			$criteria->addSearchCondition('t.post_id',$_GET['post_id']);
		
		$dataProvider=new CActiveDataProvider('Comment', array(
			'criteria'=>$criteria,
		));

		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	public function actionUpdate()
	{
		$model=$this->loadModel();
		if(isset($_POST['ajax']) && $_POST['ajax']==='comment-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
		if(isset($_POST['Comment']))
		{
			$model->attributes=$_POST['Comment'];
			if($model->save())
				$this->redirect(array('index'));
		}

		$this->render('update',array(
			'model'=>$model,
		));
	}

	public function actionDelete()
	{
		if(Yii::app()->request->isPostRequest)
		{
			// we only allow deletion via POST request
			$this->loadModel()->delete();

			// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
			if(!isset($_POST['ajax']))
				$this->redirect(array('index'));
		}
		else
			throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
	}

	public function actionApprove()
	{
		if(Yii::app()->request->isPostRequest)
		{
			$comment=$this->loadModel();
			$comment->approve();
			$this->redirect(array('index'));
		}
		else
			throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
	}

	public function loadModel()
	{
		if(isset($_GET['id']))
			$model=Comment::model()->findbyPk($_GET['id']);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}
}
