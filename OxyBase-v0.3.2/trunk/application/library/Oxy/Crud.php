<?php
require_once 'Oxy/Crud/Abstract.php';

/**
* CRUD
*
* @category Oxy
* @package Oxy_Crud
* @author Tomas Bartkus <to.bartkus@gmail.com>
* @version 1.0
**/
class Oxy_Crud extends Oxy_Crud_Abstract
{
	/**
	 * Singleton instance
	 *
	 * Marked only as protected to allow extension of the class. To extend,
	 * simply override {@link getInstance()}.
	 *
	 * @var Oxy_Crud_Abstract
	 **
	protected static $_obj_instance = null;

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
	 * @return Oxy_Crud_Abstract
	 */
	public static function getInstance()
	{
		if (null === self::$_obj_instance)
		{
			self::$_obj_instance = new self();
		}
		return self::$_obj_instance;
	}
}
/**
$obj_adapter = new Oxy_Crud_Adapter_Doctrine('language');
Oxy_Crud::getInstance()->setAdapter($obj_adapter);
Oxy_Crud::generate();
Oxy_Crud::generate();
Oxy_Crud::generate();
Oxy_Crud::generate();
/**/
?>