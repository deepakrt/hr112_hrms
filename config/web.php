<?php

$params = require(__DIR__ . '/params.php');

$config = [
    'id' => 'eMulazimApp',
    'basePath' => dirname(__DIR__),
    'defaultRoute'=>'site/login',
    'bootstrap' => ['log'],
    'modules' => [
		'inventory' => [
            'class' => 'app\modules\inventory\Module',
        ],
        'dashboard' => [
            'class' => 'app\modules\dashboard\Module',
        ],
        'admin' => [
            'class' => 'app\modules\admin\Module',
        ],
        'manageproject' => [
            'class' => 'app\modules\manageproject\Module',
        ],
        'hr' => [
            'class' => 'app\modules\hr\Module',
        ],
        'employee' => [
            'class' => 'app\modules\employee\Module',
        ],
         'fts' => [
            'class' => 'app\modules\fts\Module',
        ],
        'finance' => [
            'class' => 'app\modules\finance\Module',
        ],
        'filetracking' => [
            'class' => 'app\modules\filetracking\Module',
        ],
		'efile' => [
            'class' => 'app\modules\efile\Module',
        ],
    ],
    'components' => [
        'utility' => [
            'class' => 'app\components\Utility',
        ],
		'inventory' => [
            'class' => 'app\components\Inventoryutility',
        ],
        'finance' => [
            'class' => 'app\components\Finance',
        ],
		'projectcls' => [
            'class' => 'app\components\Projectcls',
        ],
		'pmis_Csuserlog' => [
            'class' => 'app\components\facade\Csuserlog',
        ],
        'pmis_project' => [
            'class' => 'app\components\facade\Project',
        ],
        'projects' => [
            'class' => 'app\components\Projects',
        ],
        'hr_utility' => [
            'class' => 'app\components\Hr_utility',
        ],
        'tr_utility' => [
            'class' => 'app\components\Tr_utility',
        ],
        'emp_utility' => [
            'class' => 'app\components\Emp_utility',
        ],
        
        'fts_utility' => [
            'class' => 'app\components\Fts_utility',
        ],
        
        'Dakutility' => [
            'class' => 'app\components\Dakutility',
        ],
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'F2xovdnTSEAiK_YdSSP8SS1EzuAfp-GM',
        ],
        'assetManager' => [
            'appendTimestamp' => true,
            'bundles' => [
                'yii\web\JqueryAsset' => [
                    'js'=>[]
                ],
                'yii\bootstrap\BootstrapPluginAsset' => [
                    'js'=>[]
                ],
                'yii\bootstrap\BootstrapAsset' => [
                    'css' => [],
                ],

            ],
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
         
		'user' => [
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => true,
            'authTimeout' =>  3600,
            'enableSession'=>true,
            'autoRenewCookie'=>true,
        ],
        'session' => [
			'class' => 'yii\web\Session',
			'cookieParams' => ['httponly' => true, 'lifetime' =>  3600*4],
			'timeout' =>  3600*4, //session expire
			'useCookies' => true,
		],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => true,
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'db' => require(__DIR__ . '/db.php'),
		'db2' => require(__DIR__ . '/db2.php'),
		'urlManager' => [		
	    'enablePrettyUrl' => true,
            'showScriptName' => false,
            'enableStrictParsing' => false,          
            'rules' => [
               
            ],
		],
    ],
    'params' => $params,
];

if (!YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    // $config['bootstrap'][] = 'debug';
    // $config['modules']['debug'] = [
        // 'class' => 'yii\debug\Module',
    // ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        'allowedIPs' => ['127.0.0.1','::1','10.228.11.169','10.228.11.177','10.228.10.241','10.228.7.23','10.228.11.170','10.228.11.181'],        
        //'allowedIPs' => ['127.0.0.1','::1','10.228.11.53'],        
    ];
}

return $config;
