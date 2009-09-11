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
	 * Initialize plugin
	 *
	 * @param DOMDocument $obj_profile
	 * @return void
	 */
	public function __construct(DOMDocument $obj_profile)
	{
		$this->obj_profile = $obj_profile;
	}

}
?>