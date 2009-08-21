<?php
class Oxybase_Installer_Ext_Acl extends Oxy_Acl implements Oxy_Extension_Interface
{

}







// Extensions registry
// When application is bootstraped
// During modules bootstraping all found extensions are
// registered to extensions manager
// Then all extensions calls $this->__extension_name__
// Extensible class checks in registry if it exists
final class Oxy_Extensions_Registry
{
	// It is singletone
	public $arr_extensions = array();

	public function addExtensions(Array $arr_extensions = array())
	{
		$this->arr_extensions = $arr_extensions;
	}

	public function getExtensions()
	{
		return $this->arr_extensions;
	}

	public static function getExtension($str_name)
	{
		return $this->arr_extensions[$str_name];
	}

	public function addExtension(IExtension $obj_extension)
	{
		$this->arr_extensions[$obj_extension->getName()] = $obj_extension;
	}
}


/**
 *
 * All extensions must implement this
 *
 */
interface IExtension
{
	public function getName();

	// Should we use returned name as class name
	// return boolean
	public function useInstantly();

	/**
	 * What param name should be
	 * @return unknown_type
	 */
	public function getParamName();
}

/**
 *
 * All extendable components must extend this
 *
 */
abstract class Extensible
{
	// Check in registry
	public function __set($str_param_name)
	{
		$this->$str_param_name = Oxy_Extensions_Registry::getExtension($str_param_name);
	}
}

/**
 *
 * This is extensible component
 *
 */
class Oxy_Acl extends Extensible
{
	public function someFunction()
	{

	}
}


//---------------------------------- User extensions

/**
 * This is oxy component extension
 *

 */
class OxyBase_Installer_Oxy_Acl_Extension extends Oxy_Acl implements IExtension
{
	public function getName()
	{
		return 'v';
	}

	public function someFunction()
	{

	}
}

/**
 * This is another oxy component extension
 * this extensions lets add extensions through the constructor
 */
class OxyBase_InstallerA_Oxy_Acl_Extension extends Oxy_Acl implements IExtension
{
	public function getName()
	{
		return 'oxy_acl_extA';
	}

	public function someFunction()
	{
		$this->oxy_acl_ext1->someFunction();

		// My logic
	}
}

/**
 * This is another oxy component extension
 * to add extensions use Extensible::addExtensions()
 */
class OxyBase_InstallerB_Oxy_Acl_Extension extends Oxy_Acl implements IExtension
{
	public function getName()
	{
		return 'oxy_acl_extB';
	}

	public function useInstantly()
	{
		return false;
	}

	public function someFunctionB()
	{
		// Add new method to component
	}
}

/**
 * This is another oxy component extension
 * to add extensions use Extensible::addExtensions()
 *
 */
class OxyBase_InstallerC_Oxy_Acl_Extension extends Oxy_Acl implements IExtension
{
	public $a;
	private $b;

	// To access protected properties for extended classes
	// create getters and setters
	protected $c;

	public function getName()
	{
		return 'OxyBase_InstallerC_Oxy_Acl_Extension';
	}

	public function useInstantly()
	{
		return true;
	}

	public function getProtectedC()
	{
		return $this->c;
	}
}

/**
 * This is another oxy component extension
 * to add extensions use Extensible::addExtensions()
 *
 */
class OxyBase_InstallerD_Oxy_Acl_Extension extends Oxy_Acl implements IExtension
{
	// This class extends all extensions

	// Change Oxy_Acl
	public function someFunction()
	{
		// Same as $this->someFunction()
		// because extension does nothing
		$this->oxy_acl_ext1->someFunction();

		// Extension adds some functionality
		$this->oxy_acl_extA->someFunction();

		// Extension adds new method
		$this->oxy_acl_extB->someFunctionB();

		// Extension adds new properties
		$this->OxyBase_InstallerC_Oxy_Acl_Extension->a;
		$this->OxyBase_InstallerC_Oxy_Acl_Extension->getProtectedC();

		// Here goes my extension logic
	}

	// Add new function
	public function myNewFunction()
	{

	}
}


/**
 * Above extensions are created by different users, they knows nothing
 * about each other, all they know is that there is such extension called with
 * __xxx__ name (oxy_acl_ext1, oxy_acl_extA) or __class_name_ (OxyBase_InstallerC_Oxy_Acl_Extension)
 */


//------------------------ Usage


// Base Oxy component
$obj_oxy_acl = new Oxy_Acl();


// User 1 writes extension for ACL component
// But he does not uses any other extensions
$obj_oxy_acl_extension = new OxyBase_Installer_Oxy_Acl_Extension();


// User 2 writes other extension
// he uses User 1 extension
$obj_oxy_acl_extensionA = new OxyBase_InstallerA_Oxy_Acl_Extension(
	array(
		'OxyBase_Installer_Oxy_Acl_Extension'
	)
);

// User 3 writes extension for ACL component
// But he does not uses any other extensions
$obj_oxy_acl_extensionB = new OxyBase_InstallerB_Oxy_Acl_Extension();

// User 4 writes extension for ACL component
// But he does not uses any other extensions
$obj_oxy_acl_extensionC = new OxyBase_InstallerC_Oxy_Acl_Extension();

// User 1 writes extension for ACL component
// This uses ALL extensions
$obj_oxy_acl_extensionD = new OxyBase_InstallerD_Oxy_Acl_Extension();
/*$obj_oxy_acl_extensionD->addExtensions(array(
		'OxyBase_Installer_Oxy_Acl_Extension',
		'OxyBase_InstallerA_Oxy_Acl_Extension',
		'OxyBase_InstallerB_Oxy_Acl_Extension',
		'OxyBase_InstallerC_Oxy_Acl_Extension'
	)
);*/
