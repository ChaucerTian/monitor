<?php
/**
 * Smarty plugin
 *
 */

/**
 * Smarty js compiler plugin
 * Type:     compiler<br>
 * Name:     css<br>
 * Example:  {js file="static/css/index.css"}
 * @return css with md5-version code
 */

function smarty_function_urlParam($params, $template) {
    $allFilters = $params['filters'];
    $currentFilter = $params['current'];
    $key = $params['key'];
    $value = $params['value'];

    $currentFilter[$key] = $value;

    $result = '';

    foreach ($allFilters as $filter) {
        if ($currentFilter[$filter['key']] !== $filter['default']) {
            $result .= $filter['key'] . '=' . $currentFilter[$filter['key']] . '&';
        }
    }

    if (strlen($result) > 1) {
        $result = substr($result, 0, -1);
    }

    return $result;
}



