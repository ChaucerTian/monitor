<?php
/**
 * Trend Model for point value
 * Author: Chaucer Tian (tqignshuai@gmail.com)
 * Date: 2016/5/7
 */

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

class Trend extends ActiveRecord {
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'trend';
    }
}