<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */


/**
 * Smarty normalize_config modifier plugin
 *
 * Type:     modifier<br>
 * Name:     normalize_config<br>
 * Purpose:  Normalize config structure,
             as Zend_Config_Xml creates different structures when
             there's only one node or more than one node.
             See more on:
                http://framework.zend.com/issues/browse/ZF-6109
                http://framework.zend.com/issues/browse/ZF-7772
 * @author   Viktoras Puzas
 * @param array
 * @param string Which key should be used to identify if it's a multinode.
 * @return array Modified array
 */
function smarty_modifier_normalize_config($arrConfiguration, $strKeyToCheck = 0)
{
    if (isset($arrConfiguration[$strKeyToCheck]))
    {
        return $arrConfiguration;
    }
    else
    {
        return array($strKeyToCheck => $arrConfiguration);
    }
}

/* vim: set expandtab: */

?>
