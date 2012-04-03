<?php

// Load main config file
$main = include_once( 'main.php' );

// Development configurations
$development = array(
	'components' => array(
		'db' =>  array(
                        'class' => 'CDbConnection',
                        'connectionString' => 'mysql:host=localhost;dbname=yiiframework',
                        'username' => 'root',
                        'password' => 'root',
                        'charset' => 'UTF8',
                        'tablePrefix' => '',
                        'emulatePrepare' => true,
                        'enableProfiling' => true,
                        'schemaCacheID' => 'cache',
                        'schemaCachingDuration' => 120
                ),
		'messages' => array(
						'onMissingTranslation' => array('MissingMessages', 'load'),
		                'cachingDuration' => 0,
         ),
		'cache' => array( 'class' => 'CDummyCache' ),
		'log' => array(
                        'class' => 'CLogRouter',
                        'routes' => array(
                                array(
                                        'class'=>'CWebLogRoute',
                                        'enabled' => false,
										'levels' => 'info',
                                ),
                                array(
                                        'class'=>'CProfileLogRoute',
                                        'enabled' => false,
                                ),
								/*array(
								          'class'=>'application.extensions.yiidebugtb.XWebDebugRouter',
								          'config'=>'alignLeft, opaque, runInDebug, fixedPos, collapsed, yamlStyle',
								          'levels'=>'error, warning, trace, profile, info',
								     ),*/
								
                        ),
                ),

        ),
);
//merge both configurations and return them
return CMap::mergeArray($main, $development);