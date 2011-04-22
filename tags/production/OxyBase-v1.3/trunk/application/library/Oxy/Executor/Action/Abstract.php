<?php
/**
* Base action class
*
* @category Oxy
* @package Oxy_Executor
* @author Tomas Bartkus
* @version 1.0
**/
abstract class Oxy_Executor_Action_Abstract
{
	/**
	 * Action name
	 *
	 * @var String
	 */
	private $str_name;

	/**
	 * Tasks array
	 *
	 * @var Array
	 */
	private $arr_tasks;

	/**
	 * Check array
	 *
	 * @var Array
	 */
	private $arr_checks;

	/**
	 * Locks arrays
	 *
	 * @var Array
	 */
	private $arr_locks;

	/**
	 * Initialize data
	 *
	 * @param String $str_name
	 * @param Array $arr_tasks
	 */
	public function __construct($str_name = null,
								Array $arr_tasks = array(),
								Array $arr_checks = array(),
								Array $arr_locks = array())
	{
		$this->setName($str_name);
		$this->addChecks($arr_checks);
		$this->addTasks($arr_tasks);
		$this->addLocks($arr_locks);
	}

	/**
	 * Set action name
	 *
	 * @param String $str_name
	 *
	 * @return void
	 */
	public function setName($str_name = null)
	{
		if(!is_string($str_name))
		{
			throw new Oxy_Executor_Action_Exception('Action name muts be valid string!');
		}

		$this->str_name = $str_name;
	}

	/**
	 * @see Oxy_Executor_Action_Interface::getName()
	 *
	 * @return String
	 */
	public function getName()
	{
		return (string) $this->str_name;
	}

	/**
	 * Return all tasks
	 *
	 * @return Array
	 */
	public function getTasks()
	{
		return (array) $this->arr_tasks;
	}

	/**
	 * Return custom task
	 *
	 * @param String $str_task_name
	 *
	 * @return Oxy_Executor_Task_Abstract
	 */
	public function getTask($str_task_name = null)
	{
		if(!isset($this->arr_tasks[$str_task_name]))
		{
			throw new Oxy_Executor_Action_Exception('Task is not set!');
		}

		return $this->arr_tasks[$str_task_name];
	}

	/**
	 * Add new task
	 *
	 * @param Oxy_Executor_Task_Interface $obj_task
	 */
	public function addTask(Oxy_Executor_Task_Abstract $obj_task)
	{
		if(isset($this->arr_tasks[$obj_task->getName()]))
		{
			throw new Oxy_Executor_Action_Exception('Task with such name already exists!');
		}

		$this->arr_tasks[$obj_task->getName()] = $obj_task;
	}

	/**
	 * Add more tasks at once
	 *
	 * @param Array $arr_tasks
	 */
	public function addTasks(Array $arr_tasks = array())
	{
		foreach ($arr_tasks as $obj_task)
		{
			$this->addTask($obj_task);
		}
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
			throw new Oxy_Executor_Action_Exception('Check is not set!');
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
			throw new Oxy_Executor_Action_Exception('Check with such name already exists!');
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
	 * Return all locks
	 *
	 * @return Array
	 */
	public function getLocks()
	{
		return (array) $this->arr_locks;
	}

	/**
	 * Return custom lock
	 *
	 * @param String $str_lock_name
	 * @return Oxy_Executor_Lock_Interface
	 */
	public function getLock($str_lock_name = null)
	{
		if(!isset($this->arr_locks[$str_lock_name]))
		{
			throw new Oxy_Executor_Action_Exception('Lock is not set!');
		}

		return $this->arr_locks[$str_lock_name];
	}

	/**
	 * Add new lock
	 *
	 * @param Oxy_Executor_Lock_Interface $obj_lock
	 */
	public function addLock(Oxy_Executor_Lock_Interface $obj_lock)
	{
		if(isset($this->arr_locks[$obj_lock->getName()]))
		{
			throw new Oxy_Executor_Action_Exception('Lock with such name already exists!');
		}

		$this->arr_locks[$obj_lock->getName()] = $obj_lock;
	}

	/**
	 * Add more locks at once
	 *
	 * @param Array $arr_locks
	 */
	public function addLocks(Array $arr_locks = array())
	{
		foreach ($arr_locks as $obj_lock)
		{
			$this->addLock($obj_lock);
		}
	}

	/**
	 * Execute tasks
	 * if null given execute all tasks
	 *
	 * @param String $str_task_name
	 * @return Boolean
	 */
	public function execute($str_task_name = null)
	{
		// Perform validation (run checks)
		$bl_result = true;
		foreach ($this->arr_checks as $obj_check)
		{
			$bl_result = $obj_check->validate();
			if($bl_result === false)
			{
				return false;
			}
		}

		// Proceed with tasks
		// When task is executed it generates some result
		// that result is saved in task object
		// If some task needs result from previuos task
		// It can be retrieved throught the Oxy_Executor::getActionTaskResult()
		if(is_string($str_task_name))
		{
			if(isset($this->arr_tasks[$str_task_name]))
			{
				$obj_task = &$this->arr_tasks[$str_task_name];
				$bl_result = $obj_task->execute();
			}
		}
		else
		{
			foreach ($this->arr_tasks as &$obj_task)
			{
				$bl_result = $obj_task->execute();
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