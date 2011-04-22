<?php
require_once 'Oxy/Compiler/Text/Filter/Strip/Comments.php';
require_once 'Oxy/Compiler/Text/Filter/Strip/Emptynewlines.php';
require_once 'Oxy/Compiler/Text/Filter/Strip/Newlines.php';
require_once 'Oxy/Compiler/Text/Filter/Strip/Starttag.php';
require_once 'Oxy/Compiler/Text/Filter/Strip/Endtag.php';
require_once 'Oxy/Compiler/Text/Filter/Strip/Includes.php';
require_once 'Oxy/Compiler/Text/Filter/Strip/Whitespace.php';

class Oxy_Compiler_Text_Filter_Manager
{
    private $arr_filters = array();

    public function addFilter(Oxy_Compiler_Text_Filter_Interface $obj_filter)
    {
        $this->arr_filters[] = $obj_filter;
    }

    public function addFilters($arr_filters)
    {

        if (!$this->filtersListValid($arr_filters))
        {
            return false;
        }

        foreach ($arr_filters as $obj_filter)
        {
            $this->addFilter($obj_filter);
        }

        return true;
    }

    public static function filtersListValid($arr_filters)
    {
        return (is_array($arr_filters) && count($arr_filters) > 0);
    }

    public function clearFilters()
    {
        $this->arr_filters = array();
    }

    public function process($str_text)
    {
        if (empty($this->arr_filters))
        {
            return $str_text;
        }

        foreach ($this->arr_filters as $obj_filter)
        {
            $str_text = $obj_filter->processText($str_text);
        }

        return $str_text;
    }

    public static function applyFilters($str_text, $arr_filters = array())
    {
        if (empty($arr_filters))
        {
            return $str_text;
        }

        foreach ($arr_filters as $obj_filter)
        {
            if ($obj_filter instanceof Oxy_Compiler_Text_Filter_Interface)
            {
                $str_text = $obj_filter->processText($str_text);
            }
        }

        return $str_text;
    }
}