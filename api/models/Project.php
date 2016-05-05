<?php
/**
 * Project Model
 * Author: Chaucer Tian (tqignshuai@gmail.com)
 * Date: 2016/5/5
 */

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

class Project extends ActiveRecord {
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'project';
    }
}