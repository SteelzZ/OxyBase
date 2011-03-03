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
        $this->addResourceTypes(
            array(
                'lib' => array('namespace' => 'Lib' , 'path' => 'library'),
                'domain' => array('namespace' => 'Domain' , 'path' => 'domain'),
                'web-services' => array('namespace' => 'WebService' , 'path' => 'web-services'),
                'commands' => array('namespace' => 'Command' , 'path' => 'commands'),
                'commands-handlers' => array('namespace' => 'CommandHandler' , 'path' => 'commands/handlers')
            )
        
        );
        $this->setDefaultResourceType('lib');
    }

    /**
     * Attempt to autoload a class
     *
     * @param  string $class
     * @return mixed False if not matched, otherwise result if include operation
     */
    public function autoload($class)
    {
        $segments = explode('_', $class);
        $namespaceTopLevel = $this->getNamespace();
        $namespace = '';
        if (! empty($namespaceTopLevel))
        {
            $namespace = array_shift($segments);
            if ($namespace != $this->getNamespace())
            {
                return false;
            }
        }
       
        if (count($segments) < 2)
        {
            // assumes all resources have a component and class name, minimum
            return false;
        }
        $final = array_pop($segments);
        $component = $namespace;
        $lastMatch = false;
        do
        {
            $segment = array_shift($segments);
            $component .= empty($component) ? $segment : '_' . $segment;
            if (isset($this->_components[$component]))
            {
                $lastMatch = $component;
            }
        }
        while (count($segments));
        
        if (! $lastMatch)
        {
            return false;
        }
        
        $final = substr($class, strlen($lastMatch));
        $path = $this->_components[$lastMatch];
        return include_once $path . '/' . str_replace('_', '/', $final) . '.php';
    }
}
