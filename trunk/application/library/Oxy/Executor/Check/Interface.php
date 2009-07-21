<?php
/**
* Check interface
*
* @category Oxy
* @package Oxy_Executor
* @author Tomas Bartkus
* @version 1.0
**/
interface Oxy_Executor_Check_Interface
{
	/**
	 * Validate requirement
	 *
	 * @return Boolean
	 */
	public function validate();

	/**
	 * Get check name
	 *
	 * @return String
	 */
	public function getName();
}
?>