<?php

$env_prod = strtolower(substr($_SERVER['HTTP_HOST'], -8)) == 'volax.gr';
$env_dev = !$env_prod;

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');

// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
return array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name'=>'Βωλάξ',
	'language'=>'el',

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
		'db'=>$env_prod ? 
			array(
				'connectionString' => 'mysql:host=db56.grserver.gr;port=3306;dbname=forthnet_volax_gr',
				'username' => 'volax_gr',
				'password' => 'Rtesnd@4s',
				'emulatePrepare' => true,
				'charset' => 'utf8',
				'tablePrefix' => 'v4_',
				'enableProfiling'=>true,
			) : array(
				'connectionString' => 'mysql:host=localhost;dbname=forthnet_volax_gr',
				'username' => 'volax_gr_user2',
				'password' => 'QUk?J-At8N_-59K',
				'emulatePrepare' => true,
				'charset' => 'utf8',
				'tablePrefix' => 'v4_',
				'enableProfiling'=>true,
			),
		'errorHandler'=>array(
			// use 'site/error' action to display errors
			'errorAction'=>'site/error',
		),
		'urlManager'=>array(
			//'urlFormat'=>'get', // 'path',
			'urlFormat'=>'path',
			// when setting showScriptName to false, elFinder crashes in forthnet, loading partly!
			'showScriptName'=>true,
			//'showScriptName'=>false,
			//'urlSuffix'=>'.html',
			
			'rules'=>array(
				'home'=>'site/index',
				'visitus'=>array('category/view', 'defaultParams'=>array('id'=>127, 'title'=>'Επισκευτείτε μας')),
				'terms'=>array('page/view', 'defaultParams'=>array('url_keyword'=>'terms')),
				'whoweare'=>array('page/view', 'defaultParams'=>array('url_keyword'=>'whoweare')),
				'contact'=>array('site/contact'),
				'search'=>array('site/search'),
				
				'users/<id:\d+>-<name:.*?>'=>'user/view',
				'users/<id:\d+>'           =>array('user/view', 'parsingOnly'=>true),
				'user/<id:\d+>/<name:.*?>' =>array('user/view', 'parsingOnly'=>true),
				'user/<id:\d+>'            =>array('user/view', 'parsingOnly'=>true),
				
				'posts/<id:\d+>-<title:.*?>'=>array('post/view'),
				'posts/<id:\d+>'            =>array('post/view', 'parsingOnly'=>true),
				'post/<id:\d+>/<title:.*?>' =>array('post/view', 'parsingOnly'=>true),
				'post/<id:\d+>'             =>array('post/view', 'parsingOnly'=>true),
				
				'categories/<id:\d+>-<title:.*?>'=>array('category/view'),
				'categories/<id:\d+>'            =>array('category/view', 'parsingOnly'=>true),
				'category/<id:\d+>/<title:.*?>'  =>array('category/view', 'parsingOnly'=>true),
				'category/<id:\d+>'              =>array('category/view', 'parsingOnly'=>true),
				
				'tags/<tag:.*?>'    =>array('post/list'),
				'tag/<tag:.*?>'     =>array('post/list', 'parsingOnly'=>true),
				'post/tag/<tag:.*?>'=>array('post/list', 'parsingOnly'=>true),
				
				'page/<url_keyword:.*?>' =>'page/view',
				'pages/<url_keyword:.*?>'=>array('page/view', 'parsingOnly'=>true),
				
				'<controller:\w+>/<action:\w+>'=>'<controller>/<action>',
			),
		),
		'log'=>array(
			'class'=>'CLogRouter',
			'routes'=>array(
				array(
					'class'=>'CFileLogRoute',
					'logFile'=>'application.log',
					'levels'=>'trace, debug, info, warning, error',
					'enabled'=> $env_dev,
				),
				array(
					'class'=>'CFileLogRoute',
					'logFile'=>'errors.log',
					'levels'=>'warning, error',
					'enabled'=>true,
				),
				array(
					// this one presents profile info at the end of the webpage.
					'class'=>'CProfileLogRoute',
					//'enabled'=>true,
					'enabled'=>false,
				),			
			),
		),
		
		'mailer' => array(
			'class'=>'application.components.Mailer',
			'from' => 'Volax.gr <info@volax.gr>',
			'bcc' => array(),
			'htmlFormat' => true,
			'bypass' => $env_dev,
			'disclaimer' => 
				"ΓΝΩΣΤΟΠΟΙΗΣΗ\r\n" .
				'Το περιεχόμενο αυτού του μηνύματος και των τυχόν συνημμένων σε αυτό αρχείων είναι εμπιστευτικό και απόρρητο. '.
				'Σε περίπτωση, που περιέλθει σε σας από λάθος χωρίς να είστε ο σκοπούμενος παραλήπτης, '.
				'παρακαλούμε να το διαγράψετε άμεσα από το σύστημά σας και να ειδοποιήσετε τον αποστολέα. '.
				'Η αντιγραφή, χρήση ή κοινοποίηση σε τρίτους του μηνύματος αυτού από μη κατονομαζόμενο παραλήπτη αντίκειται στο νόμο. '.
				'Δεδομένου ότι οι επικοινωνίες μέσω του διαδικτύου δεν είναι ασφαλείς, '.
				'o αποστολέας δεν ευθύνεται για οποιαδήποτε απώλεια δεδομένων ή άλλη ζημία προκύπτει από την χρήση του παρόντος μηνύματος. '
		),
		
		'textDiff'=>array(
			'class'=>'application.components.TextDiff',
		),
		'stringTools'=>array(
			'class'=>'application.components.StringTools',
		),
		'contentProcessor'=>array(
			'class'=>'application.components.ContentProcessor',
		),
		'openGraph'=>array(
			'class'=>'application.components.OpenGraph',
		),
		'geoFileConverter'=> array(
			'class'=>'application.components.GeoFileConverter',
		),
	),

	'modules' => array(
		// uncomment the following to enable the Gii tool
		'gii'=>array(
			'class'=>'system.gii.GiiModule',
			'password'=>'password',
		 	// If removed, Gii defaults to localhost only. Edit carefully to taste.
			'ipFilters'=>array('127.0.0.1','::1'),
		),
		'admin',
    ),
	
	// application-level parameters that can be accessed
	// using Yii::app()->params['paramName']
	'params'=>require(dirname(__FILE__).'/params.php'),
);
