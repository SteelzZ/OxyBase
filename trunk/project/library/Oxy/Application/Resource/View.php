<?php
/**
 * View resource
 *
 * @category   Oxy
 * @package    Oxy_Application
 * @subpackage Resource
 * @author Tomas Bartkus
 */
class Oxy_Application_Resource_View extends Zend_Application_Resource_ResourceAbstract
{

    /**
     * @var Zend_View_Interface
     */
    protected $_view;

    /**
     * Defined by Zend_Application_Resource_Resource
     *
     * @return Zend_View
     */
    public function init()
    {
        // Dependency
        //$this->getBootstrap()->bootstrap('Frontcontroller');
        // Retrieve the front controller from the bootstrap registry
        //$front = $this->getBootstrap()->getResource('Frontcontroller');
        //$request = $front->getRequest();
        $paths = array();
        $suffix = 'tpl';
        //$arr_skin = array();
        //$bl_no_skin = false;
        foreach ($this->getOptions() as $key => $value) {
            switch (strtolower($key)) {
                case 'helperpaths':
                    $paths = (array) $value;
                    break;
                case 'suffix':
                    $suffix = (string) $value;
                    break;
                /*case 'no_skin':
                    $bl_no_skin = (boolean) $value;
                    break;
                case 'skins':
                    if (is_array($value)) {
                        foreach ($value as $str_domain => $str_skin) {
                            $arr_skin[$str_domain] = $str_skin;
                        }
                    } else {
                        $bl_no_skin = true;
                    }
                    break;*/
            }
        }
        //$objRouter = $front->getRouter();
        //$request = $objRouter->route($request);
        $viewRenderer = new Oxy_Controller_Action_Helper_ViewRenderer();
        $view = $this->getView();
        //if ($bl_no_skin) {
            $viewRenderer->setViewScriptPathSpec(':controller/:action.:suffix');
            $viewRenderer->setView($view);
            $viewRenderer->setViewSuffix($suffix);
            Zend_Controller_Action_HelperBroker::addHelper($viewRenderer);
            return $view;
        /*} else {
            $objRouter = $front->getRouter();
            $request = $front->getRequest();
            $request = $objRouter->route($request);
            foreach ($paths as $str_prefix => $str_path) {
                $view->addHelperPath($str_path, $str_prefix);
            }
            if (! isset($arr_skin[$request->getDomainName()])) {
                $arr_skin[$request->getDomainName()] = 'oxy';
            }
            $view->assign('skin', $arr_skin[$request->getDomainName()]);
            $viewRenderer->setViewScriptPathSpec($arr_skin[$request->getDomainName()] . '/:controller/:action.:suffix');
            $viewRenderer->setView($view);
            $viewRenderer->setViewSuffix($suffix);
            Zend_Controller_Action_HelperBroker::addPrefix('Oxy_Controller_Action_Helper');
            Zend_Controller_Action_HelperBroker::addHelper($viewRenderer);
            return $view;
        }*/
    }

    /**
     * Retrieve view object
     *
     * @return Zend_View
     */
    public function getView()
    {
        if (null === $this->_view) {
            $this->_view = new Zend_View($this->getOptions());
        }
        return $this->_view;
    }
}
