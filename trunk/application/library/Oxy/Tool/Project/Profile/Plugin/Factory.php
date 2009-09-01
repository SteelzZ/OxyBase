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
		$str_name = strtolower($str_name);
		$str_class = 'Oxy_Tool_Project_Profile_Plugin_'.ucfirst($str_name);
		$obj_plugin = null;
		try
		{
			require_once 'Oxy/Tool/Project/Profile/Plugin/'.ucfirst($str_name).'.php';
			$obj_plugin = new $str_class($obj_profile);
			return $obj_plugin;
		}
		catch(Exception $ex)
		{
			require_once 'Oxy/Tool/Project/Profile/Plugin/Exception.php';
			throw new Oxy_Tool_Project_Profile_Plugin_Exception('Plugin not found!');
		}
	}
}
?>