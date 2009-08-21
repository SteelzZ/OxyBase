<?php
/**
* Installer bootstrap
*
* @category Modules
* @package Installer
* @author Tomas Bartkus
* @version 1.0
**/
class Oxybase_Installer_Bootstrap extends Oxy_Application_Module_Bootstrap
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
		$this->str_base_path = APPLICATION_PATH . 'domains/oxybase/modules/installer/';

		set_include_path(
		    $this->str_base_path . 'resources/'. PATH_SEPARATOR .
		    $this->str_base_path . 'resources/db_tables/'. PATH_SEPARATOR .
		    $this->str_base_path . 'resources/db_tables/generated/'. PATH_SEPARATOR .
		    get_include_path()
		);


	}

	/**
	 * Initialize extensions
	 *
	 */
	protected function _initExtensions()
	{
		// @TODO: Autoload extensions
		//Oxy_Extension_Manager::getInstance()->addExtension(new Oxybase_Installer_Ext_Acl());
		//Oxy_Extension_Manager::getInstance()->addExtension(new Oxybase_Installer_Ext_Acl_Db());
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
		//$this->registerPlugins($this->getApplication()->getOption('installer'));
	}
}
?>