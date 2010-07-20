<?php

/**
 * Helper to generate a "submit" button. Allows to not escape value which is not allowed in Zend's FormSubmit helper.
 *
 * @category   Oxy
 * @package    Oxy_View
 * @subpackage Helper
 */
class Oxy_View_Helper_FormSubmit extends Zend_View_Helper_FormElement
{
    /**
     * Generates a 'submit' button.
     *
     * @access public
     *
     * @param string|array $name If a string, the element name.  If an
     * array, all other parameters are ignored, and the array elements
     * are extracted in place of added parameters.
     *
     * @param mixed $value The element value.
     *
     * @param array $attribs Attributes for the element tag.
     *
     * @return string The element XHTML.
     */
    public function formSubmit($name, $value = null, $attribs = null)
    {
        $info = $this->_getInfo($name, $value, $attribs);
        extract($info); // name, value, attribs, options, listsep, disable

        // check if disabled
        $disabled = '';
        if ($disable) {
            $disabled = ' disabled="disabled"';
        }

        // XHTML or HTML end tag?
        $endTag = ' />';
        if (($this->view instanceof Zend_View_Abstract) && !$this->view->doctype()->isXhtml()) {
            $endTag= '>';
        }

        // Allow to pass plain value without escaping it
        $value = ($escape) ? $this->view->escape($value) : $value;

        // Render the button.
        $xhtml = '<input type="submit"'
               . ' name="' . $this->view->escape($name) . '"'
               . ' id="' . $this->view->escape($id) . '"'
               . ' value="' . $value . '"'
               . $disabled
               . $this->_htmlAttribs($attribs)
               . $endTag;

        return $xhtml;
    }
}
