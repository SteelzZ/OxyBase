<?php

class Oxy_View_Helper_PhoneElement extends Zend_View_Helper_FormElement
{
    protected $html = '';
    public function phoneElement($name, $value = null, $attribs = null)
    {
        $info = $this->_getInfo($name, $value, $attribs);
        extract($info); // name, value, attribs, options, listsep, disable

        if (!isset($attribs['phoneCodes'])) {
            $attribs['phoneCodes'] = array();
        }

        // Fetch phone codes list and input field names for phone number and code
        $phoneCodesList = $attribs['phoneCodes'];
        $phoneNumber    = $attribs['phoneNumberName'];
        $phoneCode      = $attribs['phoneCodeName'];

        // Remove locally used attributes from going down the chain
        unset($attribs['phoneCodes']);
        unset($attribs['phoneNumberName']);
        unset($attribs['phoneCodeName']);

        // Get helpers for select and text elements
        $helper = new Zend_View_Helper_FormSelect();
        $helper->setView($this->view);

        $textHelper = new Zend_View_Helper_FormText();
        $textHelper->setView($this->view);

        // Get field values, if exists
        $phoneCodeValue = '';
        $phoneNumerValue = '';
        if (is_array($value)) {
            $phoneCodeValue  = (isset($value[$phoneCode])) ? $value[$phoneCode] : '';
            $phoneNumerValue = (isset($value[$phoneNumber])) ? $value[$phoneNumber] : '';
        }

        // Add specific classes to phone number and code fields
        if (!isset($attribs['class'])){
            $attribs['class'] = '';
        }
        $phoneCodeAttribs = $phoneNumberAttribs = $attribs;
        $phoneCodeAttribs['class']   .= ' phoneElementCode';
        $phoneNumberAttribs['class'] .= ' phoneElementNumber';

        $phoneCodeSelected = isset($phoneCodeAttribs['selected']) ? $phoneCodeAttribs['selected'] : '';
        $phoneCodeValue = $phoneCodeValue ? $phoneCodeValue : $phoneCodeSelected;

        $this->html .= $helper->formSelect($name . '[' . $phoneCode . ']', $phoneCodeValue, $phoneCodeAttribs, $phoneCodesList);
        $this->html .= $textHelper->formText($name . '[' . $phoneNumber . ']', $phoneNumerValue, $phoneNumberAttribs);

        return $this->html;
    }

}

