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
    [
        ''
    ]
);