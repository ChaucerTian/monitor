<?php
namespace common\filters\auth;

use yii\filters\auth\AuthMethod;
/**
 *
 * Author: Chaucer Tian (tqignshuai@gmail.com)
 * Date: 2016/4/27
 */

class HttpBasicParamAuth extends AuthMethod {
    /**
     * @var string the param name for passing user name
     */
    public $usernameParam = 'user';
    /**
     * @var string the param name for passing user password
     */
    public $passwordParam = 'pass';
    /**
     * @var string the param name for passing user token
     */
    public $tokenParam = 'token';

    /**
     * @var callable a PHP callable thath will authenticate the user
     */
    public $auth;



    /**
     * @inheritdoc
     */
    public function authenticate($user, $request, $response) {
        $username = $request->get($this->usernameParam);
        $username = $username ? $username : $request->getBodyParam($this->usernameParam);

        $password = $request->get($this->passwordParam);
        $password = $password ? $password : $request->getBodyParam($this->passwordParam);

        $token = $request->getHeaders()->get('token');
        $token = $token ? $token : $request->get($this->tokenParam);
        $token = $token ? $token : $request->getBodyParam($this->tokenParam);

        if ($this->auth) {
            if ($username !== null || $password !== null) {
                $identity = call_user_func($this->auth, $username, $password);
                if ($identity !== null) {
                    $user->switchIdentity($identity);
                } else {
                    $this->handleFailure($response);
                }
                return $identity;
            }
        } else if ($username !== null && $token !== null) {
            $identity = $user->loginByAccessToken($token, get_class($this));
            if ($identity === null) {
                $this->handleFailure($response);
            }
            return $identity;
        }
        return null;
    }
}
