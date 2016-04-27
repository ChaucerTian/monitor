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
    'GET user' => 'v1/user/country',
//    [
//        'v1.0/user/token',
//        'pattern' => 'v1.0/user/key/username/<username:\w+>/pass/<pass:\w+>/',
//    ],
    '<controller:\w+>/<action:\w+>'=>'<controller>/<action>',
    '<module:\w+>/<controller:\w+>/<action:\w+>'=>'<module>/<controller>/<action>',
);