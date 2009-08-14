<?php
/**
* Oxy identity abstract class
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
	 * @var String
	 */
	protected $str_id;

	/**
	 * Identity role
	 *
	 * @var String
	 */
	protected $str_role_id;

	/**
	 * Identity constructor
	 *
	 * @param String $str_role_id
	 * @param String $str_id
	 */
	public function __construct($str_role_id = null, $str_id = null)
	{
		$this->setId($str_id);
		$this->setRoleId($str_role_id);
	}

	/**
	 * Set id
	 *
	 * @param String $str_id
	 */
	public function setId($str_id = null)
	{
		if(is_null($str_id))
		{
			throw new Oxy_Identity_Exception('Identity id can not be null');
		}

		$this->str_id = $str_id;
	}

	/**
	 * Set role id
	 *
	 * @param String $str_role_id
	 */
	public function setRoleId($str_role_id = null)
	{
		if(is_null($str_role_id))
		{
			$this->str_role_id = self::GUEST;
		}

		$this->str_role_id = $str_role_id;
	}

	/**
	 * @see Oxy_Identity_Interface::getId()
	 */
	public function getId()
	{
		return $this->str_id;
	}

	/**
	 * @see Oxy_Identity_Interface::getRoleId()
	 */
	public function getRoleId()
	{
		return $this->str_role_id;
	}
}
?>