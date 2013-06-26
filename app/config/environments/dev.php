<?php
// development environment configuration
return array(
	'modules' => array(
		'gii' => array(
			'class' => 'system.gii.GiiModule',
			'password' => 'yiiapp',
			'ipFilters' => array('127.0.0.1','::1'),
            'generatorPaths' => array('app.gii'),
		),
	),
	'components' => array(
		'db' => array(
			'enableProfiling' => true,
			'enableParamLogging' => true,
		),
	),
);
