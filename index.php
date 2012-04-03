<?php

// change the following paths if necessary
$yii=dirname(__FILE__).'/framework/yiilite.php';
$config=dirname(__FILE__).'/protected/config/';
// Define root directory
defined('ROOT_PATH') or define('ROOT_PATH', dirname(__FILE__) . '/');

// remove the following lines when in production mode
defined('YII_DEBUG') or define('YII_DEBUG', false);

if( YII_DEBUG === true )
{
	ini_set('display_errors', true);
	error_reporting(E_ALL);
	
	// By default we use testing.com for the currently active domain
	define('CURRENT_ACTIVE_DOMAIN', 'testing.com');
}
else
{
	//ini_set('display_errors', false);
	//error_reporting(0);
	ini_set('display_errors', true);
	error_reporting(E_ALL);
	
	// On production it will be the yiiframework.co.il domain name
	define('CURRENT_ACTIVE_DOMAIN', 'yiiframework.co.il');
}

$configFile = YII_DEBUG ? 'dev.php' : 'production.php';

require_once($yii);
Yii::createWebApplication($config . $configFile)->run();