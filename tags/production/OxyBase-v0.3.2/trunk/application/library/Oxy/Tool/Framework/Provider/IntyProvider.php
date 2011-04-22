<?php
require_once 'Zend/Tool/Framework/Provider/Abstract.php';

/**
* Oxy integration provider
*
* @category Oxy
* @package Oxy_Tool
* @subpackage Framework
* @author Tomas Bartkus <to.bartkus@gmail.com>
**/
class Oxy_Tool_Framework_Provider_IntyProvider extends Zend_Tool_Framework_Provider_Abstract
{
	/**
	 * Export integration and create installable package
	 *
	 * @param string $str_config
	 */
	public function export($str_config)
	{
	}

	/**
	 * Import some integration package
	 * Remote packages can be used
	 *
	 * @param string $str_path_to_package
	 * @param boolean $bl_remote
	 */
	public function import($str_path_to_package, $bl_remote = false)
	{

	}

	/**
	 * Upgrade some integration package
	 *
	 * @var string $str_path_to_package
	 */
	public function upgrade($str_path_to_package)
	{

	}

	/**
	 * Show currently imported packages
	 *
	 */
	public function showImported()
	{

	}

	/**
	 * Show available packages
	 */
	public function showAvailable()
	{

	}

	/**
	 * Add repository
	 */
	public function addRepo($str_url)
	{

	}
}
?>