<?php

// Load main config file
$main = include_once( 'main.php' );

//require_once( '' );

// Production configurations
$production = array(
	'components' => array(
		'db' =>  array(
                        'class' => 'CDbConnection',
                        'connectionString' => 'mysql:host=localhost;dbname=yiiframework',
                        'username' => 'root',
                        'password' => '89538953',
                        'charset' => 'UTF8',
                        'tablePrefix' => '',
                        'emulatePrepare' => true,
                        'enableProfiling' => true,
                        'schemaCacheID' => 'cache',
                        'schemaCachingDuration' => 3600
                ),
		'messages' => array(
							//'onMissingTranslation' => array('MissingMessages', 'load'),
			                'cachingDuration' => 3600,
	         ),		
		'log' => array(
                        'class' => 'CLogRouter',
			'routes' => array(
				// Configures Yii to email all errors and warnings to an email address
				array(
					'class' => 'LogEmailMessages',
					'levels' => 'error, warning',
					'emails' => 'vadimg88@gmail.com',
					'sentFrom' => 'support@yiiframework.co.il',
					'subject' => 'Application Error',
				),
		),


            ),
        ),
);
//merge both configurations and return them
return CMap::mergeArray($main, $production);