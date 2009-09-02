<?php
require_once 'Oxy/Tool/Project/Profile/Plugin/Abstract.php';

/**
* Deploy plugin
*
* @category Oxy
* @package Oxy_Tool
* @subpackage Project
* @author Tomas Bartkus <to.bartkus@gmail.com>
**/
class Oxy_Tool_Project_Profile_Plugin_Generator extends Oxy_Tool_Project_Profile_Plugin_Abstract
{
	/**
	 * Prepare new file
	 *
	 * @param Array $arr_params
	 *
	 * @return void
	 */
	public function prepare(Array $arr_params = array())
	{
		if(!isset($arr_params[2]) || empty($arr_params[2]))
		{
			throw new Oxy_Tool_Project_Profile_Plugin_Exception('Resource name can not be null!');
		}

		print "will prepare $arr_params[2]";
	}
}
?>