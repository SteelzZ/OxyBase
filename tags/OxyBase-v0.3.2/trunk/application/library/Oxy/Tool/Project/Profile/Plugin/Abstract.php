<?php
/**
* Oxy profile base plugin
*
* @category Oxy
* @package Oxy_Project
* @subpackage Project
* @author Tomas Bartkus <to.bartkus@gmail.com>
**/
abstract class Oxy_Tool_Project_Profile_Plugin_Abstract
{
	/**
	 * Plugin profile
	 *
	 * @var DOMDocument
	 */
	protected $_obj_operations;

	/**
	 * Set profile
	 *
	 * @param DOMDocument $obj_profile
	 */
	public function setProfile(DOMDocument $obj_profile)
	{
	    $this->_obj_operations = $obj_profile;
	}

}
?>