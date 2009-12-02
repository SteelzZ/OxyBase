<?php
require_once 'Oxy/Compiler/Text/Filter/Interface.php';
require_once 'Oxy/Compiler/Text/Filter/Abstract.php';

class Oxy_Compiler_Text_Filter_Strip_Includes extends Oxy_Compiler_Text_Filter_Abstract
    implements Oxy_Compiler_Text_Filter_Interface
{
    public function processText($str_text)
    {
        // Comment out include statements
        return preg_replace('/^(\s*)(require(_once)?)/m', "$1//$2", $str_text);
        //return preg_replace('/^(\s*)(require(_once)?|include(_once)?)/m', "$1//$2", $str_text);
        //return preg_replace('/(require_once)/', "//$1", $str_text);
    }
}