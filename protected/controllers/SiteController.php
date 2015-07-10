<?php

class SiteController extends Controller
{
	/**
	 * Declares class-based actions.
	 */
	public function actions()
	{
		return array(
			// captcha action renders the CAPTCHA image displayed on the contact page
			'captcha'=>array(
				'class'=>'CCaptchaAction',
				'backColor'=>0xFFFFFF,
			),
			// page action renders "static" pages stored under 'protected/views/site/pages'
			// They can be accessed via: index.php?r=site/page&view=FileName
			'page'=>array(
				'class'=>'CViewAction',
			),
		);
	}

	
	/**
	 * Homepage
	 */
	public function actionIndex()
	{
		//Yii::beginProfile('site_index');
		
		$criteria=new CDbCriteria(array(
			'condition'=>'status = '.Post::STATUS_PUBLISHED. ' AND in_home_page = 1',
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

		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
		
		//Yii::endProfile('site_index');
	}
	 
	 
	/**
	 * This is the action to handle external exceptions.
	 */
	public function actionError()
	{
	    if($error=Yii::app()->errorHandler->error)
	    {
	    	if(Yii::app()->request->isAjaxRequest)
	    		echo $error['message'];
	    	else
	        	$this->render('error', $error);
	    }
	}

	
	/**
	 * Displays the contact page
	 */
	public function actionContact()
	{
		$model = new ContactFormModel();
		if (isset($_POST['ContactFormModel']))
		{
			$model->attributes = $_POST['ContactFormModel'];
			if ($model->validate())
			{
				foreach (Yii::app()->params['contactFormReceivers'] as $receiver)
				{
					$headers="From: {$model->email}\r\nReply-To: {$model->email}";
					mail($receiver, $model->subject, $model->body, $headers);
				}
				
				Yii::app()->user->setFlash('contact','Ευχαριστούμε για την επικοινωνία. Θα σας απαντήσουμε σύντομα.');
				$this->refresh();
			}
		}
		
		$this->render('contact',array('model'=>$model));
	}
}
