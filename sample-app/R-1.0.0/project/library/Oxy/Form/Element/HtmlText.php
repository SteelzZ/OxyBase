<?php

/**
 * Form tag element
 * @author Viktoras Puzas
 * @package elements
 */

class Oxy_Form_Element_HtmlText extends Zend_Form_Element_Xhtml
{
    public $helper = 'formDefaultText';

    public function render(Zend_View_Interface $view = null){

        $this->setDecorators(array('ViewHelper'));

        return parent::render($view);

    }
}