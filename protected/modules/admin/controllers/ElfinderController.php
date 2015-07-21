<?php

	
class ElfinderController extends CController
{
	public function actions()
	{
		// a user only see his own folder
		$userFolder = Yii::app()->user->isAdmin ? '' : '/' . Yii::app()->user->user->username;
		
		$rootPath = Yii::getPathOfAlias('webroot') . '/uploads' . $userFolder . '/';
		$rootUrl = Yii::app()->baseUrl . '/uploads' . $userFolder . '/';
		$rootAlias = 'uploads' . $userFolder;
		
		return array(
			'connector' => array(
				'class' => 'application.components.elFinder.ElFinderConnectorAction',
				'settings' => array(
					'root' => $rootPath,
					'URL' => $rootUrl,
					'rootAlias' => $rootAlias,
					'mimeDetect' => 'none'
				)
			),
		);
	}
}


