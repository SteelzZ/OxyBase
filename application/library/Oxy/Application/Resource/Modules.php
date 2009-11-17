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
		$objBootstrap = $this->getBootstrap();
		$objBootstrap->bootstrap('Frontcontroller');
		$objFront = $objBootstrap->getResource('Frontcontroller');
		$strCurrentDomain = $objFront->getRequest()->getDomainName();
		$strCurrentModule = $objFront->getRequest()->getModuleName();
		$arrDomains = $objFront->getControllerDirectory();
		$default = $objFront->getDefaultModule();
		foreach ($arrDomains as $strDomain => $arrModules)
		{
			foreach (array_keys($arrModules) as $strModule)
			{
				$strBootstrapClass = $this->_formatModuleName($strDomain) . '_' .
								  $this->_formatModuleName($strModule) . '_Bootstrap';

				if (! class_exists($strBootstrapClass, false))
				{
					$strBootstrapPath = $objFront->getDomainDirectory($strDomain, $strModule) . '/Bootstrap.php';
					if (file_exists($strBootstrapPath))
					{
						include_once $strBootstrapPath;
						if (! class_exists($strBootstrapClass, false))
						{
							throw new Zend_Application_Resource_Exception('Bootstrap file found for module "' . $strModule . '" but bootstrap class "' . $strBootstrapClass . '" not found');
						}
					}
					else
					{
						continue;
					}
				}

				$objModuleBootstrap = new $strBootstrapClass($objBootstrap);

				if($strDomain == $strCurrentDomain && $strModule == $strCurrentModule)
				{
				    $objModuleBootstrap->setIsCurrent();
				}

				$objModuleBootstrap->bootstrap();
				$this->_bootstraps[$strModule] = $objModuleBootstrap;
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
