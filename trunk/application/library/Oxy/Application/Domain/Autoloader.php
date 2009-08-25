<?php
/**
* Resource loader for application domain classes
*
* @category Oxy
* @package Oxy_Application
* @subpackage Domain
* @author Tomas Bartkus <to.bartkus@gmail.com>
**/
class Oxy_Application_Domain_Autoloader extends Oxy_Loader_Autoloader_Resource
{
    /**
     * Constructor
     *
     * @param  array|Zend_Config $options
     * @return void
     */
    public function __construct($options)
    {
        parent::__construct($options);
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
                'namespace' => 'Lib',
                'path'      => 'library',
            )
        ));
        $this->setDefaultResourceType('model');
    }
}
