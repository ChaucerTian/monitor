<?php
namespace app\modules\v1\controllers;

use Yii;
use yii\rest\Controller;
use yii\filters\auth\CompositeAuth;
use yii\filters\auth\HttpBasicAuth;
use common\filters\auth\HttpBasicParamAuth;
use common\models\User;
use app\modules\v1\models\Country;
use yii\web\Response;

/**
 * USER Controller
 * Author: Chaucer Tian (tqignshuai@gmail.com)
 * Date: 2016/4/27
 */

class UserController extends Controller {

    public function behaviors() {
        $behaviors = parent::behaviors();
        $behaviors['contentNegotiator']['formats']['text/html'] = Response::FORMAT_JSON;
//        $behaviors['authenticator'] = [
//            'class' => HttpBasicParamAuth::className(),
//            'auth' => [$this, 'auth'],
//        ];

        return $behaviors;
    }

    public function auth($username, $password) {
        $user = User::findByUsername($username);
        if ($user !== null && $user->validatePassword($password)) {
            return $user;
        }
        return null;
    }

    public function actionKey() {
        $request = Yii::$app->getRequest();
        $username = $request->get($this->usernameParam);
        $username = $username ? $username : $request->getBodyParam($this->usernameParam);
        return User::findByUsername($username);
    }
    public function actionCountry() {
        return Country::find();
    }
}