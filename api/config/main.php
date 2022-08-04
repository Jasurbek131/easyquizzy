<?php

$db = require(__DIR__ . '/../../config/db.php');
$params = require(__DIR__ . '/params.php');

return [
    'id' => 'basic',
    'name' => 'api',
    'timeZone' => 'Asia/Tashkent',
    'basePath' => dirname(__DIR__) . '/..',
    'bootstrap' => ['log'],
    'language' => 'uz',
    'components' => [
        'authManager' => [
            // Run migration
            // php yii migrate/up --migrationPath=@yii/rbac/migrations
            'class' => 'yii\rbac\DbManager',
            'defaultRoles' => [],
        ],
        'request' => [
            'parsers' => [
                'application/json' => 'yii\web\JsonParser',
            ]
        ],
        'user' => [
            'identityClass' => 'app\models\Users',
            'enableAutoLogin' => false,
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                    'logFile' => '@app/runtime/logs/api.log',
                ],
                [
                    'class' => 'yii\log\FileTarget',
                    'categories' => ['save'],
                    'logFile' => '@app/runtime/logs/save.log',
                    'logVars' => []
                ],
            ],
        ],
        'urlManager' => [
            'showScriptName' => false,
            'enablePrettyUrl' => true,
            'enableStrictParsing' => true,
            'rules' => [
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => [

                    ],
                    'extraPatterns' => [

                    ],
                ],
                //  '<controller:\w+>/<action:\w+>' => '<controller>/<action>',
            ],
        ],
        'db' => $db,
    ],
    'modules' => [
        'v1' => [
            'class' => 'app\api\modules\v1\Module'
        ],
    ],
    'params' => $params,
];