<?php

/**
 * Oxy ACL implementation
 *
 * @category Oxy
 * @package Oxy_Acl
 * @author Tomas Bartkus
 * @version 1.0
 **/
class Oxy_Acl_Db extends Oxy_Acl
{

	/**
	 * Singleton instance
	 *
	 * Marked only as protected to allow extension of the class. To extend,
	 * simply override {@link getInstance()}.
	 *
	 * @var Oxy_Acl_Db
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
	 * @return Oxy_Acl_Db
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
	 * Initialize ACL
	 *
	 * @param ArrayIterator $obj_iterator
	 */
	public function init (ArrayIterator $obj_iterator = null,
						  ArrayIterator $obj_roles = null,
						  ArrayIterator $obj_resources = null,
						  ArrayIterator $obj_asserts = null)
	{
		foreach ($obj_roles as $obj_role_record)
		{
			if (! ($obj_role_record instanceof Oxy_Acl_Role_Interface))
			{
				throw new Oxy_Acl_Role_Exception('Role record must be an object of Oxy_Acl_Role_Interface type!');
			}

			if (! $this->hasRole($obj_role_record->getRole()))
			{
				if($this->hasRole($obj_role_record->getParentRole()))
				{
					$this->addRole(new Zend_Acl_Role($obj_role_record->getRole()), $obj_role_record->getParentRole());
				}
				else
				{
					$this->addRole(new Zend_Acl_Role($obj_role_record->getRole()));
				}
			}
		}

		foreach ($obj_resources as $obj_resource_record)
		{
			if (! ($obj_resource_record instanceof Oxy_Acl_Resource_Interface))
			{
				throw new Oxy_Acl_Resource_Exception('Resource record must be an object of Oxy_Acl_Resource_Interface type!');
			}

			if (! $this->has($obj_resource_record->getResource()))
			{
				if($obj_resource_record->getParentResource() === null)
				{
					$this->add(new Zend_Acl_Resource($obj_resource_record->getResource()));
				}
				else
				{
					if($this->has($obj_resource_record->getParentResource()))
					{
						$this->add(new Zend_Acl_Resource($obj_resource_record->getResource()), new Zend_Acl_Resource($obj_resource_record->getParentResource()));
					}
					else
					{
						$this->add(new Zend_Acl_Resource($obj_resource_record->getResource()));
					}
				}
			}
		}

		// Defaul error resource
		if (! $this->has('default_error'))
		{
			$this->add(new Zend_Acl_Resource('default_error'));
		}

		$this->deny();
		$this->allow(null, 'default_error');

		foreach ($obj_iterator as $obj_acl_record)
		{
			if (! ($obj_acl_record instanceof Oxy_Acl_Record_Interface))
			{
				throw new Oxy_Acl_Record_Exception('ACL record must be an object of Oxy_Acl_Record_Interface type!');
			}

			if((int)$obj_acl_record->isAllowed() === 1)
			{
				if($obj_asserts->count() > 0)
				{
					foreach ($obj_asserts as $obj_assert)
					{
						$this->allow($obj_acl_record->getRole(),
									 $obj_acl_record->getResource(),
									 $obj_acl_record->getAction(),
									 $obj_assert);
					}
				}
				else
				{
					$this->allow($obj_acl_record->getRole(),
								 $obj_acl_record->getResource(),
								 $obj_acl_record->getAction());
				}
			}
			else
			{
				$this->deny($obj_acl_record->getRole(),
							$obj_acl_record->getResource(),
							$obj_acl_record->getAction());
			}
		}
	}
}
?>