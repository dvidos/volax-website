<?php

class AuthorModule extends CWebModule
{
	public function init()
	{
		// this method is called when the module is being created
		// you may place code here to customize the module or the application

		// import the module-level models and components
		$this->setImport(array(
			'author.models.*',
			'author.components.*',
		));
		
		$this->defaultController = 'dashboard';
		$this->layoutPath = Yii::getPathOfAlias('author.views.layouts');
		$this->layout = 'main';
	}

	public function beforeControllerAction($controller, $action)
	{
		if(parent::beforeControllerAction($controller, $action))
		{
			// this method is called before any module controller action is performed
			// you may place customized code here

			if (!Yii::app()->user->isAuthor)
				throw new CHttpException(403, 'You must be logged in as author');
			
			return true;
		}
		else
			return false;
	}
}
