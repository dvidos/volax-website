<?php

class PostController extends Controller
{
	public $layout='column1';

	
	public function actions()
	{
		return array(
			// captcha action renders the CAPTCHA image displayed on the contact page
			'captcha'=>array(
				'class'=>'CCaptchaAction',
				'backColor'=>0xFFFFFF,
			),
		);
	}	

	/**
	 * Displays a particular model.
	 */
	public function actionView()
	{
		$post=$this->loadModel();
		$comment=$this->newComment($post);
		if (!Yii::app()->user->isGuest)
		{
			$comment->author = Yii::app()->user->user->fullname;
			$comment->email = Yii::app()->user->user->email;
		}

		$this->render('view',array(
			'model'=>$post,
			'comment'=>$comment,
		));
	}

	public function actionTags()
	{
		// do not show tags beginning with parenthesis
		$tags = Tag::model()->findAll(array(
			'order'=>'name',
			'condition'=>'name NOT LIKE \'(%\'',
		));
		$this->render('tags', array(
			'tags'=>$tags,
		));
	}
	
	
	/**
	 * Lists all models.
	 */
	public function actionList()
	{
		$criteria=new CDbCriteria(array(
			'condition'=>'status='.Post::STATUS_PUBLISHED,
			'order'=>'create_time DESC',
			'with'=>'commentCount',
		));
		if(isset($_GET['tag']))
			$criteria->addSearchCondition('tags',$_GET['tag']);

		$dataProvider=new CActiveDataProvider('Post', array(
			'pagination'=>array(
				'pageSize'=>Yii::app()->params['postsPerPage'],
			),
			'criteria'=>$criteria,
		));

		$this->render('list',array(
			'dataProvider'=>$dataProvider,
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
				$condition='status='.Post::STATUS_PUBLISHED.' OR status='.Post::STATUS_ARCHIVED;
			else
				$condition='';
			$model=Post::model()->findByPk($_GET['id'], $condition);
		}
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
			
		return $model;
	}
	
	

	/**
	 * Creates a new comment.
	 * This method attempts to create a new comment based on the user input.
	 * If the comment is successfully created, the browser will be redirected
	 * to show the created comment.
	 * @param Post the post that the new comment belongs to
	 * @return Comment the comment instance
	 */
	protected function newComment($post)
	{
		$comment=new Comment;
		if(isset($_POST['ajax']) && $_POST['ajax']==='comment-form')
		{
			echo CActiveForm::validate($comment);
			Yii::app()->end();
		}
		if(isset($_POST['Comment']))
		{
			$comment->attributes=$_POST['Comment'];
			if($post->addComment($comment))
			{
				$comment->notifyEmailSubscribers();
				$comment->notifyAuthor();
				if($comment->status==Comment::STATUS_PENDING)
					Yii::app()->user->setFlash('commentSubmitted','Ευχαριστούμε για το σχόλιό σας. Θα εμφανιστεί μόλις εγκριθεί.');
				$this->refresh();
			}
		}
		return $comment;
	}
}
