<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');

// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
$db_host = getenv('OPENSHIFT_MYSQL_DB_HOST');
$db_port = getenv('OPENSHIFT_MYSQL_DB_PORT');
$db_user = getenv('OPENSHIFT_MYSQL_DB_USERNAME');
$db_pass = getenv('OPENSHIFT_MYSQL_DB_PASSWORD');
return array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name'=>'Tracy',

	// preloading 'log' component
	'preload'=>array('log'),

	// autoloading model and component classes
	'import'=>array(
		'application.models.*',
		'application.components.*',
	),

	'modules'=>array(

		'gii'=>array(
			'class'=>'system.gii.GiiModule',
			'password'=>'$w-3ng',
			// If removed, Gii defaults to localhost only. Edit carefully to taste.
			'ipFilters'=>array('*'),//,'127.0.0.1','::1'),
		),
	),

	// application components
	'components'=>array(
		'user'=>array(
			// enable cookie-based authentication
			'allowAutoLogin'=>true,
		),

		'urlManager'=>array(
			'urlFormat'=>'path',
            'showScriptName'=>false,
			'rules'=>array(
                'rawfile/<name:(\w|\/)+>.<ext:\w+>'=>'generator/rawfile',
                'file/<name:(\w|\/)+>.<ext:\w+>'=>'generator/file',
                'tetris' => 'site/tetris',
                'snake' => 'site/snake',
                'class/view/<id:\w+>'=>'class/view',
                'class/view/<id:\w+>'=>'class/view',
				'<controller:\w+>/<id:\d+>'=>'<controller>/view',
				'<controller:\w+>/<action:\w+>/<id:\d+>'=>'<controller>/<action>',
				'<controller:\w+>/<action:\w+>'=>'<controller>/<action>',
			),
		),

		'db'=>array(
			'connectionString' => "mysql:host=$db_host;port=$db_port;dbname=requisiti",
			'emulatePrepare' => true,
			'username' => $db_user,
			'password' => $db_pass,
			'charset' => 'utf8',
		),
        
		'db_redmine'=>array(
			'connectionString' => "mysql:host=$db_host;port=$db_port;dbname=redmine",
			'emulatePrepare' => true,
			'username' => $db_user,
			'password' => $db_pass,
			'charset' => 'utf8',
            'class' => 'CDbConnection',
		),
        
		'db_pdca'=>array(
			'connectionString' => "mysql:host=$db_host;port=$db_port;dbname=grafico_pdca",
			'emulatePrepare' => true,
			'username' => $db_user,
			'password' => $db_pass,
			'charset' => 'utf8',
            'class' => 'CDbConnection',
		),
        
        
		'errorHandler'=>array(
			// use 'site/error' action to display errors
			'errorAction'=>'site/error',
		),
		'log'=>array(
			'class'=>'CLogRouter',
			'routes'=>array(
				array(
					'class'=>'CFileLogRoute',
					'levels'=>'error, warning',
				),
				// uncomment the following to show log messages on web pages
				/*
				array(
					'class'=>'CWebLogRoute',
				),
				*/
			),
		),
	),

	// application-level parameters that can be accessed
	// using Yii::app()->params['paramName']
	'params'=>array(
		// this is used in contact page
		'adminEmail'=>'webmaster@example.com',
	),
	
	'theme'=> 'classic',
);
