<?php

class UserController extends Controller
{
	public function actions()
	{
		return array(
			// captcha action renders the CAPTCHA image displayed on the register page
			'captcha'=>array(
				'class'=>'CCaptchaAction',
				'backColor'=>0xFFFFFF,
			),
		);
	}
	
	public function beforeAction($action)
	{
		if (!defined('CRYPT_BLOWFISH')||!CRYPT_BLOWFISH)
			throw new CHttpException(500,"This application requires that PHP was compiled with Blowfish support for crypt().");
		
		return parent::beforeAction($action);
	}
	
	public function filters()
	{
		return array('accessControl');
	}
	
	public function accessRules()
	{
		return array(
			array(
				'allow', 
				'users' => array('*'),
				'actions'=>array('view', 'register', 'captcha', 'sendConfirmationEmail', 'confirmEmail', 'login', 'forgotPassword', 'resetPassword', 'subscribe', 'unsubscribe'),
			),
			array(
				'allow',
				'users' => array('@'),
				'actions' => array('myAccount', 'logout', 'terminate', ),
			),
			array(
				'deny'
			),
		);		
	}

	
	/*
		view
		register --> email --> confirmEmail (from email)
		myAccount
		terminate
		login --> email (if not confirmed) --> confirmEmail (from email)
		forgotPassword --> email --> resetPassword
		logout
		subscribe (+through ajax)
		unsubscribe --> (from email)
		
		emails:
			- email address confirmation
			- password reset requested
	*/
	
	public function actionView($id)
	{
		$user = User::model()->findByPk($id);
		if ($user == null)
			throw new CHttpException(404,'The requested page does not exist.');
		
		$this->render('view',array('user'=>$user));
	}

	
	
	public function actionRegister()
	{
		$model = new RegisterFormModel();
		
		if (isset($_POST['RegisterFormModel']))
		{
			$model->attributes = $_POST['RegisterFormModel'];
			if ($model->validate())
			{
				// set user values
				$user = new User();
				$user->email = $model->email;
				$user->password = $user->hashPassword($model->password);
				$user->username = $user->generateUniqueUsername($model->email);
				$user->registered_at = date('Y-m-d H:i:s');
				
				if (!$user->save())
					throw new Exception('Cannot save new user, ' . var_export($user->getErrors(), true));
				
				$this->redirect(array('/user/sendConfirmationEmail', 'id'=>$user->id, 'email'=>$user->email));
			}
		}
		
		$this->render('register', array('model'=>$model));
	}
	
	
	public function actionSendConfirmationEmail($id, $email)
	{
		$user = User::model()->findByPk($id);
		if ($user == null || $user->email != $email)
			throw new CHttpException(404, 'No such user found or email mismatch');
		
		$token = $user->createEmailToken();
		$url = $this->createAbsoluteUrl('/user/confirmEmail', array('email'=>$user->email, 'token'=>$token));
		$body = $this->renderPartial('confirmEmailMessage', array('url'=>$url, 'user'=>$user), true);
		Yii::app()->mailer->send($user->email, 'Εγγραφή χρήστη', $body);
		
		$this->render('confirmEmailSent', array('user'=>$user));
	}
	
	public function actionConfirmEmail($email, $token)
	{
		$user = User::model()->findByAttributes(array('email'=>$email));
		if ($user == null)
			throw new CHttpException(404,'The requested page does not exist.');

		if (!$user->validateEmailToken($token))
		{
			$this->render('confirmEmailFailed', array('email'=>$email));
			Yii::app()->end();
		}
		
		// set email confirmed
		$user->email_confirmed = 1;
		$user->last_login_at = date('Y-m-d H:i:s');
		$user->update();
		
		// log the user in (NOTE: we do not know his cleartext password, so we cannot validate it, but we accept him)
		$identity = new UserIdentity($user->email, $user->password);
		if (!$identity->authenticateWithoutPassword())
			throw new CHttpException(500, 'Cannot authenticate fresh user!');
		Yii::app()->user->login($identity, 0);
		
		// send email to admins
		foreach (Yii::app()->params['newUserSubscribers'] as $subscriber)
		{
			Yii::app()->mailer->send($subscriber, 
				'Νέος χρήστης', 
				'<p>Ενας νέος χρήστης γράφτηκε στο volax.gr</p><p>Email: ' . $user->email . '</p>'
			);
		}
		
		// notify user
		$this->render('confirmEmailSuccess', array('user'=>$user));
	}
	
	public function actionTerminate()
	{
		if (Yii::app()->user->user == null)
			throw new CHttpException(400, 'No user logged in');
		
		$model = new TerminateFormModel();
		
		if (isset($_POST['TerminateFormModel']))
		{
			$model->attributes = $_POST['TerminateFormModel'];
			if ($model->validate())
			{
				// send email to admins
				foreach (Yii::app()->params['newUserSubscribers'] as $subscriber)
				{
					Yii::app()->mailer->send($subscriber, 
						'Διαγραφή χρήστη', 
						'<p>Ενας χρήστης τερμάτισε την εγγραφή του στο volax.gr</p><p>Email: ' . Yii::app()->user->user->email . '</p>'
					);
				}
				
				// delete user, logout, go home.
				Yii::app()->user->user->delete();
				Yii::app()->user->logout();
				$this->redirect(Yii::app()->homeUrl);
				Yii::app()->end();
			}
		}
		
		$this->render('terminate', array('model'=>$model));
	}
	
	public function actionLogin()
	{
		$model = new LoginFormModel();

		// if it is ajax validation request
		if(isset($_POST['ajax']) && $_POST['ajax']==='login-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}

		// collect user input data
		if (isset($_POST['LoginFormModel']))
		{
			$model->attributes = $_POST['LoginFormModel'];
			if ($model->validate())
			{
				$user = $model->findUser();
				if ($user == null)
					throw new CHttpException(500, 'Cannot find user!');
				
				if (!$user->email_confirmed)
				{
					$this->redirect(array('/user/sendConfirmationEmail', 'id'=>$user->id, 'email'=>$user->email));
					Yii::app()->end();
				}
				
				if ($user->is_banned)
				{
					$this->render('loginBanned', array('email'=>$user->email));
					Yii::app()->end();
				}
				
				// validate user input and redirect to the previous page if valid
				if ($model->login())
				{
					$user->last_login_at = date('Y-m-d H:i:s');
					$user->update();
					$this->redirect(Yii::app()->user->returnUrl);
				}
			}
		}
		
		// display the login form
		$this->render('login',array('model'=>$model));
	}
	
	public function actionLogout()
	{
		Yii::app()->user->logout();
		$this->redirect(Yii::app()->homeUrl);
	}
	
	public function actionMyAccount()
	{
		$user = Yii::app()->user->user;
		
		// if it is ajax validation request
		if(isset($_POST['ajax']) && $_POST['ajax']==='my-account-form')
		{
			echo CActiveForm::validate($user);
			Yii::app()->end();
		}

		// collect user input data
		if (isset($_POST['User']))
		{
			$user->attributes = $_POST['User'];
			
			// validate user input and redirect to the previous page if valid
			if ($user->validate())
			{
				// save, set flash message
				$user->save();
			}
		}
		
		// display the login form
		$this->render('myAccount',array('user'=>$user));
	}
	
	public function actionForgotPassword()
	{
		$model = new ForgotPasswordFormModel();
		
		if (isset($_POST['ForgotPasswordFormModel']))
		{
			$model->attributes = $_POST['ForgotPasswordFormModel'];
			if ($model->validate())
			{
				$user = User::model()->findByAttributes(array('email'=>$model->email));
				if ($user == null)
					throw new CHttpException(500, 'User not found');
				
				$token = $user->createEmailToken();
				$url = $this->createAbsoluteUrl('/user/resetPassword', array('email'=>$user->email, 'token'=>$token));
				$title = 'Απώλεια κωδικού πρόσβασης';
				$body = $this->renderPartial('forgotPasswordMessage', array('url'=>$url, 'user'=>$user), true);
				Yii::app()->mailer->send($user->email, $title, $body);
				
				$this->render('forgotPasswordMessageSent', array('user'=>$user));
				Yii::app()->end();
			}
		}
		
		$this->render('forgotPassword', array('model'=>$model));
	}
	
	public function actionResetPassword($email, $token)
	{
		$user = User::model()->findByAttributes(array('email'=>$email));
		if ($user == null)
			throw new CHttpException(404,'The requested page does not exist.');

		if (!$user->validateEmailToken($token))
		{
			$this->render('resetPasswordFailed', array('email'=>$email));
			Yii::app()->end();
		}

		// log the user in, in this special occasion (without password), and redirect to myAccount...
		$identity = new UserIdentity($user->email, $user->password);
		if (!$identity->authenticateWithoutPassword())
			throw new CHttpException(500, 'Cannot authenticate known user!');
		Yii::app()->user->login($identity, 0);
		
		Yii::app()->user->setFlash('changePassword','Μπορείτε να αλλάξετε τον κωδικό πρόσβασής σας');
		$this->redirect(array('/user/myAccount'));
	}
}
