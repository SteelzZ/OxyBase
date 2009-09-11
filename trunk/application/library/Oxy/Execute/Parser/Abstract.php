<?php

/**
* Base class for parsers
*
* @category Oxy
* @package OxyExecute
* @subpackage Parser
* @author Tomas Bartkus <to.bartkus@gmail.com>
* @version 1.0
**/
class Oxy_Execute_Parser_Abstract
{
	/**
	 * Operations
	 *
	 * @var DOMDocument
	 */
	protected $_obj_operations;

	/**
	 * Initialize
	 *
	 * @return void
	 */
	public function __construct($str_path_to_profile = '')
	{
		$this->loadOperations($str_path_to_profile);
	}

	/**
	 * Load profile
	 *
	 * @param String $str_path_to_profile
	 *
	 * @return void
	 */
	public function loadOperations($str_path_to_operations = '')
	{
		$this->_obj_operations = new DOMDocument('1.0','utf-8');
        if (!$this->_obj_operations->loadXML(file_get_contents($str_path_to_operations)))
        {
            require_once 'Oxy/Execute/Parser/Exception.php';
            throw new Oxy_Execute_Parser_Exception("Operations '{$str_path_to_operations}' XML could not be loaded!");
        }
	}

	/**
	 * Parse operations file
	 *
	 * @return Array
	 */
	abstract public function parse();
}
?>