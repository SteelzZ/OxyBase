<?php
/**
* Plugin manager
*
* @category Oxy
* @package Oxy_Tool
* @subpackage Project
* @author Tomas Bartkus <to.bartkus@gmail.com>
* @version 1.0
**/
class Oxy_Tool_Project_Profile_Plugin_Manager
{
    /**
	 * Loaded plugins
	 *
	 * @var Array
	 */
	public $_arr_plugins = array();

	/**
	 * Singleton instance
	 *
	 * Marked only as protected to allow extension of the class. To extend,
	 * simply override {@link getInstance()}.
	 *
	 * @var Oxy_Tool_Project_Profile_Plugin_Manager
	 */
	protected static $_obj_instance = null;

	/**
	 * Constructor
	 *
	 * Instantiate using {@link getInstance()};
	 *
	 * @return void
	 */
	protected function __construct ()
	{}

	/**
	 * Enforce singleton; disallow cloning
	 *
	 * @return void
	 */
	private function __clone ()
	{}

	/**
	 * Singleton instance
	 *
	 * @return Oxy_Tool_Project_Profile_Plugin_Manager
	 */
	public static function getInstance ()
	{
		if (null === self::$_obj_instance)
		{
			self::$_obj_instance = new self();
		}
		return self::$_obj_instance;
	}

	/**
	 * Register plugins
	 *
	 * @param Array $arr_plugins
	 *
	 * @return void
	 */
	public function registerPlugins(Array $arr_plugins = array())
	{
		foreach ($arr_plugins as $str_name => $mix_object)
		{
			if(!($mix_object instanceof Oxy_Tool_Project_Profile_Plugin_Abstract))
			{
				if(is_string($mix_object))
				{
					$this->addPlugin(new $mix_object());
				}
			}
			else if($mix_object instanceof Oxy_Tool_Project_Profile_Plugin_Abstract)
			{
				$this->addPlugin($mix_object);
			}
		}
	}

	/**
	 * Return all plugins
	 *
	 * @return Array
	 */
	public function getPlugins()
	{
		return $this->_arr_plugins;
	}

	/**
	 * Return custom plugin
	 *
	 * @param String$str_name
	 *
	 * @return Oxy_Tool_Project_Profile_Plugin_Abstract|Boolean
	 */
	public function getPlugin($str_name = null)
	{
		if(isset($this->_arr_plugins[$str_name]))
		{
			return $this->_arr_plugins[$str_name];
		}

		return false;
	}

	/**
	 * Add new plugin
	 *
	 * @param Oxy_Tool_Project_Profile_Plugin_Abstract $obj_extension
	 *
	 * @return unknown_type
	 */
	public function addPlugin(Oxy_Tool_Project_Profile_Plugin_Abstract $obj_plugin)
	{
		if(isset($this->_arr_plugins[$obj_plugin->getName()]))
		{
			throw new Oxy_Extension_Exception('Extension ['.$obj_plugin->getName().'] is already loaded!');
		}

		$this->_arr_plugins[$obj_plugin->getName()] = $obj_plugin;
	}

}
?>