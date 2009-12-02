<?php
require_once 'Oxy/Compiler/Text/Filter/Interface.php';
require_once 'Oxy/Compiler/Text/Filter/Abstract.php';

class Oxy_Compiler_Text_Filter_Strip_Whitespace extends Oxy_Compiler_Text_Filter_Abstract
    implements Oxy_Compiler_Text_Filter_Interface
{
    public function processText($str_text)
    {
        // Strip all white spaces at the begining of the line
        return preg_replace('/^[ ]{2,}/m', '', $str_text);
    }
}
