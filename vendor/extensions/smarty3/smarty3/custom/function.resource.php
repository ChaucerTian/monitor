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

function smarty_function_resource($params, $template) {
    $varName = $params['var'];
    $resource = $params['resource'];
    $method = $params['method'];
    $param = $params['param'];

    if (!$param) {
        $result = Resource::instance($resource . 'Resource')->$method();
    } elseif (count($param) === 1) {
        $result = Resource::instance($resource . 'Resource')->$method($param[0]);
    } else {
        $result = Resource::instance($resource . 'Resource')->$method($param);
    }
    $template->assign($varName, $result);
    return '';
}


