<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */

/**
 * Smarty {normalize_config} function plugin
 *
 * Type:     function<br>
 * Name:     normalize_config<br>
 * Date:     Nov 18, 2009<br>
 * Purpose:  Normalize config structure,<br>
             as Zend_Config_Xml creates different structures when<br>
             there's only one node or more than one node.<br>
             See more on:<br>
                http://framework.zend.com/issues/browse/ZF-6109<br>
                http://framework.zend.com/issues/browse/ZF-7772<br>
 * @author   Viktoras Puzas
 * @param    array
 * @param    string Which key should be used to identify if it's a multinode.
 * @return   array Modified array
 */
function smarty_function_normalize_config($params, &$smarty)
{
    $arrConfiguration = $params['array'];
    $strKeyToCheck    = isset($params['key']) ? $params['key'] : 0;

    if (isset($arrConfiguration[$strKeyToCheck]))
    {
        $arrFinalConfiguration = $arrConfiguration;
    }
    else
    {
        $arrFinalConfiguration = array($strKeyToCheck => $arrConfiguration);
    }

    $smarty->assign($params['assign'], $arrFinalConfiguration);
}

/* vim: set expandtab: */

?>
