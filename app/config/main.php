<?php
// shared application configuration
return array(
	'basePath' => realpath(__DIR__ . '/..'),

	// application name
	'name' => 'Keskustelu',

	// application language
	//'language' => 'fi',

	// path aliases
	'aliases' => array(
		'app' => 'application',
		'vendor' => '../vendor',
	),

	// components to preload
	'preload' => array('log'),

	// paths to import
	'import' => array(
        'app.behaviors.*',
        'app.components.*',
        'app.helpers.*',
        'app.models.ar.*',
        'app.models.form.*',
		'vendor.nordsoftware.yii-audit.models.*',
	),

	// application components
	'components' => array(
		// uncomment the following to enable the email extension
		/*
		'emailer' => array(
			'class' => 'vendor.nordsoftware.yii-emailer.components.Emailer',
			'defaultLayout' => 'application.views.layouts.email',
			'data' => array(
				'h1Style' => 'font-size: 38.5px; line-height: 40px; margin: 0 0 10px; font-weight: bold; text-rendering: optimizelegibility;',
				'linkStyle' => 'color: #0088CC; text-decoration: none',
			),
			'templates' => array(
			),
		),
		*/
        'bbcodeParser' => array(
            'class' => 'ext.bbcode.components.BBCodeParser',
        ),
		'db' => array(
			'connectionString' => 'mysql:host=localhost;dbname=discuss',
			'username' => 'root',
			'password' => '',
			'charset' => 'utf8',
		),
		'errorHandler' => array(
			'errorAction' => 'site/error',
		),
		'fileManager' => array(
			'class' => 'vendor.crisu83.yii-filemanager.components.FileManager',
		),
        'format' => array(
            'class' => 'app.components.Formatter',
        ),
		'imageManager' => array(
			'class' => 'vendor.crisu83.yii-imagemanager.components.ImageManager',
		),
		'log' => array(
			'class' => 'CLogRouter',
			'routes' => array(
				array(
					'class' => 'CFileLogRoute',
					'levels' => 'error, warning',
				),
				array(
					'class' => 'CDbLogRoute',
                    'connectionID' => 'db',
                    'logTableName' => 'log',
                    'levels' => 'error, warning',
				),
			),
		),
	),

	// application parameters
	'params' => array(
		'adminEmail' => 'webmaster@example.com',
	),
);