<?php
require_once 'Zend/Tool/Framework/Provider/Abstract.php';
require_once 'Oxy/Tool/Project/Profile/Standard.php';
require_once 'Oxy/Event/Handler.php';

/**
* Oxy project provider
*
* @category Oxy
* @package Oxy_Tool
* @subpackage Project
* @author Tomas Bartkus <to.bartkus@gmail.com>
**/
class Oxy_Tool_Project_Provider_OprojectProvider extends Zend_Tool_Framework_Provider_Abstract
{
	/**
	 * Parameters delimeter
	 *
	 * @var String
	 */
	const PARAM_DELIMETER = ':';

	/**
	 * Events handler
	 *
	 * @var Oxy_Event_Handler
	 */
	protected $_obj_event_handler;

	/**
	 * Initialze
	 *
	 */
	public function __construct()
	{
	    $this->_obj_event_handler = new Oxy_Event_Handler();
	}

	/**
	 * Execute some plugin
	 * - structure:create:domain:new
	 *
	 * @var string $str_profile
	 * @var string $str_args
	 */
	public function execute($str_profile, $str_args)
	{
		// Load profile
		$obj_profile = new Oxy_Tool_Project_Profile_Standard($str_profile, $obj_event_handler);
		$arr_params = explode(self::PARAM_DELIMETER, $str_args);
		$obj_profile->execute($arr_params);
	}

	/**
	 * Execute some plugin profile is loaded auto
	 * - structure:create:domain:new
	 *
	 * @var string $str_args
	 */
	public function exec($str_args)
	{
	    // Load profile
		$obj_profile = new Oxy_Tool_Project_Profile_Standard($this->_registry->getConfig()->oproject->profiles_dir .
		                                                     $this->_registry->getConfig()->oproject->profile,
		                                                     $obj_event_handler);
		$arr_params = explode(self::PARAM_DELIMETER, $str_args);
		$obj_profile->execute($arr_params);
	}

	/**
	 * Set profile that tool should use
	 *
	 * @param String $str_path_to_profile
	 */
	public function setProfile($str_path_to_profile)
	{

	}

	/**
	 * Show current loaded profile
	 *
	 */
	public function showProfile()
	{
	    print "Profile file: " . $this->_registry->getConfig()->oproject->profile;
	}

	/**
	 * Create project profile
	 *
	 * @param String $str_path_to_base_dir
	 */
	public function generateProfile($str_path_to_base_dir, $str_profile_name)
	{

	}
}
?>