<?php
require_once 'Oxy/Compiler/Text/Filter/Interface.php';
require_once 'Oxy/Compiler/Text/Filter/Abstract.php';

class Oxy_Compiler_Text_Filter_Strip_Endtag extends Oxy_Compiler_Text_Filter_Abstract
    implements Oxy_Compiler_Text_Filter_Interface
{
    public function processText($str_text)
    {
        // Strip all php start tags
        return preg_replace('/^\s*(\?>)/m', '', $str_text);
    }
}
