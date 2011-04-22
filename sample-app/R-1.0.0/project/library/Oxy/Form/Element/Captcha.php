<?php

class Oxy_Form_Element_Captcha extends Zend_Form_Element_Captcha
{
    public function render(Zend_View_Interface $view = null)
    {
        $captcha    = $this->getCaptcha();
        $captcha->setName($this->getFullyQualifiedName());

        $decorators = $this->getDecorators();

        $decorator  = $captcha->getDecorator();
        if (!empty($decorator)) {
            array_unshift($decorators, $decorator);
        }

        $decorator = array('Captcha', array('captcha' => $captcha));
        array_unshift($decorators, $decorator);

        $this->setDecorators($decorators);

        $this->setValue($this->getCaptcha()->generate());
echo "||" . parent::render($view) . "||";
exit;
        return parent::render($view);
    }
}

?>