<?php

class GeoWaypointsController extends Controller
{
	public function actionIndex()
	{
		$model=new GeoWaypoint('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['GeoWaypoint']))
			$model->attributes=$_GET['GeoWaypoint'];

		$this->render('index',array(
			'model'=>$model,
		));
	}

	public function actionCreate()
	{
		$model = new GeoWaypoint;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['GeoWaypoint']))
		{
			$model->attributes = $_POST['GeoWaypoint'];
			if($model->save())
			{
				Yii::app()->user->setFlash('success','Το waypoint αποθηκεύτηκε');
				$this->redirect(array('index'));
			}
		}

		$this->render('create',array(
			'model'=>$model,
		));
	}

	public function actionUpdate($id)
	{
		$model = $this->loadModel($id);

		if(isset($_POST['GeoWaypoint']))
		{
			$model->attributes = $_POST['GeoWaypoint'];
			if($model->save())
			{
				Yii::app()->user->setFlash('success','Το waypoint αποθηκεύτηκε');
				$this->redirect(array('index'));
			}
		}

		$this->render('update',array(
			'model'=>$model,
		));
	}

	public function actionDelete($id)
	{
		if(Yii::app()->request->isPostRequest)
		{
			// we only allow deletion via POST request
			$this->loadModel($id)->delete();

			// if AJAX request (triggered by deletion via admin gridview), we should not redirect the browser
			if(!isset($_GET['ajax']))
				$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('index'));
		}
		else
			throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
	}

	public function loadModel($id)
	{
		$model=GeoWaypoint::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='geowaypoint-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
