<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');

// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
return array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name'=>'volax.gr',
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
		
		'mailer' => array(
			'class'=>'application.components.Mailer',
			'from' => 'info@volax.gr',
			'bcc' => array(),
			'htmlFormat' => true,
			'disclaimer' => 
				'Email Disclaimer' . "\r\n" .
				'The information in this email is confidential and is intended solely for the addressee(s). ' .
				'If you have received this transmission in error, and you are not an intended recipient, be aware that any disclosure, copying, distribution or use of this transmission or its contents is prohibited. '.
				'Furthermore, you are kindly requested to delete the message from your system immediately.' . "\r\n" .
				'Internet communications are not secure and therefore the sender does not accept legal responsibility for the contents of this message and for any damage whatsoever caused by viruses being transmitted.' . "\r\n" .
				"\r\n" . 
				'Thank You,' . "\r\n" .
				'Your Volax Website publishing platform' . "\r\n",
		),
		
		'differer' => array(
			'class'=>'application.components.Differer',
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