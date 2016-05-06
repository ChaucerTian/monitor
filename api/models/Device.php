<?php
/**
 * Device Model
 * Author: Chaucer Tian (tqignshuai@gmail.com)
 * Date: 2016/5/6
 */

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

class Device extends ActiveRecord {

    const STATUS_NORMAL =0;

    const ALARM_STATUS_NORMAL = 0;
    /**
     * @inheritdoc
     */

    public static function tableName() {
        return 'device';
    }
}