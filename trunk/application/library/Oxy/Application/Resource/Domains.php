<?php
/**
 * Oxy modules loader
 *
 * @category Oxy
 * @package Oxy_Application
 * @subpackage Resources
 * @author Tomas Bartkus <to.bartkus@gmail.com>
 **/
class Oxy_Application_Resource_Domains extends Zend_Application_Resource_ResourceAbstract
{

	/**
	 * @var ArrayObject
	 */
	protected $_bootstraps;

	/**
	 * Constructor
	 *
	 * @param  mixed $options
	 * @return void
	 */
	public function __construct($options = null)
	{
		$this->_bootstraps = new ArrayObject(array(), ArrayObject::ARRAY_AS_PROPS);
		parent::__construct($options);
	}

	/**
	 * Initialize modules
	 *
	 * @return array
	 * @throws Zend_Application_Resource_Exception When bootstrap class was not found
	 */
	public function init()
	{
		$bootstrap = $this->getBootstrap();
		$bootstrap->bootstrap('Front');
		$front = $bootstrap->getResource('Front');
		$arr_domains = $front->getControllerDirectory();
		$default = $front->getDefaultModule();
		foreach ($arr_domains as $str_domain => $arr_modules)
		{
			$bootstrapClass = $this->_formatModuleName($str_domain) . '_Bootstrap';

			if (!class_exists($bootstrapClass, false))
			{
				$bootstrapPath = $front->getDomainDirectory($str_domain) . '\Bootstrap.php';

				// Calculate domain pat
				$arr_data = explode('/',$bootstrapPath);
				unset($arr_data[count($arr_data)-1]);
				$arr_data[] = 'domains';
				$arr_data[] = $str_domain;
				$bootstrapPath = implode('/', $arr_data);

				$bootstrapPath = $bootstrapPath . '/Bootstrap.php';
				if (file_exists($bootstrapPath))
				{
					include_once $bootstrapPath;

					if (! class_exists($bootstrapClass, false))
					{
						throw new Zend_Application_Resource_Exception('Bootstrap file found for module "' . $str_domain . '" but bootstrap class "' . $bootstrapClass . '" not found');
					}
				}
				else
				{
					continue;
				}
			}

			$domainBootstrap = new $bootstrapClass($bootstrap);

			$domainBootstrap->bootstrap();
			$this->_bootstraps[$str_domain] = $domainBootstrap;

		}
		return $this->_bootstraps;
	}

	/**
	 * Get bootstraps that have been run
	 *
	 * @return ArrayObject
	 */
	public function getExecutedBootstraps()
	{
		return $this->_bootstraps;
	}

	/**
	 * Format a module name to the module class prefix
	 *
	 * @param  string $name
	 * @return string
	 */
	protected function _formatModuleName($name)
	{
		$name = strtolower($name);
		$name = str_replace(array('-' , '.'), ' ', $name);
		$name = ucwords($name);
		$name = str_replace(' ', '', $name);
		return $name;
	}
}
