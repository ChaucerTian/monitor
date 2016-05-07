<?php
namespace app\modules\v1\controllers;

use Yii;
use yii\rest\Controller;
use yii\filters\auth\CompositeAuth;
use yii\filters\auth\HttpBasicAuth;
use common\filters\auth\HttpBasicParamAuth;
use common\models\User;
use common\models\AuthToken;
use app\modules\v1\models\Country;
use yii\web\Response;
use app\modules\v1\managers\AuthTokenManager;

/**
 * USER Controller
 * Author: Chaucer Tian (tqignshuai@gmail.com)
 * Date: 2016/4/27
 */

class UserController extends Controller {

    public function behaviors() {
        $behaviors = parent::behaviors();
        $behaviors['contentNegotiator']['formats']['text/html'] = Response::FORMAT_JSON;
        $behaviors['authenticator'] = [
            'class' => HttpBasicParamAuth::className(),
            'auth' => [$this, 'auth'],
        ];

        return $behaviors;
    }

    /**
     * @param $username
     * @param $password
     * @return null|static
     */
    public function auth($username, $password) {
        $user = User::findByUsername($username);
        if ($user !== null && $user->validatePassword($password)) {
            return $user;
        }
        return null;
    }

    /**
     * @return null|static
     */
    public function actionKey() {
        $request = Yii::$app->getRequest();
        if ($request->isGet) {
            $username = $request->get($this->usernameParam);
            $username = $username ? $username : $request->getBodyParam($this->usernameParam);
            $token = AuthTokenManager::instance()->getAuthTokenByName($username);
            if (!$token) {
                $token = AuthTokenManager::instance()->addTokenByUsername($username);
            }
            return array(
                'errcode' => 0,
                'errmsg' => '',
                'key' => $token,
            );
        } else {
            Yii::$app->response->statusCode = 405;
            return array(
                'errcode' => 10001,
                'errmsg' => 'method not allowed',
                'key' => ''
            );
        }

    }
}