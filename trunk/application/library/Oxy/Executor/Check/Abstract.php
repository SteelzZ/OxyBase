<?php
/**
* Check base
*
* @category Oxy
* @package Oxy_Executor
* @author Tomas Bartkus
* @version 1.0
**/
abstract class Oxy_Executor_Check_Abstract implements Oxy_Executor_Check_Interface
{
	/**
	 * Required data
	 * hash array
	 *
	 * @var Array
	 */
	protected $arr_data;

	/**
	 * Check name
	 *
	 * @var String
	 */
	protected $str_name;

	/**
	 * Initialize data
	 *
	 * @param Array $arr_data
	 */
	public function __construct($str_name = '', Array $arr_data = array())
	{
		$this->setData($arr_data);
		$this->setName($str_name);
	}

	/**
	 * Set data
	 *
	 * @param Array $arr_data
	 */
	public function setData(Array $arr_data = array())
	{
		if(!is_array($arr_data))
		{
			throw new Model_Catalog_Action_Add_Check_Exception('Data must be an array');
		}

		$this->arr_data = $arr_data;
	}

	/**
	 * Set check name
	 *
	 * @param String $str_name
	 */
	public function setName($str_name = null)
	{
		if(!is_string($str_name))
		{
			throw new Model_Catalog_Action_Add_Check_Exception('Check name must be string!');
		}

		$this->str_name = $str_name;
	}

	/**
	 * Return name
	 *
	 * @return String
	 */
	public function getName()
	{
		return $this->str_name;
	}

	/**
	 * Concrete validation
	 */
	public function validate()
	{
	}
}
?>