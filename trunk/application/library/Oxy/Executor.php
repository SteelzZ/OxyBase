<?php
/**
* Actions executor
*
* @category Oxy
* @package Oxy_Executor
* @author Tomas Bartkus
* @version 1.0
**/
class Oxy_Executor
{
	/**
	 * Actions array
	 *
	 * @var Array
	 */
	private $arr_actions;

	/**
	 * Instance
	 *
	 * @var Oxy_Executor
	 */
	private static $obj_instance;

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
	 * @return Oxy_Executor
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
	 * Add action
	 *
	 * @param Oxy_Executor_Action_Abstract $obj_action
	 */
	public function addAction(Oxy_Executor_Action_Abstract $obj_action)
	{
		$this->arr_actions[$obj_action->getName()] = $obj_action;
	}

	/**
	 * Add more then one action at once
	 *
	 * @param Array $arr_actions
	 */
	public function addActions(Array $arr_actions = array())
	{
		foreach ($arr_actions as $obj_action)
		{
			$this->addAction($obj_action);
		}
	}

	/**
	 * Get all actions
	 *
	 * @return Array
	 */
	public function getActions()
	{
		return $this->arr_actions;
	}

	/**
	 * Return action
	 *
	 * @param String $str_action_name
	 *
	 * @return Oxy_Executor_Action_Abstract
	 */
	public function getAction($str_action_name = null)
	{
		if(!isset($this->arr_actions[$str_action_name]))
		{
			throw new Oxy_Executor_Exception('Action is not set!');
		}

		return $this->arr_actions[$str_action_name];
	}

	/**
	 * Return custom action custom task result
	 * if request fails false is returned
	 *
	 * @param String $str_action_name
	 * @param String $str_task_name
	 *
	 * @return Oxy_Executor_Task_Result_Abstract|Boolean
	 */
	public function getActionTaskResult($str_action_name = null, $str_task_name = null)
	{
		if(is_string($str_action_name) && is_string($str_task_name))
		{
			$obj_action = $this->getAction($str_action_name);

			if($obj_action instanceof Oxy_Executor_Action_Abstract)
			{
				$obj_task = $obj_action->getTask($str_task_name);
				if($obj_task instanceof Oxy_Executor_Task_Abstract)
				{
					return $obj_task->getResult();
				}
			}
		}

		return false;
	}

	/**
	 * Reset all data and prepare environment
	 * for other action
	 *
	 */
	public function reset()
	{
		$this->arr_actions = array();
	}

	/**
	 * Execute requested action or execute all
	 * if null is passed
	 *
	 * If any action fails stop procedure
	 *
	 * @param String $str_action_name
	 *
	 * @return Boolean
	 */
	public function execute($str_action_name = null, $str_task_name = null)
	{
		$bl_result = true;
		if(is_string($str_action_name) && is_string($str_task_name))
		{
			if(isset($this->arr_actions[$str_action_name]))
			{
				$obj_action = $this->arr_actions[$str_action_name];
				$obj_action->execute($str_task_name);
			}
		}
		else
		{
			foreach ($this->arr_actions as &$obj_action)
			{
				$bl_result = $obj_action->execute();
				if(!$bl_result)
				{
					break;
				}
			}
		}

		return $bl_result;
	}
}
?>