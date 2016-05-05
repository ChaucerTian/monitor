<?php
namespace common\managers;

use yii\base\Model;
use yii\base\Component;

/**
 * Manager is the base class to handle model for controller
 * Author: Chaucer Tian (tqignshuai@gmail.com)
 * Date: 2016/4/28
 */

class Manager extends Component {
    /**
     * return instance of class
     * @param string $className
     * @return mixed
     */
    public static function instance($className = __CLASS__) {
        $instance = new $className();
        return $instance;
    }
}