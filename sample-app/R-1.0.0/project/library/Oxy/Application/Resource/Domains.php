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
	 * @return array
	 * @throws Zend_Application_Resource_Exception When bootstrap class was not found
	 */
	public function init()
	{
		$objBootstrap = $this->getBootstrap();
				
	    $options = $this->getOptions();
        if(!isset($options['path'])){
            throw new Oxy_Application_Exception('Domains resource requires path param to be defined in config!');
        } else {
            $path = $options['path'];
        }
		
        try {
            $dir = new DirectoryIterator($path);
        } catch (Exception $e) {
            throw new Oxy_Application_Exception(
                "Directory $path not readable"
            );
        }
        
        foreach ($dir as $file) {
            if ($file->isDot() || ! $file->isDir()) {
                continue;
            }
            if ($file->isDir()) {
                $domain = $file->getFilename();
                // Don't use SCCS directories as modules
                if (preg_match('/^[^a-z]/i', $domain) || ('CVS' == $domain)) {
                    continue;
                }
                
                $strBootstrapClass = $this->_formatModuleName($domain) . '_Bootstrap';
                
                if (!class_exists($strBootstrapClass, false)){
                    $strBootstrapPath = $file->getPathname() . DIRECTORY_SEPARATOR . 'Bootstrap.php';
                    
                    if (file_exists($strBootstrapPath)){
                        include_once $strBootstrapPath;
    
                        if (! class_exists($strBootstrapClass, false)){
                            throw new Zend_Application_Resource_Exception('Bootstrap file found for domain "' . $domain . '" but bootstrap class "' . $strBootstrapClass . '" not found');
                        }
                    } else {
                        continue;
                    }
                    
                    $objDomainBootstrap = new $strBootstrapClass($objBootstrap);

                    $objDomainBootstrap->bootstrap();
                    $this->_bootstraps[$domain] = $objDomainBootstrap;
                    
                    
                    // After successfull domain bootstrap go for modules
                    $modulesDir = $file->getPathname() . DIRECTORY_SEPARATOR . 'interface';
                    $modulesDir = new DirectoryIterator($modulesDir);
                    foreach ($modulesDir as $moduleFile) {
                        if ($moduleFile->isDot() || ! $moduleFile->isDir()) {
                            continue;
                        }
                        
                        $module = $moduleFile->getFilename();
                        // Don't use SCCS directories as modules
                        if (preg_match('/^[^a-z]/i', $module) || ('CVS' == $module)) {
                            continue;
                        }
                        
                        $bootstrapClass = $this->_formatModuleName($domain) . '_' .
                                          $this->_formatModuleName($module) . '_Bootstrap';

                        if (!class_exists($bootstrapClass, false)){
                            $bootstrapPath = $moduleFile->getPathname() . DIRECTORY_SEPARATOR . 'Bootstrap.php';
                            if (file_exists($bootstrapPath)){
                                include_once $bootstrapPath;
                                if (! class_exists($bootstrapClass, false)){
                                    throw new Zend_Application_Resource_Exception('Bootstrap file found for module "' . $module . '" but bootstrap class "' . $bootstrapClass . '" not found');
                                }
                            } else {
                                continue;
                            }
                        }
        
                        $moduleBootstrap = new $bootstrapClass($objBootstrap);
                
                        $moduleBootstrap->bootstrap();
                        $this->_bootstraps[$module] = $moduleBootstrap;
                    }
                }
                
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
	protected function _formatModuleName($strName)
	{
		$strName = strtolower($strName);
		$strName = str_replace(array('-' , '.'), ' ', $strName);
		$strName = ucwords($strName);
		$strName = str_replace(' ', '', $strName);
		return $strName;
	}
}
