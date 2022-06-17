<?php

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';

$config = [
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'language' => 'uz',
    'layout' => 'plm',
    'timeZone' => 'Asia/Tashkent',
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',

    ],
    'modules' => [
        'admin' => [
            'class' => 'app\modules\admin\Module',
            'as access' => [
                'class' => 'yii\filters\AccessControl',
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ]
                ],
                'denyCallback' => function($rule, $action) {
                    Yii::$app->user->setReturnUrl(\yii\helpers\Url::canonical());
                    return Yii::$app->response->redirect(['/site/login?r='.Yii::$app->getRequest()->getUrl()]);
                },
            ],
        ],
        'hr' => [
            'class' => 'app\modules\hr\Module',
            'as access' => [
                'class' => 'yii\filters\AccessControl',
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ]
                ],
                'denyCallback' => function($rule, $action) {
                    Yii::$app->user->setReturnUrl(\yii\helpers\Url::canonical());
                    return Yii::$app->response->redirect(['/site/login?r='.Yii::$app->getRequest()->getUrl()]);
                },
            ],
        ],
        'references' => [
            'class' => 'app\modules\references\Module',
            'as access' => [
                'class' => 'yii\filters\AccessControl',
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ]
                ],
                'denyCallback' => function($rule, $action) {
                    Yii::$app->user->setReturnUrl(\yii\helpers\Url::canonical());
                    return Yii::$app->response->redirect(['/site/login?r='.Yii::$app->getRequest()->getUrl()]);
                },
            ],
        ],
        'plm' => [
            'class' => 'app\modules\plm\Module',
            'as access' => [
                'class' => 'yii\filters\AccessControl',
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ]
                ],
                'denyCallback' => function($rule, $action) {
                    Yii::$app->user->setReturnUrl(\yii\helpers\Url::canonical());
                    return Yii::$app->response->redirect(['/site/login?r='.Yii::$app->getRequest()->getUrl()]);
                },
            ],
        ],
        'treemanager' =>  [
            'class' => '\kartik\tree\Module',
        ]
    ],
    'components' => [
        'authManager' => [
            // php yii migrate/up --migrationPath=@yii/rbac/migrations
            'class' => 'yii\rbac\DbManager',
            'defaultRoles' => [],
        ],
        'i18n' => [
            'translations' => [
                'app*' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@app/messages',
                    'sourceLanguage' => 'en-US',
                    'fileMap' => [
                        'app' => 'app.php',
                        'kvtree'  => 'kvtree.php',
//                        'app/auth' => 'auth.php'
                    ],
                ],
            ],
        ],
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'sad2121a2seAczxASDZXc2asca1ascdaxcASA', // vaqtinchalik
            'baseUrl' => '',
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'app\models\Users',
            'enableAutoLogin' => true,
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure transport
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
        'db' => $db,
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'class' => 'app\widgets\MultiLang\components\UrlManager',
            'languages' => ['uz','ru'],
            'ignoreLanguageUrlPatterns' => [
                '#^api/#' => '#^api/#',
            ],
            'enableLanguageDetection' => true,
            'enableDefaultLanguageUrlCode' => true,
            'rules' => [
                'plm/plm-documents/<action>/<slug:\w+>/<id:\d+>' => 'plm/plm-documents/<action>',
                'plm/plm-documents/<action>/<slug:\w+>' => 'plm/plm-documents/<action>',

                '<controller:\w+>/<action:\w+>/' => '<controller>/<action>',
                '' => 'site/index',
            ],
        ],
        'telegram' => [
            'class' => 'aki\telegram\Telegram',
            'botToken' => '5268528798:AAErwKMllti1zmkTRQ34FUros2PSOwF4TCo',
        ]
    ],
    'params' => $params,
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'app\components\CustomGii\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];
}

return $config;
