<?php
/**
 * Oxy modules loader
 *
 * @category
 * @package
 * @author Tomas Bartkus
 * @version 1.0
 **/
class Oxy_Application_Resource_Modules extends Zend_Application_Resource_ResourceAbstract
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
		$arr_domains = $front->getControllerDirectory();
		$default = $front->getDefaultModule();
		foreach ($arr_domains as $str_domain => $arr_modules)
		{
			foreach (array_keys($arr_modules) as $module)
			{
				if ($module === $default)
				{
					continue;
				}
				$bootstrapClass = $this->_formatModuleName($str_domain) . '_' .
								  $this->_formatModuleName($module) . '_Bootstrap';

				if (! class_exists($bootstrapClass, false))
				{
					$bootstrapPath = $front->getDomainDirectory($str_domain, $module) . '/Bootstrap.php';
					if (file_exists($bootstrapPath))
					{
						include_once $bootstrapPath;
						if (! class_exists($bootstrapClass, false))
						{
							throw new Zend_Application_Resource_Exception('Bootstrap file found for module "' . $module . '" but bootstrap class "' . $bootstrapClass . '" not found');
						}
					}
					else
					{
						continue;
					}
				}

				$moduleBootstrap = new $bootstrapClass($bootstrap);

				$moduleBootstrap->bootstrap();
				$this->_bootstraps[$module] = $moduleBootstrap;
			}
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
