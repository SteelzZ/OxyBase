<?php
/**
* Operations executor
*
* @category Oxy
* @package Oxy_Execute
* @author Tomas Bartkus
* @version 1.0
**/
class Oxy_Execute
{
    /**
     * Parser
     *
     * @var Oxy_Execute_Parser_Abstract
     */
    private $_obj_parser;

	/**
	 * Instance
	 *
	 * @var Oxy_Executor
	 */
	private static $_obj_instance;

	/**
	 * Constructor
	 *
	 * Instantiate using {@link getInstance()};
	 *
	 * @return void
	 */
	protected function __construct()
	{}

	/**
	 * Enforce singleton; disallow cloning
	 *
	 * @return void
	 */
	private function __clone()
	{}

	/**
	 * Singleton instance
	 *
	 * @return Oxy_Executor
	 */
	public static function getInstance()
	{
		if (null === self::$_obj_instance)
		{
			self::$_obj_instance = new self();
		}
		return self::$_obj_instance;
	}

	/**
	 * Set parser
	 *
	 * @param Oxy_Execute_Parser_Abstract $obj_parser
	 */
	public function setParser(Oxy_Execute_Parser_Abstract $obj_parser)
	{
	    $this->_obj_parser = $obj_parser;
	}

	/**
	 * Execute requested operations
	 *
	 * @param String $str_tasks_file
	 */
	public function execute($str_tasks_file = null)
	{
	    $arr_operations = $this->_obj_parser->parse();
	    foreach($arr_operations as $obj_operation)
	    {
	        $obj_operation->execute();
	    }
	}
}
?>