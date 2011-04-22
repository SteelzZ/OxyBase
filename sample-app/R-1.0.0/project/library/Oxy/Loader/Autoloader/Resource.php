<?php
/**
* Resource autoloader
*
* @category Oxy
* @package Oxy_Loader
* @subpackage Autoloader
* @author Tomas Bartkus
**/
class Oxy_Loader_Autoloader_Resource extends Zend_Loader_Autoloader_Resource
{
	/**
     * Constructor
     *
     * @param  array|Zend_Config $options Configuration options for resource autoloader
     * @return void
     */
    public function __construct($options)
    {
        if ($options instanceof Zend_Config) {
            $options = $options->toArray();
        }
        if (!is_array($options)) {
            require_once 'Zend/Loader/Exception.php';
            throw new Zend_Loader_Exception('Options must be passed to resource loader constructor');
        }

        $this->setOptions($options);

        $namespace = $this->getNamespace();
        if ((null === $namespace)
            || (null === $this->getBasePath())
        ) {
            require_once 'Zend/Loader/Exception.php';
            throw new Zend_Loader_Exception('Resource loader requires both a namespace and a base path for initialization');
        }

        if (!empty($namespace)) {
            $namespace .= '_';
        }
        Oxy_Loader_Autoloader::getInstance()->unshiftAutoloader($this, $namespace);
    }
	/**
     * Attempt to autoload a class
     *
     * @param  string $class
     * @return mixed False if not matched, otherwise result if include operation
     */
    public function autoload($class)
    {
        $segments          = explode('_', $class);
        $namespaceTopLevel = $this->getNamespace();
        $namespace         = '';

        if (!empty($namespaceTopLevel)) {
            $namespace = array_shift($segments) . '_' . array_shift($segments);
            if ($namespace != $this->getNamespace()) {
                // wrong prefix? we're done
                return false;
            }
        }

        if (count($segments) < 2) {
            // assumes all resources have a component and class name, minimum
            return false;
        }

        $final     = array_pop($segments);
        $component = $namespace;
        $lastMatch = false;
        do {
            $segment    = array_shift($segments);
            $component .= empty($component) ? $segment : '_' . $segment;
            if (isset($this->_components[$component])) {
                $lastMatch = $component;
            }
        } while (count($segments));

        if (!$lastMatch) {
            return false;
        }

        $final = substr($class, strlen($lastMatch));
        $path = $this->_components[$lastMatch];
        return include $path . '/' . str_replace('_', '/', $final) . '.php';
    }
}
?>