<?php
namespace frontend\controllers;

use Yii;
use yii\smarty\smarty3;
use yii\web\Controller;

class MainController extends Controller {

    public function actionIndex() {
        Yii::$app->smarty->display('main/index.html');
    }
}