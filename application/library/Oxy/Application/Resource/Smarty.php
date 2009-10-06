<?php
/**
 * Smarty resource
 *
 * @category   Oxy
 * @package    Oxy_Application
 * @subpackage Resource
 * @author Tomas Bartkus <to.bartkus@gmail.com>
 */
class Oxy_Application_Resource_Smarty extends Zend_Application_Resource_ResourceAbstract
{
	/**
     * Initialize view
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

		$arr_params = array();
		$arr_paths = array();
		$str_suffix = 'tpl';
		$arr_skin = array();
		$bl_caching = false;
		$bl_compile_check = true;
		$bl_force_compile = true;
		$str_cache_dir = '/tmp';
		$bl_no_skin = false;
   		foreach ($this->getOptions() as $key => $value)
    	{
           switch (strtolower($key))
           {
           		case 'params':
           			$arr_params = (array) $value;
           			break;
           		case 'helperpaths':
           			$arr_paths = (array) $value;
           			break;
           		case 'caching':
           			$bl_caching = (bool) $value;
           			break;
           		case 'compile_check':
           			$bl_compile_check = (bool) $value;
           			break;
           		case 'force_compile':
           			$bl_force_compile = (bool) $value;
           			break;
           		case 'cache_dir':
           			$str_cache_dir = (string) $value;
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

		// Create view object
		$obj_view = new Oxy_View_Smarty($arr_params);

		foreach ($arr_paths as $str_prefix => $str_path)
		{
			$obj_view->addHelperPath($str_path, $str_prefix);
		}

		if(!isset($arr_skin[$obj_request->getDomainName()]))
		{
			$arr_skin[$obj_request->getDomainName()] = 'oxy';
		}

		if(!$bl_no_skin)
        {
		    $obj_view->assign('skin', $arr_skin[$obj_request->getDomainName()]);
        }

		$obj_view->getEngine()->caching = $bl_caching;
		$obj_view->getEngine()->compile_check = $bl_compile_check;
		$obj_view->getEngine()->force_compile = $bl_force_compile;
		$obj_view->getEngine()->cache_dir = $str_cache_dir;

		Zend_Controller_Action_HelperBroker::addPrefix('Oxy_Controller_Action_Helper');
		$obj_view_renderer = Zend_Controller_Action_HelperBroker::getStaticHelper('ViewRenderer');
		$obj_view_renderer->setViewScriptPathSpec($arr_skin[$obj_request->getDomainName()] . '/:controller/:action.:suffix');
		$obj_view_renderer->setView($obj_view);
		$obj_view_renderer->setViewSuffix($str_suffix);

		return $obj_view;
    }
}
