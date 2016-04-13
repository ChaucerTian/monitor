<?php
/**
 * Smarty plugin
 * 将整数时间段转换为“小时：分钟：秒”
 */

/**
 * @param $duration 整数时间间隔， 单位为秒
 * @return string “小时：分钟：秒”
 */
function smarty_modifier_intDurationFormat($duration) {
    $hour = 0;
    $minute = 0;
    $second = 0;

    if ($duration >= 3600) {
        $hour = intval($duration / 3600);
        $minute = intval(($duration % 3600) / 60);
        $second = $duration % 60;
        if ($hour < 10) {
            $hour = '0' . $hour;
        }
        if ($minute < 10) {
            $minute = '0' . $minute;
        }
        if ($second < 10) {
            $second = '0' . $second;
        }
        $result = $hour . ':' . $minute . ':' . $second;
    } else if ($duration > 60) {
        $minute = intval($duration / 60);
        $second = $duration % 60;
        if ($minute < 10) {
            $minute = '0' . $minute;
        }
        if ($second < 10) {
            $second = '0' . $second;
        }
        $result = $minute . ':' . $second;
    } else {
        if ($duration < 10) {
            $duration = '00:0' . $duration;
        } else {
            $duration = '00:'.$duration;
        }
        $result = $duration;
    }

    return $result;
}

