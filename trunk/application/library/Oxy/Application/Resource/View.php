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
    	$this->getBootstrap()->bootstrap('Frontcontroller');

        // Retrieve the front controller from the bootstrap registry
        $obj_front = $this->getBootstrap()->getResource('Frontcontroller');
		$obj_request = $obj_front->getRequest();

		$arr_paths = array();
		$str_suffix = 'tpl';
		$arr_skin = array();
		$bl_no_skin = false;
        foreach ($this->getOptions() as $key => $value)
    	{
           switch (strtolower($key))
           {
           		case 'helperpaths':
           			$arr_paths = (array) $value;
           			break;
           		case 'suffix':
           			$str_suffix = (string) $value;
               	break;
           		case 'no_skin':
           			$bl_no_skin = (boolean) $value;
               	break;
           		case 'skins':
           			if (is_array($value))
           			{
                        foreach ($value as $str_domain => $str_skin)
                        {
                            $arr_skin[$str_domain] = $str_skin;
                        }
                    }
                    else
                    {
                        $bl_no_skin = true;
                    }
               	break;
           }
        }

        $obj_router = $obj_front->getRouter();
		$obj_request = $obj_router->route($obj_request);

        $obj_view_renderer = new Oxy_Controller_Action_Helper_ViewRenderer();
        $obj_view = $this->getView();

        if($bl_no_skin)
        {

            $obj_view_renderer->setViewScriptPathSpec(':controller/:action.:suffix');
            $obj_view_renderer->setView($obj_view);
            $obj_view_renderer->setViewSuffix($str_suffix);

            Zend_Controller_Action_HelperBroker::addHelper($obj_view_renderer);
            return $obj_view;
        }
        else
        {
            $obj_router = $obj_front->getRouter();
    		$obj_request = $obj_front->getRequest();
    		$obj_request = $obj_router->route($obj_request);

            foreach ($arr_paths as $str_prefix => $str_path)
    		{
    			$obj_view->addHelperPath($str_path, $str_prefix);
    		}

    		if(!isset($arr_skin[$obj_request->getDomainName()]))
    		{
    			$arr_skin[$obj_request->getDomainName()] = 'oxy';
    		}

    		$obj_view->assign('skin', $arr_skin[$obj_request->getDomainName()]);

            $obj_view_renderer->setViewScriptPathSpec($arr_skin[$obj_request->getDomainName()] . '/:controller/:action.:suffix');
            $obj_view_renderer->setView($obj_view);
            $obj_view_renderer->setViewSuffix($str_suffix);

            Zend_Controller_Action_HelperBroker::addPrefix('Oxy_Controller_Action_Helper');
            Zend_Controller_Action_HelperBroker::addHelper($obj_view_renderer);
            return $obj_view;
        }
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
