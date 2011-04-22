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
     * @var String
     */
    protected $_resourceName;

	/**
     * @return the $_str_resource_name
     */
    public function getResourceName()
    {
        return $this->_resourceName;
    }

	/**
     * @param String $str_resource_name
     */
    public function setResourceName($str_resource_name)
    {
        if(is_null($str_resource_name)){
	        throw new Oxy_Resource_Exception('Resource name can not be null!');
	    }

        $this->_resourceName = $str_resource_name;
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