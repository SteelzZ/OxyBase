<?php
/**
* Base class for results
*
* @category
* @package
* @author Tomas Bartkus
* @version 1.0
**/
abstract class Oxy_Executor_Task_Result_Abstract
{
	/**
	 * Hash array of data
	 *
	 * @var Array
	 */
	private $arr_data;

	/**
	 * Initialize data
	 *
	 * @param Array $arr_data
	 */
	public function __construct(Array $arr_data = array())
	{
		$this->setDataArray($arr_data);
	}

	/**
	 * Return custom key data or all
	 * if null is given
	 *
	 * @return Array|Mixed|Boolean
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
	 * Set data
	 *
	 * @param String $str_key
	 * @param Mixed $mix_data
	 */
	public function setData($str_key = null, $mix_data = null)
	{
		if(!is_string($str_key))
		{
			throw new Oxy_Executor_Task_Result_Exception('Task result data array key must be valid string!');
		}

		$this->arr_data[$str_key] = $mix_data;
	}

	/**
	 * Set data from array
	 *
	 * @param Array $arr_data
	 */
	public function setDataArray(Array $arr_data = array())
	{
		if(!is_array($arr_data))
		{
			throw new Oxy_Executor_Task_Result_Exception('Task result data must be an array!');
		}

		foreach ($arr_data as $str_key => $mix_data)
		{
			$this->setData($str_key, $mix_data);
		}
	}
}
?>