<?php
require_once 'Oxy/Tool/Project/Profile/Plugin/Factory.php';
/**
* Base profile class
* All concrete profilers should extend this one
*
* @category Oxy
* @package Oxy_Tool
* @subpackage Project
* @author Tomas Bartkus <to.bartkus@gmail.com>
**/
abstract class Oxy_Tool_Project_Profile_Abstract
{
	/**
	 * Profile object
	 *
	 * @var DOMDocument
	 */
	protected $obj_profile;

	/**
	 * Initialize profile
	 *
	 * @return void
	 */
	public function __construct($str_path_to_profile = '')
	{
		$this->loadProfile($str_path_to_profile);
	}

	/**
	 * Load profile
	 *
	 * @param String $str_path_to_profile
	 *
	 * @return void
	 */
	public function loadProfile($str_path_to_profile = '')
	{
		$this->obj_profile = new DOMDocument('1.0','utf-8');
        if (!$this->obj_profile->loadXML(file_get_contents($str_path_to_profile)))
        {
            throw new Oxy_Tool_Project_Profile_Exception("Profile XML could not be loaded!");
        }
	}

	public function getPluginProfile($str_name)
	{
		$obj_document = new DOMDocument('1.0','utf-8');
		$obj_nodes = $this->obj_profile->getElementsByTagName($str_name);
		if($obj_nodes instanceof DOMNodeList)
		{
			$obj_node = $obj_nodes->item(0);
			$obj_document->loadXML($this->obj_profile->saveXml($obj_node));
		}

		return $obj_document;
	}

	/**
	 * Get plugin
	 *
	 * @param $str_param_name
	 *
	 * @return Oxy_Tool_Project_Profile_Plugin_Abstract
	 */
	public function __get($str_param_name)
	{
		return Oxy_Tool_Project_Profile_Plugin_Factory::load($str_param_name, $this->getPluginProfile($str_param_name));
	}
}
?>