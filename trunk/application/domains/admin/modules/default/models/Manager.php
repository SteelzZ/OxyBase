<?php
/**
* Interface for external modules that
* will want to access some resources
*
* @category Admin
* @package Default
* @subpackage Model
* @author Tomas Bartkus <to.bartkus@gmail.com>
**/
class Admin_Default_Model_Manager implements Oxy_Extension_Interface
{
	/**
	 *
	 */
	public function getName()
	{
		return 'admin_default_manager';
	}

	/**
	 *
	 */
	public function useInstantly()
	{
		return false;
	}

	public function test()
	{
		print "yep";
	}
}
?>