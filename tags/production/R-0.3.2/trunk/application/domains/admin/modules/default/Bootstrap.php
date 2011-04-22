<?php
/**
* Default bootstrap
*
* @category Modules
* @package Default
* @author Tomas Bartkus
* @version 1.0
**/
class Admin_Default_Bootstrap extends Oxy_Application_Module_Bootstrap
{
	/**
	 * Base module path
	 *
	 * @var String
	 */
	private $str_base_path;


	/**
	 * Set include pathes for module
	 *
	 */
	protected function _initPath()
	{
		$this->str_base_path = APPLICATION_PATH . 'domains/admin/modules/default/';

		/*set_include_path(
		    $this->str_base_path . 'resources/'. PATH_SEPARATOR .
		    $this->str_base_path . 'resources/db_tables/'. PATH_SEPARATOR .
		    $this->str_base_path . 'resources/db_tables/generated/'. PATH_SEPARATOR .
		    get_include_path()
		);*/
	}

	/**
	 * Load extensions
	 */
	protected function _initExtensions()
	{
		Oxy_Extension_Manager::getInstance()->addExtension(new Admin_Default_Model_Manager());
	}


	/**
	 * Initialize database tables
	 *
	 */
	protected function _initDB()
	{
		//Doctrine::createTablesFromModels($this->str_base_path . 'resources/db_tables/');
	}

	/**
	 * Initialize plugins
	 *
	 */
	protected function _initPlugins()
	{
		//$this->registerPlugins($this->getApplication()->getOption('default'));
	}
}
?>