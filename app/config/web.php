<?php
// web application configuration
return array(
	// path aliases
	'aliases' => array(
		'bootstrap' => 'vendor.crisu83.yiistrap',
		'yiiwheels' => 'vendor.2amigos.yiiwheels',
	),

    // paths to import
    'import' => array(
        'bootstrap.helpers.*',
        'bootstrap.widgets.*',
        'app.widgets.*',
        'vendor.nordsoftware.yii-audit.behaviors.*',
    ),

	// application behaviors
	'behaviors' => array(
		// uncomment this if your application is multilingual
		/*
        'multilingual' => array(
			'class' => 'vendor.crisu83.yii-multilingual.behaviors.MlApplicationBehavior',
			'languages' => array( // enabled languages (locale => language)
				'fi' => 'Suomi',
			),
		),
		*/
	),

	// controllers mappings
	'controllerMap' => array(
		'email' => array('class' => 'vendor.nordsoftware.yii-emailer.controllers.EmailController'),
		'image' => array('class' => 'vendor.crisu83.yii-image.controllers.ImageController'),
	),

	// application modules
	'modules' => array(
		// uncomment the following to enable the auth module
		/*
		'auth' => array(
			'class' => 'vendor.crisu83.yii-auth.AuthModule',
		),
		*/
        'discuss' => array(
            'class' => 'app.modules.discuss.DiscussModule',
        ),
	),

	// application components
	'components' => array(
		// uncomment the following if you enable the auth module
		/*
		'authManager'=>array(
			'class'=>'auth.components.CachedDbAuthManager',
			'itemTable'=>'authitem',
			'itemChildTable'=>'authitemchild',
			'assignmentTable'=>'authassignment',
			'behaviors'=>array(
				'auth'=>array(
					'class'=>'auth.components.AuthBehavior',
					'admins'=>array('admin'),
				),
			),
		),
		*/
		'bootstrap' => array(
			'class' => 'bootstrap.components.TbApi',
		),
        'clientScript' => array(
            'class' => 'ClientScript',
        ),
		// uncomment the following to enable the image extension
		/*
		'image' => array(
			'class' => 'vendor.crisu83.yii-image.components.ImageManager',
			'versions' => array(
			),
		),
		*/
		'log' => array(
			'routes' => array(
				array(
					'class' => 'vendor.malyshev.yii-debug-toolbar.yii-debug-toolbar.YiiDebugToolbarRoute',
					'ipFilters' => array('127.0.0.1', '::1'),
				),
			),
		),
		'urlManager' => array(
			//'class' => 'vendor.crisu83.yii-multilingual.components.MlUrlManager',
			'urlFormat' => 'path',
			'showScriptName' => false,
			'rules' => array(
				// language rules, uncomment the following if your application is multilingual
				//'<lang:([a-z]{2}(?:_[a-z]{2})?)>/<route:[\w\/]+>'=>'<route>',
				// seo rules
                'keskustelu.html' => 'room/index',
                'keskustelu/<name>-<id:\d+>.html' => 'room/view',
                'keskustelu/<room>/<name>-<id:\d+>.html' => 'thread/view',
                '<controller:\w+>/<name>-<id:\d+>.html'=>'<controller>/view',
				// default rules
				'<controller:\w+>/<id:\d+>' => '<controller>/view',
				'<controller:\w+>/<action:\w+>/<id:\d+>' => '<controller>/<action>',
				'<controller:\w+>/<action:\w+>' => '<controller>/<action>',
			),
		),
		'user' => array(
			'class'=>'WebUser',
			'allowAutoLogin' => true,
		),
		'errorHandler' => array(
			'errorAction' => 'site/error',
		),
	),
);