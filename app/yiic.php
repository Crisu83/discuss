<?php

$debugger = __DIR__ . '/../vendor/crisu83/yii-debug/helpers/Debugger.php';

require_once($debugger);

Debugger::init(__DIR__ . '/../app/runtime/debug');

$yii = __DIR__ . '/../vendor/yiisoft/yii/framework/' . (YII_DEBUG ? 'yii.php' : 'yiilite.php');
$builder = __DIR__ . '/../vendor/crisu83/yii-configbuilder/helpers/ConfigBuilder.php';

defined('STDIN') or define('STDIN', fopen('php://stdin', 'r'));
defined('YII_DEBUG') or define('YII_DEBUG',true);

require_once($yii);
require_once($builder);

$config = ConfigBuilder::buildForEnv(array(
	__DIR__ . '/../app/config/main.php',
	__DIR__ . '/../app/config/console.php',
	__DIR__ . '/../app/config/environments/{environment}.php',
	__DIR__ . '/../app/config/local.php',
), __DIR__ . '/../app/runtime/environment');


$app = Yii::createConsoleApplication($config);
$app->commandRunner->addCommands(YII_PATH . '/cli/commands');
$app->run();

