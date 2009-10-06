<?php
require_once 'Oxy/Tool/Project/Profile/Abstract.php';

/**
* OxyBase profile class
* This class is responsible for loading
* profiles
*
* @category Oxy
* @package Oxy_Tool
* @subpackage Project
* @author Tomas Bartkus <to.bartkus@gmail.com>
* @version 1.0
**/
class Oxy_Tool_Project_Profile_Standard extends Oxy_Tool_Project_Profile_Abstract
{
	/**
	 * Execute profile plugin
	 *
	 * @param Array $arr_params
	 * @return void
	 */
	public function execute(Array $arr_params = array())
	{
	    // Event
	    $obj_event = new Oxy_Event_Standard($this,
	    									'Oxy_Tool_Project_Profile_Standard.before_execute',
	                                        $arr_params);
        $this->_obj_plugin_handler->notify($obj_event);

		if(!isset($arr_params[0]))
		{
			require_once 'Oxy/Tool/Project/Profile/Exception.php';
			throw new Oxy_Tool_Project_Profile_Exception('Plugin name is not set!');
		}

		if(!isset($arr_params[1]))
		{
			require_once 'Oxy/Tool/Project/Profile/Exception.php';
			throw new Oxy_Tool_Project_Profile_Exception('Plugin action to execute is not set!');
		}

		$str_plugin = $arr_params[0];
		$str_action = $arr_params[1];
		unset($arr_params[0]);
		unset($arr_params[1]);

		// Execute plugin action with given params
		$this->$str_plugin->$str_action($arr_params);

		// Event
		$obj_event = new Oxy_Event_Standard($this,
	    									'Oxy_Tool_Project_Profile_Standard.after_execute',
	                                        $arr_params);
        $this->_obj_plugin_handler->notify($obj_event);
	}

}
?>