<?php
// shared application configuration
return array(
	'basePath' => realpath(__DIR__ . '/..'),

	// application name
	'name' => 'Kotipolku – Kaikki kodistasi',

	// application language
	'language' => 'fi',

    'timeZone' => 'Europe/Helsinki',

	// path aliases
	'aliases' => array(
		'app' => 'application',
		'vendor' => realpath(__DIR__ . '/../../vendor'),
	),

	// components to preload
	'preload' => array('log', 'errorHandler'),

	// paths to import
	'import' => array(
        'app.behaviors.*',
        'app.components.*',
        'app.controllers.*',
        'app.helpers.*',
        'app.models.ar.*',
        'app.models.form.*',
		'vendor.nordsoftware.yii-audit.models.*',
	),

	// application components
	'components' => array(
        'appMessages' => array(
            'class' => 'CPhpMessageSource',
            'language' => 'fi',
        ),
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
			'class' => 'ext.coalmine.components.CoalmineErrorHandler',
			'errorAction' => 'site/error',
			'signature' => 'd9ab0f63ce2668718d1857946dc5497ccde517ef',
			'version' => '1.0.0',
			'connection' => array(
				'environmentName' => 'development',
				'enabledEnvironments' => array('production', 'staging'),
			),
		),
        'facebook' => array(
            'class' => 'ext.facebook.components.FacebookApi',
            'appId' => '482990508443117',
            'appSecret' => 'a8c2473c9644b676b95bf6828d75c3a2',
            'siteName' => 'Kotipolku',
            'namespace' => 'kotipolku',
            'locale' => 'fi_FI',
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