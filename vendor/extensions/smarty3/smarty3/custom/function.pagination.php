<?php
/**
 * Smarty plugin
 *
 */

/**
 * Smarty pagination  plugin
 */

function smarty_function_pagination($params, $template) {
    $varName = $params['var'];
    $totalPage = $params['totalPage'];
    $currentPage = $params['currentPage'];
    $pagination = array(
        'firstPage' => 1,
        'lastPage' => $totalPage,
        'totalPage' => $totalPage,
        'currentPage' => $currentPage,
        'pages' => array(),
        'prevPage' => ($currentPage > 1) ? $currentPage - 1 : 1,
        'nextPage' => ($currentPage < $totalPage) ? $currentPage + 1 : $totalPage,
    );

    $startPage = $currentPage - 2;
    if ($currentPage + 2 > $totalPage) {
        $startPage -= ($currentPage + 2 - $totalPage);
    }
    if ($startPage < 1) {
        $startPage = 1;
    }
    $endPage = $startPage + 4;
    if ($endPage > $totalPage) {
        $endPage = $totalPage;
    }

    if ($startPage > 1) {
        $pagination['ellipsisStart'] = true;
    }
    if ($endPage < $totalPage) {
        $pagination['ellipsisEnd'] = true;
    }

    for ($i = $startPage; $i <= $endPage; $i += 1) {
        $pagination['pages'][] = $i; 
    }

    $template->assign($varName, $pagination);
    return '';
}



