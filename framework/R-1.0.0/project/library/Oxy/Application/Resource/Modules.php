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
        $bootstrap->bootstrap('Router');
        $front = $bootstrap->getResource('Frontcontroller');
        $routedRequested = $front->getRouter()->route($front->getRequest());
        $currentDomain = $routedRequested->getDomainName();
        $currentModule = $routedRequested->getModuleName();
        $domains = $front->getControllerDirectory();
        $default = $front->getDefaultModule();
                
        foreach ($domains as $domain => $modules){
            foreach (array_keys($modules) as $strModule){
                $bootstrapClass = $this->_formatModuleName($domain) . '_' .
                                  $this->_formatModuleName($strModule) . '_Bootstrap';

                if (! class_exists($bootstrapClass, false)){
                    $bootstrapPath = $front->getDomainDirectory($domain, $strModule) . '/Bootstrap.php';
                    if (file_exists($bootstrapPath)){
                        include_once $bootstrapPath;
                        if (! class_exists($bootstrapClass, false)){
                            throw new Zend_Application_Resource_Exception('Bootstrap file found for module "' . $strModule . '" but bootstrap class "' . $bootstrapClass . '" not found');
                        }
                    } else {
                        continue;
                    }
                }

                $moduleBootstrap = new $bootstrapClass($bootstrap);

                if(strtolower($domain) === strtolower(Oxy_Utils_String::dashToCamelCase($currentDomain))){
                    $moduleBootstrap->setIsCurrent();
                }

                $moduleBootstrap->bootstrap();
                $this->_bootstraps[$strModule] = $moduleBootstrap;
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
