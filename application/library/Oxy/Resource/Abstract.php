<?php
/**
* Base class for resources
*
* @category Oxy
* @package Oxy_Resource
* @author Tomas Bartkus
**/
abstract class Oxy_Resource_Abstract
{
    /**
     * Full path to resource
     *
     * @var String
     */
    protected $_str_tpl_name;

	/**
     * @return the $_str_resource_name
     */
    public function getResourceName()
    {
        return $this->_str_tpl_name;
    }

	/**
     * @param String $str_resource_name
     */
    public function setResourceName($str_resource_name)
    {
        if(is_null($str_resource_name))
	    {
	        throw new Oxy_Resource_Exception('Resource name can not be null!');
	    }

        $this->_str_tpl_name = $str_resource_name;
    }

    /**
     * Create resource
     *
     * Concrete classes implements this method differently
     *
     * @return boolean
     */
    abstract public function create();
}