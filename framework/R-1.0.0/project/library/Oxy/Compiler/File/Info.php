<?php


class Oxy_Compiler_File_Info
{
    private $str_filename = '';

    private $str_dirpath  = '';

    private $arr_dependencies = array();

    private $bl_is_included = false;

    private $bl_management_started = false;


    public function __construct($str_filename = null, $str_dirpath = null, $arr_dependencies = null)
    {
        if ($str_filename !== null) $this->setFilename($str_filename);
        if ($str_dirpath !== null) $this->setDirpath($str_dirpath);
        if ($arr_dependencies !== null) $this->setDependencies($arr_dependencies);
    }

    public function getFilename()
    {
        return $this->str_filename;
    }

    public function getDirpath()
    {
        return $this->str_dirpath;
    }

    public function getDependencies()
    {
        return $this->arr_dependencies;
    }

    public function getFullpath()
    {
        return $this->getDirpath() . '/' . $this->getFilename();
    }

    public function setFilename($str_filename)
    {
        $this->str_filename = $str_filename;
    }

    public function setDirpath($str_dirpath)
    {
        $this->str_dirpath = $str_dirpath;
    }

    public function setDependencies($arr_dependencies)
    {
        $this->arr_dependencies = $arr_dependencies;
    }

    public function isIncluded()
    {
        return $this->bl_is_included;
    }

    public function setIncluded($bl_is_included = true)
    {
        $this->bl_is_included = $bl_is_included;
    }

    public function isStarted()
    {
        return $this->bl_management_started;
    }

    public function setStarted($bl_is_started = true)
    {
        $this->bl_management_started = $bl_is_started;
    }

    public function dependencyExists($str_filename)
    {
        return in_array($str_filename, $this->getDependencies());
    }

    public function __toString()
    {
        return
            $this->str_dirpath . '/' . $this->str_filename . '<br />'
            . 'Dependencies: <br /><pre>'
            . print_r($this->arr_dependencies, true)
            . '</pre><br /><br />';
    }
}