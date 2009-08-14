<?php
/**
 * Layout resource
 *
 * @category   Oxy
 * @package    Oxy_Application
 * @subpackage Resource
 * @author Tomas Bartkus
 */
class Oxy_Application_Resource_Layout extends Zend_Application_Resource_ResourceAbstract
{
	/**
     * Initialize layout
     *
     * @return Zend_Layout
     */
    public function init()
    {
    	// Dependency
    	$this->getBootstrap()->bootstrap('Frontcontroller');
    	$this->getBootstrap()->bootstrap('View');

    	// Retrieve the front controller from the bootstrap registry
        $obj_front = $this->getBootstrap()->getResource('Frontcontroller');
        $obj_router = $obj_front->getRouter();
		$obj_request = $obj_front->getRequest();
		$obj_request = $obj_router->route($obj_request);

    	// Retrieve the view from the bootstrap registry
        $obj_view = $this->getBootstrap()->getResource('View');

        // Defaults
		$arr_layouts = array();
		$str_layouts_dir = 'layouts';
		$str_base_include_path = '../application';
		$str_base_url = '';
		$str_suffix = '';
   		foreach ($this->getOptions() as $key => $value)
    	{
           switch (strtolower($key))
           {
           		case 'domains':
           			if (is_array($value))
           			{
                        foreach ($value as $str_domain => $arr_data)
                        {
                        	$arr_layouts[$str_domain]['path'] = $arr_data['layout_path'];
                        	$arr_layouts[$str_domain]['script'] = $arr_data['layout_name'];
                        }
                    }
               	break;
           		case 'layouts_dir':
           			$str_layouts_dir = (string) $value;
           			break;
           		case 'base_include_path':
           			$str_base_include_path = (string) $value;
           			break;
           		case 'base_url':
           			$str_base_url = (string) $value;
           			break;
           		case 'suffix':
           			$str_suffix = (string) $value;
           			break;
           }
        }

        // Create new inflector
		$obj_inflector = new Zend_Filter_Inflector(':script.:suffix');
		$obj_inflector->addRules(array(':script' => new Zend_Filter_Word_CamelCaseToUnderscore(),
		                               'suffix'  => $str_suffix
		                              )
		                         );

		$str_path = '';
		$str_index = '';
		if(is_null($obj_request->getDomainName()))
		{
			$str_path = $arr_layouts['frontend']['path'];
			$str_index = $arr_layouts['frontend']['script'];
		}
		else
		{
			$str_path = $arr_layouts[$obj_request->getDomainName()]['path'];
			$str_index = $arr_layouts[$obj_request->getDomainName()]['script'];
		}

		$obj_layout = Zend_Layout::startMvc(array(
		    'view'       => $obj_view,
		    'layoutPath' => $str_path .
							$obj_view->skin .
							DIRECTORY_SEPARATOR .
							$str_layouts_dir .
							DIRECTORY_SEPARATOR,
		    'layout'     => $str_index
		));

		// Add layout directory for smarty
		array_unshift($obj_view->getEngine()->template_dir, $obj_layout->getLayoutPath());

		$obj_layout->base_url = $str_base_url;
		$obj_layout->setInflector($obj_inflector);

		//print_r($obj_layout);

		return $obj_layout;
    }
}
