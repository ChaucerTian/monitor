<?php
/**
 * Smarty plugin
 *
 */

function smarty_modifier_timeHuman($time) {
    $result = $time;

    $timestamp = strtotime($time);
    $now = time();
    $timeSpan = $now - $timestamp;
    if ($timeSpan >= 0) {
        if ($timeSpan < 60) {
            $result = $timeSpan . '秒前';
        } elseif ($timeSpan < 3600) {
            $result = round($timeSpan / 60) . '分钟前';
        } elseif ($timeSpan <= 86400) {
            $result = round($timeSpan / 3600) . '小时前';
        } else {
            $result = date('Y年m月d日', $timestamp);
            /*
            $daySpan = intval((strtotime(date('Y-m-d')) - strtotime(date('Y-m-d', $timestamp))) / 86400);
            if ($daySpan <= 1) {
                $result = '昨天';
            } else {
                $result = $daySpan . '天前';
            }*/
        }
    } else {
        $result = date('Y年m月d日', $timestamp);
    }
    return $result;
}
