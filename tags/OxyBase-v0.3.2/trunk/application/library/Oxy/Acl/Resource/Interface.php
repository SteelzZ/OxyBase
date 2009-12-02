<?php

interface Oxy_Acl_Resource_Interface
{
	/**
	 * Return resource
	 *
	 * @return String
	 */
	public function getResource();

	/**
	 * Return parent resource
	 *
	 * @return String
	 */
	public function getParentResource();
}
?>