<?php
require_once 'Oxy/Extension/Manager.php';

/**
* Base class that is responsible for
* getting requested resources
*
* @category Oxy
* @package Extension
* @author Tomas Bartkus <to.bartkus@gmail.com>
**/
abstract class Oxy_Extension_Accessor
{
	/**
	 * Return extension
	 *
	 * @param String $str_param_name
	 *
	 * @return Oxy_Extension_Interface
	 */
	public function __get($str_param_name)
	{
		return Oxy_Extension_Manager::getInstance()->getExtension($str_param_name);
	}
}