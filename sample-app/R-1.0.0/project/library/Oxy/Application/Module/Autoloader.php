<?php
/**
* Resource loader for application module classes
*
* @category Oxy
* @package Application
* @author Tomas Bartkus
* @version 1.0
**/
class Oxy_Application_Module_Autoloader extends Oxy_Loader_Autoloader_Resource
{
    /**
     * Constructor
     *
     * @param  array|Zend_Config $options
     * @return void
     */
    public function __construct($mixOptions)
    {
        parent::__construct($mixOptions);
        $this->initDefaultResourceTypes();
    }

    /**
     * Initialize default resource types for module resource classes
     *
     * @return void
     */
    public function initDefaultResourceTypes()
    {
        $basePath = $this->getBasePath();
        $this->addResourceTypes(array(
            'model'   => array(
                'namespace' => 'Model',
                'path'      => 'models',
            ),
            'plugin'  => array(
                'namespace' => 'Plugin',
                'path'      => 'plugins',
            ),
            'form'  => array(
                'namespace' => 'Form',
                'path'      => 'forms',
            ),
            'domain'  => array(
                'namespace' => 'Domain',
                'path'      => 'domain',
            ),
            'viewhelper' => array(
                'namespace' => 'View_Helper',
                'path'      => 'views/helpers',
            ),
            'viewfilter' => array(
                'namespace' => 'View_Filter',
                'path'      => 'views/filters',
            ),
        ));
        $this->setDefaultResourceType('model');
    }
}
