<?php
/**
* Lock interface
*
* @category Oxy
* @package Oxy_Executor
* @author Tomas Bartkus
* @version 1.0
**/
interface Oxy_Executor_Lock_Interface
{
	/**
	 * Lock custom action that users
	 * could not perform it while tasks are executed
	 *
	 * @return Boolean
	 */
	public function lock();

	/**
	 * Unlock the action
	 *
	 * @return Boolean
	 */
	public function unlock();

	/**
	 * Return lock name
	 *
	 * @return String
	 */
	public function getName();
}
?>