<?php
require_once 'Zend/Tool/Framework/Provider/Interface.php';
require_once 'Oxy/Tool/Project/Profile/Standard.php';

/**
* Oxy project provider
*
* @category Oxy
* @package Oxy_Tool
* @subpackage Project
* @author Tomas Bartkus <to.bartkus@gmail.com>
**/
class Oxy_Tool_Project_Provider_OprojectProvider implements Zend_Tool_Framework_Provider_Interface
{
	/**
	 * Parameters delimeter
	 *
	 * @var String
	 */
	const PARAM_DELIMETER = ':';

	/**
	 * Create profile
	 * - structure:create:domain:new
	 *
	 * @var string $str_profile
	 * @var string $str_args
	 */
	public function execute($str_profile, $str_args)
	{
		// Load profile
		$obj_profile = new Oxy_Tool_Project_Profile_Standard($str_profile);

		$arr_params = explode(self::PARAM_DELIMETER, $str_args);

		$obj_profile->execute($arr_params);
	}
}
?>