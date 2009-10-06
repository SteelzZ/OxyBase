<?php
require_once 'Oxy/Tool/Project/Profile/Plugin/Factory.php';

/**
* Base profile class
* All concrete profiles should extend this one
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
	protected $_obj_profile;

	/**
	 * Plugins handler
	 *
	 * @var Oxy_Event_Handler
	 */
	protected $_obj_plugin_handler;

	/**
	 * Initialize profile
	 *
	 * @return void
	 */
	public function __construct($str_path_to_profile = '',
	                            Oxy_Event_Handler $obj_plugin_handler)
	{
		$this->loadProfile($str_path_to_profile);
		$this->_obj_plugin_handler = $obj_plugin_handler;
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
		$this->_obj_profile = new DOMDocument('1.0','utf-8');
        if (!$this->_obj_profile->loadXML(file_get_contents($str_path_to_profile)))
        {
            throw new Oxy_Tool_Project_Profile_Exception("Profile '{$str_path_to_profile}' XML could not be loaded!");
        }
	}

	/**
	 * Get plugin config
	 *
	 * @param String $str_name
	 * @return DOMDocument
	 */
	public function getPluginProfile($str_name = null)
	{
		if(is_null($str_name) || empty($str_name))
		{
			require_once 'Oxy/Tool/Project/Profile/Exception.php';
			throw new Oxy_Tool_Project_Profile_Exception('Plugin config in profile name is not set!');
		}

		$obj_document = new DOMDocument('1.0','utf-8');
		$obj_nodes = $this->_obj_profile->getElementsByTagName($str_name);
		if($obj_nodes instanceof DOMNodeList)
		{
			$obj_node = $obj_nodes->item(0);
			$obj_document->loadXML($this->_obj_profile->saveXml($obj_node));
			return $obj_document;
		}
		else
		{
			require_once 'Oxy/Tool/Project/Profile/Exception.php';
			throw new Oxy_Tool_Project_Profile_Exception('Plugin config in profile file was not found!');
		}
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
	    $obj_document = $this->getPluginProfile($str_param_name);
		return Oxy_Tool_Project_Profile_Plugin_Factory::load($str_param_name, $obj_document);
	}
}
?>