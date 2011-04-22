<?php
/**
* Base class for task
*
* @category Oxy
* @package Oxy_Executor
* @author Tomas Bartkus
* @version 1.0
**/
abstract class Oxy_Executor_Task_Abstract
{
	/**
	 * Result object
	 *
	 * @var Oxy_Executor_Task_Result_Abstract
	 */
	private $obj_result;

	/**
	 * Task name
	 *
	 * @var String
	 */
	private $str_name;

	/**
	 * Check array
	 *
	 * @var Array
	 */
	private $arr_checks;

	/**
	 * Variuos data needed
	 * for task execution
	 *
	 * @var Array
	 */
	private $arr_data;

	/**
	 * Initialize data
	 *
	 * @param Array
	 */
	public function __construct($str_name = null,
								Array $arr_data = array(),
								Array $arr_checks = array())
	{
		$this->obj_result = new Oxy_Executor_Task_Result_Standard();
		$this->setName($str_name);
		$this->setData($arr_data);
		$this->addChecks($arr_checks);
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
			throw new Oxy_Executor_Task_Exception('Data for task must be an array!');
		}

		$this->arr_data = $arr_data;
	}

	/**
	 * Return custom value or all data
	 * If key not found or error - return false
	 *
	 * @param String $str_key
	 *
	 * @return Mixed|Array|Boolean
	 */
	public function getData($str_key = null)
	{
		if(is_null($str_key))
		{
			return (array) $this->arr_data;
		}
		else
		{
			if(isset($this->arr_data[$str_key]))
			{
				return $this->arr_data[$str_key];
			}
		}

		return false;
	}

	/**
	 * Set task name
	 *
	 * @param String $str_name
	 */
	public function setName($str_name = null)
	{
		if(!is_string($str_name))
		{
			throw new Oxy_Executor_Task_Exception('Task name must be valid string!');
		}

		$this->str_name = $str_name;
	}

	/**
	 * Return task name
	 *
	 * @return String
	 */
	public function getName()
	{
		return (string) $this->str_name;
	}

	/**
	 * Set result
	 *
	 * @param Oxy_Executor_Task_Result_Abstract $obj_result
	 */
	public function setResult(Oxy_Executor_Task_Result_Abstract $obj_result)
	{
		$this->obj_result = $obj_result;
	}

	/**
	 * Return task result
	 * that was generated after task execution
	 *
	 * @return Oxy_Executor_Task_Result_Abstract
	 */
	public function getResult()
	{
		return $this->obj_result;
	}

/**
	 * Return all checks
	 *
	 * @return Array
	 */
	public function getChecks()
	{
		return (array) $this->arr_checks;
	}

	/**
	 * Return custom check
	 *
	 * @param String $str_check_name
	 * @return Oxy_Executor_Check_Interface
	 */
	public function getCheck($str_check_name = null)
	{
		if(!isset($this->arr_checks[$str_check_name]))
		{
			throw new Oxy_Executor_Task_Exception('Check is not set!');
		}

		return $this->arr_checks[$str_check_name];
	}

	/**
	 * Add new check
	 *
	 * @param Oxy_Executor_Check_Interface $obj_check
	 */
	public function addCheck(Oxy_Executor_Check_Interface $obj_check)
	{
		if(isset($this->arr_checks[$obj_check->getName()]))
		{
			throw new Oxy_Executor_Task_Exception('Check with such name already exists!');
		}

		$this->arr_checks[$obj_check->getName()] = $obj_check;
	}

	/**
	 * Add more checks at once
	 *
	 * @param Array $arr_checks
	 */
	public function addChecks(Array $arr_checks = array())
	{
		foreach ($arr_checks as $obj_check)
		{
			$this->addCheck($obj_check);
		}
	}

	/**
	 * Run validations
	 * If any validation fails - break and return false
	 *
	 * @return Boolean
	 */
	public function validate()
	{
		$bl_result = true;
		foreach ($this->arr_checks as $obj_check)
		{
			$bl_result = $obj_check->validate();
			if(!$bl_result)
			{
				break;
			}
		}

		return $bl_result;
	}

	/**
	 * Execute task
	 * Concrete implementation of task execution
	 *
	 * @return Boolean
	 */
	abstract public function execute();
}
?>