<?php
/**
* Base transformation class
*
* @category Oxy
* @package Oxy_Crud
* @author Tomas Bartkus <to.bartkus@gmail.com>
* @version 1.0
**/
abstract class Oxy_Crud_Adapter_Abstract
{
    /**
     * Generate CRUD
     *
     * @param String $str_module_name
     *
     * @return Boolean
     */
    abstract public function crud($str_module_name);
}