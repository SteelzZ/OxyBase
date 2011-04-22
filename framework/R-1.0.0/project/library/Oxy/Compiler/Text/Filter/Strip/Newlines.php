<?php
require_once 'Oxy/Compiler/Text/Filter/Interface.php';
require_once 'Oxy/Compiler/Text/Filter/Abstract.php';

class Oxy_Compiler_Text_Filter_Strip_Newlines extends Oxy_Compiler_Text_Filter_Abstract
    implements Oxy_Compiler_Text_Filter_Interface
{
    public function processText($str_text)
    {
        // Strip empty lines
        // Warning: Causes problems with heredoc syntax!!!
        return preg_replace('/\n/', " ", $str_text);
    }
}
