<?php
/**
 * Smarty plugin
 *
 */

function smarty_modifier_strTimeFormat($time, $format='Y-m-d') {
    $result = $time;

    $timestamp = strtotime($time);
    
    return date($format, $timestamp);
}

