<?php

/**
 * Oxy base for domain bootstrap
 *
 * @category Oxy
 * @package Oxy_Application
 * @author Tomas Bartkus <to.bartkus@gmail.com>
 **/
class Oxy_Application_Domain_Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
	 /**
     * @var Zend_Loader_Autoloader_Resource
     */
    protected $_resourceLoader;

	/**
     * Constructor
     *
     * @param  Zend_Application|Zend_Application_Bootstrap_Bootstrapper $application
     * @return void
     */
    public function __construct($application)
    {
        $this->setApplication($application);

        // Use same plugin loader as parent bootstrap
        if ($application instanceof Zend_Application_Bootstrap_ResourceBootstrapper) {
            $this->setPluginLoader($application->getPluginLoader());
        }

        $key = strtolower($this->getDomainName());

        // Inject module config
        $r    = new ReflectionClass($this);
        $path = $r->getFileName();

        $config = new Zend_Config_Xml(dirname($path) . '/config/config.xml', $application->getApplication()->getEnvironment());

		$arr_options = $config->toArray();
    	if(!empty($arr_options[$key]))
		{
        	$application->setOptions($arr_options);
		}

  		$arr_options = $application->getOptions();

        if ($application->hasOption($key)) {
            // Don't run via setOptions() to prevent duplicate initialization
            $this->setOptions($application->getOption($key));
        }

        if ($application->hasOption('resourceloader')) {
            $this->setOptions(array(
                'resourceloader' => $application->getOption('resourceloader')
            ));
        }

        $this->initResourceLoader();

        // ZF-6545: ensure front controller resource is loaded
        if (!$this->hasPluginResource('Front')) {
            $this->registerPluginResource($application->getPluginResource('Front'),
            							  $arr_options['resources']['Front']);
        }

        // ZF-6545: prevent recursive registration of domains
        if ($this->hasPluginResource('Domains')) {
            $this->unregisterPluginResource('Domains');
        }
    }

 	/**
     * Set module resource loader
     *
     * @param  Zend_Loader_Autoloader_Resource $loader
     * @return Zend_Application_Module_Bootstrap
     */
    public function setResourceLoader(Zend_Loader_Autoloader_Resource $loader)
    {
        $this->_resourceLoader = $loader;
        return $this;
    }

	/**
     * Retrieve module resource loader
     *
     * @return Zend_Loader_Autoloader_Resource
     */
    public function getResourceLoader()
    {
        if (null === $this->_resourceLoader) {
            $r    = new ReflectionClass($this);
            $path = $r->getFileName();
            $this->setResourceLoader(new Oxy_Application_Domain_Autoloader(array(
                'namespace' => $this->getDomainName(),
                'basePath'  => dirname($path)
            )));
        }
        return $this->_resourceLoader;
    }

    /**
     * Ensure resource loader is loaded
     *
     * @return void
     */
    public function initResourceLoader()
    {
        $this->getResourceLoader();
    }


	/**
	 * Retrieve domain name
	 *
	 * @return string
	 */
	public function getDomainName()
	{
		if (empty($this->_domainName))
		{
			$class = get_class($this);
			if (preg_match('/^([a-z][a-z0-9]*)_/i', $class, $matches))
			{
				$prefix = $matches[1];
			}
			else
			{
				$prefix = $class;
			}

			$this->_domainName = $prefix;
		}
		return $this->_domainName;
	}
}
?>