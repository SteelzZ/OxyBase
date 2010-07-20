<?php

/**
 * Form numeric label helper
 *
 * @category Msc
 * @package Msc_View
 * @subpackage Helper
 * @author Viktoras Puzas
 */
class Oxy_View_Helper_FormDefaultText extends Zend_View_Helper_FormElement
{
    public function formDefaultText($name, $value = null, $attribs = array())
    {
        $info = $this->_getInfo($name, $value, $attribs);
        extract($info); // name, value, attribs, options, listsep, disable

        $default = isset($attribs['default']) ? $attribs['default'] : '';

        return $value ? $value : $default;
    }
}
