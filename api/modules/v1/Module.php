<?php

namespace api\modules\v1;


use yii\web\Response;

class Module extends \yii\base\Module
{
    public $controllerNamespace = 'app\modules\v1\controllers';
    public function init()
    {
        parent::init();
    }
}