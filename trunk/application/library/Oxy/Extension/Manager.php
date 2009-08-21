<?php
require_once 'Oxy/Extension/Interface.php';
require_once 'Oxy/Extension/Exception.php';

/**
 * Oxy Extensions manager
 * Extensions container/loader
 *
 * @category Oxy
 * @package Extension
 * @author Tomas Bartkus
 */
final class Oxy_Extension_Manager
{
	/**
	 * Loaded extensions
	 *
	 * @var Array
	 */
	public $arr_extensions = array();

	/**
	 * Singleton instance
	 *
	 * Marked only as protected to allow extension of the class. To extend,
	 * simply override {@link getInstance()}.
	 *
	 * @var Oxy_Extension_Manager
	 */
	protected static $obj_instance = null;

	/**
	 * Constructor
	 *
	 * Instantiate using {@link getInstance()};
	 *
	 * @return void
	 */
	protected function __construct ()
	{

	}

	/**
	 * Enforce singleton; disallow cloning
	 *
	 * @return void
	 */
	private function __clone ()
	{
	}

	/**
	 * Singleton instance
	 *
	 * @return Oxy_Extension_Manager
	 */
	public static function getInstance ()
	{
		if (null === self::$obj_instance)
		{
			self::$obj_instance = new self();
		}
		return self::$obj_instance;
	}

	/**
	 * Add extensions
	 *
	 * @param Array $arr_extensions
	 *
	 * @return void
	 */
	public function addExtensions(Array $arr_extensions = array())
	{
		foreach ($arr_extensions as $str_name => $mix_object)
		{
			if(!($mix_object instanceof Oxy_Extension_Interface))
			{
				if(is_string($mix_object))
				{
					$this->addExtension(new $mix_object());
				}
			}
			else if($mix_object instanceof Oxy_Extension_Interface)
			{
				$this->addExtension($mix_object);
			}
		}
	}

	/**
	 * Return all extensions
	 *
	 * @return Array
	 */
	public function getExtensions()
	{
		return $this->arr_extensions;
	}

	/**
	 * Return custom extensions
	 *
	 * @param String$str_name
	 *
	 * @return Oxy_Extension_Interface|Boolean
	 */
	public function getExtension($str_name)
	{
		if(isset($this->arr_extensions[$str_name]))
		{
			return $this->arr_extensions[$str_name];
		}

		return false;
	}

	/**
	 * Add new extension
	 *
	 * @param Oxy_Extension_Interface $obj_extension
	 *
	 * @return unknown_type
	 */
	public function addExtension(Oxy_Extension_Interface $obj_extension)
	{
		if(isset($this->arr_extensions[$obj_extension->getName()]))
		{
			throw new Oxy_Extension_Exception('Extension ['.$obj_extension->getName().'] is already loaded!');
		}

		$this->arr_extensions[$obj_extension->getName()] = $obj_extension;
	}

}
?>