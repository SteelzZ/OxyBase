<?php

interface Oxy_Acl_Record_Interface
{
	/**
	 * Return resource name
	 *
	 * @return String
	 */
	public function getResource();

	/**
	 * Return role
	 *
	 * @return String
	 */
	public function getRole();

	/**
	 * Return parent role
	 *
	 * @return String
	 */
	public function getParentRole();

	/**
	 * Return action name
	 *
	 * @return String
	 */
	public function getAction();

	/**
	 * Return if is allowed
	 */
	public function isAllowed();
}
?>