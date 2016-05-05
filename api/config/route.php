<?php
/**
 * Api route conf
 * Author: tianqingshuai
 * Date: 2016/4/27
 */

return array(
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => 'country',
        'tokens' => [
            '{id}' => '<id:\w+>'
        ]
    ],
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => 'v1/country',
        'tokens' => [
            '{id}' => '<id:\w+>'
        ]
    ],

    'GET v1/projects' => 'v1/project/index',
    'POST v1/projects' => 'v1/project/create',
    'PUT v1/project/<id:\d+>' => 'v1/project/update',
    'GET v1/project/<id:\d+>' => 'v1/project/view',
    'DELETE v1/project<id:\d+>' => 'v1/project/delete',


    'user' => 'v1/user/country',

    '<controller:\w+>/<action:\w+>'=>'<controller>/<action>',
    '<module:\w+>/<controller:\w+>/<action:\w+>'=>'<module>/<controller>/<action>',
);