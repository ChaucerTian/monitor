<?php
namespace app\modules\v1\managers;

use common\models\AuthToken;
use Yii;
use common\managers\Manager;
use common\models\User;
/**
 * AuthToken Manager
 * Author: Chaucer Tian (tqignshuai@gmail.com)
 * Date: 2016/4/28
 */

class AuthTokenManager extends Manager {

    /**
     * @param string $className
     * @return mixed
     */
    public static function instance($className = __CLASS__) {
        $instance = new $className();
        return $instance;
    }

    /**
     * return auth token accord username
     * @param string $userName
     * @return bool
     */
    public function getAuthTokenByName($userName) {
        $command = Yii::$app->db->createCommand('SELECT at.`token` FROM `auth_token` AS at
            INNER JOIN `user` ON `user`.`id`=at.`user_id`
            WHERE `user`.`username`=:username');
        $command->bindParam(':username', $userName);
        $token = $command->queryOne();
        return $token ? $token['token'] : false;
    }

    /**
     * Update auth token according to username
     * @param $userName
     * @return bool|string
     */
    public static function updateTokenByUsername($userName) {
        $user = User::findOne(array('username' => $userName));
        if (empty($user)) {
            return false;
        }
        $token = AuthToken::findOne(array('user_id' => $user->id));

        if (empty($token)) {
            return false;
        }
        $token->token = Yii::$app->security->generateRandomString();
        if ($token->save()) {
            return $token->token;
        }
        return false;
    }


}