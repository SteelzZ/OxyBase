<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */

function smarty_function_is_allowed($params, &$smarty)
{
  $module = $params['module'];
  $action = $params['action'];
  if ($params['assign']) $smarty->assign($params['assign'],is_allowed($module, $action));
  else return is_allowed($module, $action);
}

/* vim: set expandtab: */

?>
