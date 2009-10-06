<?php

/**
 * Oxy base for module bootstrap
 *
 * @category Oxy
 * @package Application
 * @author Tomas Bartkus
 * @version 1.0
 **/
class Oxy_Application_Module_Bootstrap extends Zend_Application_Module_Bootstrap
{

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

        $key = strtolower($this->getModuleName());

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
            $this->registerPluginResource($application->getPluginResource('Frontcontroller'),
            							  $arr_options['resources']['Frontcontroller']);
        }

        // ZF-6545: prevent recursive registration of modules
        if ($this->hasPluginResource('Modules')) {
            $this->unregisterPluginResource('Modules');
        }
    }

    /**
     * Register module plugins
     *
     * @param Array $arr_options
     * @return void
     */
    public function registerPlugins(Array $arr_options = array())
    {
    	if(isset($arr_options['plugins']))
    	{
	    	foreach ((array) $arr_options['plugins'] as $pluginClass)
			{
				$plugin = new $pluginClass();
				$this->getApplication()->getResource('Frontcontroller')->registerPlugin($plugin);
			}
    	}
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
            $this->setResourceLoader(new Oxy_Application_Module_Autoloader(array(
                'namespace' => $this->getDomainName() . '_' . $this->getModuleName(),
                'basePath'  => dirname($path)
            )));
        }
        return $this->_resourceLoader;
    }

	/**
	 * Retrieve module name
	 *
	 * @return string
	 */
	public function getModuleName()
	{
		if (empty($this->_moduleName))
		{
			$class = get_class($this);
			if (preg_match('/_([a-z][a-z0-9]*)_/i', $class, $matches))
			{
				$prefix = $matches[1];
			}
			else
			{
				$prefix = $class;
			}
			$this->_moduleName = $prefix;
		}
		return $this->_moduleName;
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