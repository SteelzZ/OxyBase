<?php

class Oxy_Form_Element_Phone extends Zend_Form_Element_Xhtml
{
    public $helper = "phoneElement";

    /**
     * Check if given value is valid
     * @param $value Value
     * @param $context Context
     */
    public function isValid($value, $context = null){
        $oldValue = $value;
        $phoneNumberFieldName = $this->getAttrib('phoneNumberName');

        if (isset($value[$phoneNumberFieldName])){
            $validationResult = parent::isValid($value[$phoneNumberFieldName], $context);

            $this->setValue($oldValue);

            return $validationResult;
        }
        else{
            return false;
        }
    }

    /**
     * Retrieve filtered element value
     *
     * @return mixed
     */
    public function getValue()
    {
        $valueFiltered = $this->_value;
        $phoneNumberFieldName  = $this->getAttrib('phoneNumberName');
        $phoneNumberFieldValue = isset($valueFiltered[$phoneNumberFieldName]) ? $valueFiltered[$phoneNumberFieldName] : '';

        $this->_filterValue($phoneNumberFieldValue, $phoneNumberFieldValue);
        $valueFiltered[$phoneNumberFieldName] = $phoneNumberFieldValue;

        return $valueFiltered;
    }

    /**
     * Initialization stuff
     */
    public function init()
    {
        // Set default attributes that is needed
        $attribs = $this->getAttribs();

        if (!isset($attribs['phoneNumberName'])){
            $this->setAttrib('phoneNumberName', 'number');
        }
        if (!isset($attribs['phoneCodeName'])){
            $this->setAttrib('phoneCodeName', 'code');
        }

    }
}

