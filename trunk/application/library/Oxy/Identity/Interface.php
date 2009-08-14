<?php
/**
* Oxy system identity interface
*
* @category Oxy
* @package Oxy_Identity
* @author Tomas Bartkus
**/
interface Oxy_Identity_Interface
{
	/**
	 * Return role id
	 *
	 * @return String
	 */
	public function getRoleId();

	/**
	 * Return user id
	 *
	 * @return String
	 */
	public function getId();
}
?>