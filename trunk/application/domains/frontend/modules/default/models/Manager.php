<?php
/**
* Interface for external modules that
* will want to access some resources
*
* @category Frontend
* @package Default
* @subpackage Model
* @author Tomas Bartkus <to.bartkus@gmail.com>
**/
class Frontend_Default_Model_Manager extends Oxy_Extension_Accessor implements Oxy_Extension_Interface
{
	/**
	 *
	 */
	public function getName()
	{
		return 'frontend_default_manager';
	}

	/**
	 *
	 */
	public function useInstantly()
	{
		return false;
	}

}
?>