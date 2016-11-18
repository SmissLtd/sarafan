<?php

Yii::setAlias('@tests', dirname(__DIR__) . '/tests');

$params = require(__DIR__ . '/params.php');
$db = require(__DIR__ . '/db.php');

return [
    'id' => 'basic-console',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log', 'gii'],
    'controllerNamespace' => 'app\commands',
    'modules' => [
        'gii' => 'yii\gii\Module',
        'generators' => [
            'mongoDbModel' => 'yii\mongodb\gii\model\Generator'
        ]
    ],
    'components' => [
        'cache' => [
            'class' => 'yii\mongodb\Cache',
        ],
        'log' => [
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        // Why this is required when use mongodb ?!!!!
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=;dbname=',
        ],
        'mongodb' => $db,
    ],
    'controllerMap' => [
        'mongodb-migrate' => 'yii\mongodb\console\controllers\MigrateController'
    ],
    'params' => $params,
];
