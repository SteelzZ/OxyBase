<?php

interface Oxy_Acl_Role_Interface
{
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
}
?>