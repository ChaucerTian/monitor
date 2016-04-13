<?php
/**
 * Smarty plugin
 *
 */

function smarty_modifier_text($string, $contentType='text') {
    $result = '';
    switch ($contentType) {
        case 'html':
            $result = Yii::app()->htmlCleanHelper->clean($string);
            break;
        default:
            $result = htmlspecialchars($string, ENT_QUOTES);
            $result = preg_replace('/\\n/', '</p><p>', $result);
    }
    return $result;
}



