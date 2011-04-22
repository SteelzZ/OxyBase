<?php
require_once 'Oxy/Compiler/Text/Filter/Interface.php';
require_once 'Oxy/Compiler/Text/Filter/Abstract.php';

class Oxy_Compiler_Text_Filter_Strip_Comments extends Oxy_Compiler_Text_Filter_Abstract
    implements Oxy_Compiler_Text_Filter_Interface
{
    public function processText($str_text)
    {
        // Strip comments using tokenizer - a bit slower but it's a reliable way
        $arr_comment_tokens = array(T_COMMENT);

        if (defined('T_DOC_COMMENT'))
            $arr_comment_tokens[] = T_DOC_COMMENT;
        if (defined('T_ML_COMMENT'))
            $arr_comment_tokens[] = T_ML_COMMENT;

        $arr_tokens = token_get_all($str_text);
        $str_text  = '';

        foreach ($arr_tokens as $arr_current_token)
        {
            if (is_array($arr_current_token))
            {
                if (in_array($arr_current_token[0], $arr_comment_tokens))
                {
                    continue;
                }

                $arr_current_token = $arr_current_token[1];
            }

            $str_text .= $arr_current_token;
        }

        return $str_text;
        // Strip comments. It's not 100% safe way!!!
        // Using this striping method would affect lines like
        // echo 'It is a /* commented foo bar */!';
        //return preg_replace('!/\*.*?\*/!s', '', $str_text);
    }
}
