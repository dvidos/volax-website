<?php

class PostsController extends Controller
{
	public function actionIndex()
	{
		$model=new Post('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Post']))
			$model->attributes=$_GET['Post'];

		$this->render('index',array(
			'model'=>$model,
		));
	}

	public function actionCreate()
	{
		$model = new Post;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Post']))
		{
			$model->attributes = $_POST['Post'];
			if($model->save())
			{
				$model->notifyEmailSubscribers(true);
				Yii::app()->user->setFlash('postSaved','Η ανάρτηση αποθηκεύτηκε: ' . CHtml::encode($model->title));
				if (isset($_POST['saveAndStay']))
					$this->redirect(array('update', 'id'=>$model->id));
				else
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

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Post']))
		{
			$model->attributes = $_POST['Post'];
			if($model->save())
			{
				$model->notifyEmailSubscribers(false);
				Yii::app()->user->setFlash('postSaved','Η ανάρτηση αποθηκεύτηκε: ' . CHtml::encode($model->title));
				if (isset($_POST['saveAndStay']))
					$this->redirect(array('update', 'id'=>$id));
				else
					$this->redirect(array('index'));
			}
		}

		$this->render('update',array(
			'model'=>$model,
		));
	}

	public function actionHistory($id, $revision_no = 0)
	{
		$model = $this->loadModel($id);

		if ($revision_no == 0)
		{
			$this->render('history',array(
				'model'=>$model,
			));
		}
		else
		{
			$revision = PostRevision::model()->findByAttributes(array('post_id'=>$id, 'revision_no'=>$revision_no));
			if ($revision == null)
				throw new CHttpException(404);
			
			$this->render('historyRevision',array(
				'revision'=>$revision,
				'model'=>$model,
			));
		}
	}

	public function actionDiscuss($id)
	{
		$model = $this->loadModel($id);

		if(isset($_POST['Post']))
		{
			$model->attributes = $_POST['Post'];
			if($model->save())
			{
				$model->notifyEmailSubscribers(false);
				Yii::app()->user->setFlash('postSaved','Η ανάρτηση αποθηκεύτηκε: ' . CHtml::encode($model->title));
				if (isset($_POST['saveAndStay']))
					$this->redirect(array('update', 'id'=>$id));
				else
					$this->redirect(array('index'));
			}
		}

		$this->render('discuss',array(
			'model'=>$model,
		));
	}

	public function actionInfo($id)
	{
		$model = $this->loadModel($id);

		$this->render('info',array(
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
		$model=Post::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='post-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
	
	/**
	 * Suggests tags based on the current user input.
	 * This is called via AJAX when the user is entering the tags input.
	 */
	public function actionSuggestTags()
	{
		if(isset($_GET['q']) && ($keyword=trim($_GET['q']))!=='')
		{
			$tags=Tag::model()->suggestTags($keyword);
			if($tags!==array())
				echo implode("\n",$tags);
		}
	}
	
	
}
