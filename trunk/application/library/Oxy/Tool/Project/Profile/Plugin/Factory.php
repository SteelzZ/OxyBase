<?php
/**
* Plugins factory
*
* @category Oxy
* @package Oxy_Tool
* @subpackage Project
* @author Tomas Bartkus <to.bartkus@gmail.com>
* @version 1.0
**/
final class Oxy_Tool_Project_Profile_Plugin_Factory
{
	/**
	 * Load plugin
	 *
	 * @param String $str_name
	 * @param DOMDocument $obj_profile
	 * @return Oxy_Tool_Project_Profile_Plugin_Abstract
	 */
	public static function load($str_name = '', DOMDocument $obj_profile)
	{
	    $obj_plugin = Oxy_Tool_Project_Profile_Plugin_Manager::getInstance()->getPlugin($str_name);

	    if($obj_plugin === false)
	    {
	        throw new Oxy_Tool_Project_Profile_Plugin_Exception('Plugin not found!');
	    }

	    $obj_plugin->setProfile($obj_profile);

	    return $obj_plugin;
	}
}
?>