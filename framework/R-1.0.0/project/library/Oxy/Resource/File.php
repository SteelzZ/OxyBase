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
     * @var string
     */
    protected $_templateName;

	/**
     * @return string
     */
    public function getTemplateName()
    {
        return $this->_templateName;
    }

	/**
     * @param string $str_resource_name
     */
    public function setTemplateName($templateName = null)
    {
        $this->_templateName = $templateName;
    }

    /**
     * Initialize resource
     *
     * @param string $name
     * @param string $tpl
     *
     * @return void
     */
    public function __construct($name = null, $tpl = null)
    {
        $this->setResourceName($name);
        $this->setTemplateName($tpl);
    }

    /**
     * Apply template to file
     *
     * @return String
     */
    protected function applyTemplate()
    {
        $tplContents = file_get_contents($this->getTemplateName());
        file_put_contents($this->getResourceName(), $tplContents);
    }

	/**
     * Create new resource
     *
     * @return Boolean
     */
    public function create()
    {
        try{
            touch($this->getResourceName());
            if(!is_null($this->getTemplateName())){
                $this->applyTemplate();
            }
            return true;
        } catch(Exception $ex) {
            return false;
        }
    }
}