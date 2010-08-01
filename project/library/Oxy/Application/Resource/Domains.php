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
		$bootstrap->bootstrap('Frontcontroller');
		$front = $bootstrap->getResource('Frontcontroller');
		$domains = $front->getControllerDirectory();

		$options = $this->getOptions();
		if(!isset($options['path'])){
		    throw new Oxy_Application_Exception('Domains resource requires path param to be defined in config!');
		}

		foreach ($domains as $domain => $modules){
			$bootstrapClass = $this->_formatModuleName($domain) . '_Bootstrap';

			if (!class_exists($bootstrapClass, false)){
				$strBootstrapPath = $options['path'] . DIRECTORY_SEPARATOR . $domain . DIRECTORY_SEPARATOR . 'Bootstrap.php';

				if (file_exists($strBootstrapPath)){
					include_once $strBootstrapPath;

					if (! class_exists($bootstrapClass, false)){
						throw new Zend_Application_Resource_Exception('Bootstrap file found for module "' . $domain . '" but bootstrap class "' . $bootstrapClass . '" not found');
					}
				} else {
					continue;
				}
			}

			$domainBootstrap = new $bootstrapClass($bootstrap);

			$domainBootstrap->bootstrap();
			$this->_bootstraps[$domain] = $domainBootstrap;

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
	protected function _formatModuleName($strName)
	{
		$strName = strtolower($strName);
		$strName = str_replace(array('-' , '.'), ' ', $strName);
		$strName = ucwords($strName);
		$strName = str_replace(' ', '', $strName);
		return $strName;
	}
}
