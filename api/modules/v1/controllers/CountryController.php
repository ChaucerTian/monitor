<?php
namespace app\modules\v1\controllers;

use yii\rest\ActiveController;
use yii\web\Response;

/**
 * Country Controller API
 *
 */
class CountryController extends ActiveController
{
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['contentNegotiator']['formats']['text/html'] = Response::FORMAT_JSON;
        return $behaviors;
    }

    public $modelClass = 'app\modules\v1\models\Country';
    public $serializer = [
        'class' => 'yii\rest\Serializer',
    ];
}
