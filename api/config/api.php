<?php
/**
 * Created by PhpStorm.
 * User: thanh
 * Date: 21/6/2016
 * Time: 5:30 PM
 */
$params = require(__DIR__ . '/../../config/params.php');

$config = [
    'id' => 'api',
    'basePath' => dirname(__DIR__) . '/..',
    'bootstrap' => ['log'],
    'components' => [
        // URL Configuration for our API
        'urlManager' => [
            'enablePrettyUrl' => true,
            'enableStrictParsing' => true,
            'showScriptName' => false,
            'rules' => [
                [
                    'class' => 'fproject\rest\UrlRule',
                    'controller' => [
                        'v1/book', 'v1/translate'
                    ],
                ],
                [
                    'class' => \api\components\TokenUrlRule::className(),
                    'controller' => [
                        'v1/token',
                    ],
                ]
            ],
        ],
        'request' => [
            // Set Parser to JsonParser to accept Json in request
            'parsers' => [
                'application/json' => 'yii\web\JsonParser',
            ]
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        // Set this enable authentication in our API
        'user' => [
            'identityClass' => 'api\modules\v1\models\User',
            'enableAutoLogin' => false,
            'enableSession' => false,
            'loginUrl' => 'null'
        ],
        // Enable logging for API in a api Directory different than web directory
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                    // maintain api logs in api directory
                    'logFile' => '@api/runtime/logs/error.log'
                ],
            ],
        ],
        'db' => require(__DIR__ . '/../../config/db.php'),
    ],
    'modules' => [
        'v1' => [
            'basePath' => '@api/modules/v1',
            'class' => 'api\modules\v1\Api',
        ]
    ],
    'params' => $params,
];

return $config;