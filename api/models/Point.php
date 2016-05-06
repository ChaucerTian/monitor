<?php
/**
 * Point Model
 * Author: Chaucer Tian (tqignshuai@gmail.com)
 * Date: 2016/5/6
 */

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

class Point extends ActiveRecord {
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'point';
    }
}