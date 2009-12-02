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
		$objBootstrap = $this->getBootstrap();
		$objBootstrap->bootstrap('Frontcontroller');
		$objFront = $objBootstrap->getResource('Frontcontroller');
		$arrDomains = $objFront->getControllerDirectory();
		$strDefault = $objFront->getDefaultModule();
		foreach ($arrDomains as $strDomain => $arrModules)
		{
			$strBootstrapClass = $this->_formatModuleName($strDomain) . '_Bootstrap';

			if (!class_exists($strBootstrapClass, false))
			{
				$strBootstrapPath = $objFront->getDomainDirectory($strDomain) . '\Bootstrap.php';

				// Calculate domain part
				// @TODO refactor, remove hardcoded 'domains' string
				$arrData = explode('/',$strBootstrapPath);
				unset($arrData[count($arrData)-1]);
				$arrData[] = 'domains';
				$arrData[] = $strDomain;
				$strBootstrapPath = implode('/', $arrData);

				$strBootstrapPath = $strBootstrapPath . '/Bootstrap.php';
				if (file_exists($strBootstrapPath))
				{
					include_once $strBootstrapPath;

					if (! class_exists($strBootstrapClass, false))
					{
						throw new Zend_Application_Resource_Exception('Bootstrap file found for module "' . $strDomain . '" but bootstrap class "' . $strBootstrapClass . '" not found');
					}
				}
				else
				{
					continue;
				}
			}

			$objDomainBootstrap = new $strBootstrapClass($objBootstrap);

			$objDomainBootstrap->bootstrap();
			$this->_bootstraps[$strDomain] = $objDomainBootstrap;

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
