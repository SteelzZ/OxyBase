<?php
/**
 * Oxy router loader
 *
 * @category Oxy
 * @package Oxy_Application
 * @subpackage Resources
 * @author Tomas Bartkus <to.bartkus@gmail.com>
 **/
class Oxy_Application_Resource_Router
    extends Zend_Application_Resource_ResourceAbstract
{
    /**
     * @var Oxy_Controller_Router_Rewrite
     */
    protected $_router;

    /**
     * Defined by Zend_Application_Resource_Resource
     *
     * @return Oxy_Controller_Router_Rewrite
     */
    public function init()
    {
        return $this->getRouter();
    }

    /**
     * Retrieve router object
     *
     * @return Zend_Controller_Router_Rewrite
     */
    public function getRouter()
    {
        if (null === $this->_router) {
            $bootstrap = $this->getBootstrap();
            $bootstrap->bootstrap('Frontcontroller');
            $front = $bootstrap->getResource('Frontcontroller');
            $this->_router = $front->getRouter();

            $options = $this->getOptions();
            if (!isset($options['routes'])) {
                $options['routes'] = array();
            }

            if (isset($options['chainNameSeparator'])) {
                $this->_router->setChainNameSeparator($options['chainNameSeparator']);
            }

            if (isset($options['useRequestParametersAsGlobal'])) {
                $this->_router->useRequestParametersAsGlobal($options['useRequestParametersAsGlobal']);
            }

            $this->_router->addConfig(new Zend_Config($options['routes']));
        }
        return $this->_router;
    }
}
