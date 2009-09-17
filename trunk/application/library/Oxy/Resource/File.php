<?php
require_once "Oxy/Resource/Abstract.php";
/**
* File resources
*
* @category Oxy
* @package Oxy_Resource
* @author Tomas Bartkus
**/
class Oxy_Resource_File extends Oxy_Resource_Abstract
{
	/**
     * Full path to template
     *
     * @var String
     */
    protected $_str_tpl_name;

	/**
     * @return String $_str_resource_name
     */
    public function getTemplateName()
    {
        return $this->_str_tpl_name;
    }

	/**
     * @param String $str_resource_name
     */
    public function setTemplateName($str_template_name = null)
    {
        $this->_str_tpl_name = $str_template_name;
    }

    /**
     * Initialize resource
     *
     * @param String $str_name
     * @param String $str_tpl
     *
     * @return void
     */
    public function __construct($str_name = null, $str_tpl = null)
    {
        $this->setResourceName($str_name);
        $this->setTemplateName($str_tpl);
    }

    /**
     * Apply template to file
     *
     * @return String
     */
    protected function applyTemplate()
    {
        $str_tpl_contents = file_get_contents($this->getTemplateName());
        file_put_contents($this->getResourceName(), $str_tpl_contents);
    }

	/**
     * Create new resource
     *
     * @return Boolean
     */
    public function create()
    {
        try
        {
            touch($this->getResourceName());
            if(!is_null($this->getTemplateName()))
            {
                $this->applyTemplate();
            }
            return true;
        }
        catch(Exception $ex)
        {
            return false;
        }
    }
}