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
     * Is requested module the same
     * that we are boostrapping at the moment
     *
     * @var Boolean
     */
    protected $_blIsCurrent = false;

    /**
     * Get is current
     *
     * @return Boolean
     */
    public function isCurrent()
    {
        return (boolean)$this->_blIsCurrent;
    }

    /**
     * Set is current
     *
     * @return Boolean
     */
    public function setIsCurrent($blCurrent = true)
    {
        $this->_blIsCurrent = (boolean)$blCurrent;
    }

     /**
     * Constructor
     *
     * @param  Zend_Application|Zend_Application_Bootstrap_Bootstrapper $application
     * @return void
     */
    public function __construct($objApplication)
    {
        $this->setApplication($objApplication);

        // Use same plugin loader as parent bootstrap
        if ($objApplication instanceof Zend_Application_Bootstrap_ResourceBootstrapper) {
            $this->setPluginLoader($objApplication->getPluginLoader());
        }

        $strModuleKey = strtolower($this->getModuleName());
        $strDomainKey = strtolower($this->getDomainName());

        // Inject module config
        $objRefClass    = new ReflectionClass($this);
        $strPath = $objRefClass->getFileName();

        $objConfig = new Zend_Config_Xml(dirname($strPath) . '/config/config.xml',
                                         $objApplication->getApplication()->getEnvironment());

          $arrModuleOptions = $objConfig->toArray();

          $arrOptions = $objApplication->getOptions();
         if(empty($arrOptions[$strDomainKey][$strModuleKey]) && !empty($arrModuleOptions))
          {
             $objApplication->setOptions(array($strDomainKey => array($strModuleKey => $arrModuleOptions)));
          }

            $arrOptions = $objApplication->getOptions();

        if ($objApplication->hasOption($strModuleKey)) {
            // Don't run via setOptions() to prevent duplicate initialization
            $this->setOptions($objApplication->getOption($strModuleKey));
        }

        if ($objApplication->hasOption('resourceloader')) {
            $this->setOptions(array(
                'resourceloader' => $objApplication->getOption('resourceloader')
            ));
        }

        $this->initResourceLoader();

        // ZF-6545: ensure front controller resource is loaded
        if (!$this->hasPluginResource('Front')) {
            $this->registerPluginResource($objApplication->getPluginResource('Frontcontroller'),
                                                 $arrOptions['resources']['Frontcontroller']);
        }

        // ZF-6545: prevent recursive registration of modules
        if ($this->hasPluginResource('Modules')) {
            $this->unregisterPluginResource('Modules');
        }
    }

    /**
     * Register module plugins
     *
     * @param Array $arrOptions
     * @return void
     */
    public function registerPlugins(Array $arrOptions = array())
    {
         if(isset($arrOptions['plugins']) && is_array($arrOptions['plugins']) && !empty($arrOptions['plugins']))
         {
            /*
                Normalize structure.
                Zend XML configuration loader loads configurations in two different ways,
                when a different number of children exists:

                - When 1 child is found, the structure is:
                array(
                    'attribute_name1' => 'attribute_value1',
                    'attribute_name2' => 'attribute_value2'
                )

                - When more than 1 child is found, the structure is:
                array(
                    0 => array(
                        'attribute_name1' => 'attribute_value1',
                        'attribute_name2' => 'attribute_value2'
                    ),
                    1 => array(
                        'attribute_name1' => 'attribute_value1',
                        'attribute_name2' => 'attribute_value2'
                    )
                )
            */
             $arrPluginsCollection = array();
             if(isset($arrOptions['plugins']['plugin']['name']))
             {
                 $arrPluginsCollection[] = $arrOptions['plugins']['plugin'];
             }
             else
             {
                 $arrPluginsCollection = $arrOptions['plugins']['plugin'];
             }

             // Loop each plugin
              foreach((array)$arrPluginsCollection as $arrPluginData)
               {
                   if(isset($arrPluginData['name']))
                   {
                       // Check if plugin is set as an active. If not, it won't be loaded.
                       $blIsActive = (boolean)(isset($arrPluginData['isActive']) ? $arrPluginData['isActive'] : true);
                       $blLoadAlways = (boolean)(isset($arrPluginData['loadAlways']) ? $arrPluginData['loadAlways'] : true);

                       if($blIsActive && ($blLoadAlways || $this->isCurrent()))
                       {
                            $strPlugin = new $arrPluginData['name']();
                            $this->getApplication()->getResource('Frontcontroller')->registerPlugin($strPlugin);
                       }
                   }
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