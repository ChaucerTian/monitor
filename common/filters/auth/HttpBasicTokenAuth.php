<?php
/**
 * filter supports authentication based on accesstoken in request body or url
 * Author: Chaucer Tian (tqignshuai@gmail.com)
 * Date: 2016/5/4
 */
namespace common\filters\auth;

use Yii;
use yii\filters\auth\AuthMethod;

class HttpBasicTokenAuth extends AuthMethod {
    /**
     * @var string the parameter name for passing the access token
     */
    public $tokenParam = 'key';

    /**
     * @inheritdoc
     */
    public function authenticate($user, $request, $response) {
        if ($request->isGet) {
            $accessToken = $request->get($this->tokenParam);
        } else {
            $accessToken = $request->getBodyParam($this->tokenParam);
        }

        if (is_string($accessToken)) {
            $identity = $user->loginByAccessToken($accessToken, get_class($this));
            if ($identity !== null) {
                return $identity;
            }
        }
        if ($accessToken !== null) {
            $this->handleFailure($response);
        }
        return null;
    }
}