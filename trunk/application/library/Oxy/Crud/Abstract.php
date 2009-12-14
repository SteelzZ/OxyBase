<?php
/**
* This component uses resources component to create resource
* and then by using refelction adds required methods etc
*
* @category Oxy
* @package Oxy_Crud
* @author Tomas Bartkus <to.bartkus@gmail.com>
* @version 1.0
**/
abstract class Oxy_Crud_Abstract
{
    /**
     * Generator adapter
     *
     * @var Oxy_Crud_Adapter_Abstract
     */
    private $_objAdapter;

    /**
     * Set adapter
     *
     * @param Oxy_Crud_Adapter_Abstract $obj_adapter
     * @return void
     */
	public function setAdapter(Oxy_Crud_Adapter_Abstract $obj_adapter)
	{
	    $this->_objAdapter = $obj_adapter;
	}

	/**
	 * Return adapter
	 *
	 * @return Oxy_Crud_Adapter_Abstract
	 */
	public function getAdapter()
	{
	    return $this->_objAdapter;
	}

	/**
	 * Generate CRUD
	 *
	 * @param String $str_module_name
	 * @return Boolean
	 */
	public function generate()
	{
		$this->_objAdapter->crud();
	}
}
?>