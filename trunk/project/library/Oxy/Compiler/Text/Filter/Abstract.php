<?php

abstract class Oxy_Compiler_Text_Filter_Abstract
{
    private $str_text = '';
    public function setText($str_text)
    {
        $this->str_text = $str_text;
    }

    public function __toString()
    {
        return __CLASS__;
    }
}