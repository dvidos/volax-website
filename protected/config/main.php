<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');

// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
return array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name'=>'volax.gr IV',
	'language'=>'el_gr',

	// preloading 'log' component
	'preload'=>array('log'),

	// autoloading model and component classes
	'import'=>array(
		'application.models.*',
		'application.components.*',
	),

	'defaultController'=>'site',

	// application components
	'components'=>array(
		'user'=>array(
			// enable cookie-based authentication
			'class'=>'WebUser',
			'allowAutoLogin'=>true,
		),
		'db'=>array(
			//*
				'connectionString' => 'mysql:host=localhost;dbname=volax4',
				'username' => 'root',
				'password' => '',
			/*/
				'connectionString' => 'mysql:host=mysql5.internet.gr;port=3305;dbname=forthnet_volax_gr',
				'username' => 'volax_gr',
				'password' => 'Rtesnd@4s',
			// */
			'emulatePrepare' => true,
			'charset' => 'utf8',
			'tablePrefix' => 'v4_',
		),
		'errorHandler'=>array(
			// use 'site/error' action to display errors
			'errorAction'=>'site/error',
		),
		'urlManager'=>array(
			'urlFormat'=>'get', // 'path',
			'showScriptName'=>true,
			'rules'=>array(
				'post/<id:\d+>/<title:.*?>'=>'post/view',
				'category/<id:\d+>/<title:.*?>'=>'category/view',
				'posts/<tag:.*?>'=>'post/index',
				'page/<view:.*?>'=>'site/page',
				'<controller:\w+>/<action:\w+>'=>'<controller>/<action>',
			),
		),
		'log'=>array(
			'class'=>'CLogRouter',
			'routes'=>array(
				array(
					'class'=>'CFileLogRoute',
					'levels'=>'error, warning, info',
				),
			),
		),
	),

	'modules' => array(
		'admin',
		'author',
    ),
	
	// application-level parameters that can be accessed
	// using Yii::app()->params['paramName']
	'params'=>require(dirname(__FILE__).'/params.php'),
);