<?php

	
class ElfinderController extends CController
{
	public function actions()
	{
		$username = Yii::app()->user->user->username;
		
		return array(
			'connector' => array(
				'class' => 'application.components.elFinder.ElFinderConnectorAction',
				'settings' => array(
					'root' => Yii::getPathOfAlias('webroot') . '/uploads/' . $username . '/',
					'URL' => Yii::app()->baseUrl . '/uploads/' . $username . '/',
					'rootAlias' => 'uploads/' . $username,
					'mimeDetect' => 'none'
				)
			),
		);
	}
}


