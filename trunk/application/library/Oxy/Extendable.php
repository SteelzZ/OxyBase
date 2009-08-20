<?php
require_once 'Oxy/Extension/Manager.php';

abstract class Oxy_Extendable
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
?>