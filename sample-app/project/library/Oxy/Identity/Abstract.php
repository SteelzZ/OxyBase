<?php
/**
* Oxy identity base class
*
* @category Oxy
* @package Oxy_Identity
* @author Tomas Bartkus
**/
abstract class Oxy_Identity_Abstract implements Oxy_Identity_Interface
{
	/**
	 * Guest role
	 *
	 * @var String
	 */
	const GUEST = 'guest';

	/**
	 * Identity id
	 *
	 * @var Integer
	 */
	protected $_id;

	/**
	 * Identity role
	 *
	 * @var String
	 */
	protected $_roleId;

	/**
	 * Identity constructor
	 *
	 * @param String $str_role_id
	 * @param String $str_id
	 */
	public function __construct($roleId = null, $id = null)
	{
		$this->setId($id);
		$this->setRoleId($roleId);
	}

	/**
	 * Set id
	 *
	 * @param Integer $strId
	 */
	public function setId($intId = null)
	{
		if(is_null($intId))
		{
			throw new Oxy_Identity_Exception('Identity id can not be null');
		}

		$this->_id = $intId;
	}

	/**
	 * Set role id
	 *
	 * @param String $strRoleId
	 */
	public function setRoleId($strRoleId = null)
	{
		if(is_null($strRoleId))
		{
			$this->_roleId = self::GUEST;
		}

		$this->_roleId = $strRoleId;
	}

	/**
	 * @see Oxy_Identity_Interface::getId()
	 */
	public function getId()
	{
		return $this->_id;
	}

	/**
	 * @see Oxy_Identity_Interface::getRoleId()
	 */
	public function getRoleId()
	{
		return $this->_roleId;
	}
}