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
    'DELETE v1/project/<id:\d+>' => 'v1/project/delete',

    'POST v1/project/<projectId:\d+>/devices' => 'v1/device/create',
    'PUT v1/project/<projectId:\d+>/device/<id:\d+>' => 'v1/device/update',
    'GET v1/project/<projectId:\d+>/devices' => 'v1/device/index',
    'GET v1/project/<projectId:\d+>/device/<id:\d+>' => 'v1/device/view',
    'DELETE v1/project/<projectId:\d+>/device/<id:\d+>' => 'v1/device/delete',

    'POST v1/project/<projectId:\d+>/device/<deviceId:\d+>/datapoints' => 'v1/point/create',
    'PUT v1/project/<projectId:\d+>/device/<deviceId:\d+>/datapoint/<id:\d+>' => 'v1/point/update',
    'GET v1/project/<projectId:\d+>/device/<deviceId:\d+>/datapoint/<id:\d+>' => 'v1/point/view',
    'GET v1/project/<projectId:\d+>/device/<deviceId:\d+>/datapoint/<id:\d+>.json' => 'v1/point/trend',
    'GET v1/project/<projectId:\d+>/device/<deviceId:\d+>/datapoints' => 'v1/point/index',


    'user' => 'v1/user/country',

    '<controller:\w+>/<action:\w+>'=>'<controller>/<action>',
    '<module:\w+>/<controller:\w+>/<action:\w+>'=>'<module>/<controller>/<action>',
);