<?php
/**
 * AuthToken Model
 * User: Chaucer Tian (tianqingshuai@gmail.com)
 * Date: 2016/4/27
 * Time: 14:32
 */

use Yii;
use yii\db\ActiveRecord;

Class AuthToken extends ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'auth_token';
    }

}