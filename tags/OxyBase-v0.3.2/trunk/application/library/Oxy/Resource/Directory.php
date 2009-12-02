<?php
/**
* Directory resources
*
* @category Oxy
* @package Oxy_Resource
* @author Tomas Bartkus
**/
class Oxy_Resource_Directory extends Oxy_Resource_Abstract
{
	/**
     * Create new directory
     *
     *  @return Boolean
     */
    public function create()
    {
        try
        {
            mkdir($this->getResourceName());
            return true;
        }
        catch(Exception $ex)
        {
            return false;
        }
    }
}