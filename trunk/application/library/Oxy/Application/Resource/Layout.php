<?php
/**
 * Olayout resource
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

    	// Retrieve the front controller from the bootstrap registry
        $obj_front = $this->getBootstrap()->getResource('Frontcontroller');

        // Defaults
		$arr_layouts = array();
		$str_layouts_dir = 'layouts';
		$str_base_include_path = '../application';
		$str_base_url = '';
		$str_suffix = '';
		$bl_no_skin = false;
		$str_shared_path = 'shared';
   		foreach ($this->getOptions() as $key => $value)
    	{
           switch (strtolower($key))
           {
               case 'no_skin':
           			$bl_no_skin = (boolean) $value;
               	break;
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
           		case 'shared_path':
           			$str_shared_path = (string) $value;
           			break;
           }
        }

		$str_path = '';
		$str_index = '';
		if(is_null($obj_front->getRequest()->getDomainName()))
		{
			$str_path = $arr_layouts['frontend']['path'];
			$str_index = $arr_layouts['frontend']['script'];
		}
		else
		{
			$str_path = $arr_layouts[$obj_front->getRequest()->getDomainName()]['path'];
			$str_index = $arr_layouts[$obj_front->getRequest()->getDomainName()]['script'];
		}

		Zend_Controller_Action_HelperBroker::addPrefix('Oxy_Controller_Action_Helper');
		$obj_view_renderer = Zend_Controller_Action_HelperBroker::getStaticHelper('ViewRenderer');
		$obj_view_renderer->view->base_url = $str_base_url;

		if(!isset($obj_view_renderer->view->skin))
		{
		    $bl_no_skin = true;
		}

		$str_path .= DIRECTORY_SEPARATOR;

		if(!$bl_no_skin)
		{
		    $str_path .= $obj_view_renderer->view->skin . DIRECTORY_SEPARATOR;
		}

		// Add shared script path
		$obj_view_renderer->view->addScriptPath($str_path . $str_shared_path);

		$str_path .= $str_layouts_dir . DIRECTORY_SEPARATOR;



		$obj_layout = Zend_Layout::startMvc(array(
		    'view'       => $obj_view_renderer->view,
		    'layoutPath' => $str_path,
		    'layout'     => $str_index
		));

		// Create new inflector
		$obj_inflector = new Zend_Filter_Inflector(':script.:suffix');
		$obj_inflector->addRules(array(':script' => new Zend_Filter_Word_CamelCaseToUnderscore(),
		                               'suffix'  => $str_suffix
		                              )
		                         );

		$obj_layout->setInflector($obj_inflector);
		return $obj_layout;
    }
}
