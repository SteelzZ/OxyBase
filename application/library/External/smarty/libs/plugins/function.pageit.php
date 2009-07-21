<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */

function smarty_function_pageit($params, &$smarty)
{
	$totalEntries = $params['total'];
	$entriesInPage = $params['entries'];
	$pages = ceil($totalEntries / $entriesInPage);
	$paging = array();
	for ($i = 1; $i<$pages+1; $i++){
		$paging[$i]["page"] = $i;
		$paging[$i]["start"] = ($i-1) * $entriesInPage;
		$paging[$i]["end"] = ($i) * $entriesInPage-1;
		$paging[$i]["end"] = ceil(($paging[$i]["end"]<$totalEntries)? $paging[$i]["end"] : ($totalEntries-1));
	}
	$smarty->assign($params['assign'],$paging);
}

/* vim: set expandtab: */

?>
