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
     * @return Boolean
     */
    abstract public function crud();
}