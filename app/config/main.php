<?php
// shared application configuration
return array(
	'basePath' => realpath(__DIR__ . '/..'),

	// application name
	'name' => 'Keskustelu',

	// application language
	'language' => 'fi',

	// path aliases
	'aliases' => array(
		'app' => 'application',
		'vendor' => '../vendor',
	),

	// components to preload
	'preload' => array('log'),

	// paths to import
	'import' => array(
		'application.helpers.*',
		'application.models.ar.*',
		'application.models.form.*',
		'application.components.*',
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
            'class' => 'ext.bbcode.BBCodeParser',
            'customToHtml'=>array(
                array('/:\)/'=>'<img src="images/emoticons/emotion-happy.png" />'),
                array('/:\(/'=>'<img src="images/emoticons/emotion-unhappy.png" />'),
                array('/;\)/'=>'<img src="images/emoticons/emotion-wink.png" />'),
                array('/:P/'=>'<img src="images/emoticons/emotion-tongue.png" />'),
                array('/X\)/'=>'<img src="images/emoticons/emotion-waii.png" />'),
                array('/:D/'=>'<img src="images/emoticons/emotion-smile.png" />'),
                array('/XD/'=>'<img src="images/emoticons/emotion-grin.png" />'),
                array('/:E/'=>'<img src="images/emoticons/emotion-evilgrin.png" />'),
                array('/:O/'=>'<img src="images/emoticons/emotion-surprised.png" />'),
            ),
            'customFromHtml'=>array(
                array('/\<img src="(.*?)emotion-happy.png" \/\>/'=>':)'),
                array('/\<img src="(.*?)emotion-unhappy.png" \/\>/'=>':('),
                array('/\<img src="(.*?)emotion-wink.png" \/\>/'=>';)'),
                array('/\<img src="(.*?)emotion-tongue.png" \/\>/'=>';P'),
                array('/\<img src="(.*?)emotion-waii.png" \/\>/'=>'X)'),
                array('/\<img src="(.*?)emotion-smile.png" \/\>/'=>':D'),
                array('/\<img src="(.*?)emotion-grin.png" \/\>/'=>'XD'),
                array('/\<img src="(.*?)emotion-evilgrin.png" \/\>/'=>':E'),
                array('/\<img src="(.*?)emotion-surprised.png" \/\>/'=>':O'),
            ),
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
				// uncomment the following to show log messages on web pages
				/*
				array(
					'class'=>'CWebLogRoute',
				),
				*/
			),
		),
	),

	// application parameters
	'params' => array(
		'adminEmail' => 'webmaster@example.com',
	),
);