<?php
return [
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
//    'defaultController' => 'main',

    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
//            'class' => YII_DEBUG ? 'system.caching.CFileCache' : 'system.caching.CApcCache',
        ],
        'smarty' => array (
            'class' => 'yii\smarty\smarty3',
        ),
    ],
];
