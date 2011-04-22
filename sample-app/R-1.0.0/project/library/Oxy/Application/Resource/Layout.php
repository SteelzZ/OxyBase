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
    	$this->getBootstrap()->bootstrap('Dwoo');

    	// Retrieve the front controller from the bootstrap registry
        $front = $this->getBootstrap()->getResource('Frontcontroller');

        // Defaults
		$layouts = array();
		$layoutsDir = 'layouts';
		$baseIncludePath = '../application';
		$baseUrl = '';
		$str_suffix = '';
		$hasNoSkin = false;
		$sharedPath = 'shared';
   		foreach ($this->getOptions() as $key => $value)
    	{
           switch (strtolower($key))
           {
               case 'no_skin':
           			$hasNoSkin = (boolean) $value;
               	break;
           		case 'domains':
           			if (is_array($value))
           			{
                        foreach ($value as $domain => $arr_data)
                        {
                            //$domain = Msc_Utils_String::dashToCamelCase($domain);
                        	$layouts[$domain]['path'] = $arr_data['layout_path'];
                        	$layouts[$domain]['script'] = $arr_data['layout_name'];
                        }
                    }
               	break;
           		case 'layouts_dir':
           			$layoutsDir = (string) $value;
           			break;
           		case 'base_include_path':
           			$baseIncludePath = (string) $value;
           			break;
           		case 'base_url':
           			$baseUrl = (string) $value;
           			break;
           		case 'google_api_key':
           			$googleApiKey = (string)$value;
           			break;
           		case 'google_api_version':
           			$googleApiVersion = (string)$value;
           			break;
           		case 'suffix':
           			$str_suffix = (string) $value;
           			break;
           		case 'shared_path':
           			$sharedPath = (string) $value;
           			break;
           }
        }

		$path = $baseIncludePath . DIRECTORY_SEPARATOR;
		$index = '';

		if(is_null($front->getRequest()->getDomainName()))
		{
			$path = $layouts['eshop']['path'];
			$index = $layouts['eshop']['script'];
		}
		else
		{
		    $requestedDomainName = strtolower($front->getRequest()->getDomainName());
			$path .= Msc_Utils_String::dashToCamelCase($layouts[$requestedDomainName]['path']);
			$index = $layouts[$requestedDomainName]['script'];
		}

		Zend_Controller_Action_HelperBroker::addPrefix('Oxy_Controller_Action_Helper');
		$viewRenderer = Zend_Controller_Action_HelperBroker::getStaticHelper('ViewRenderer');
		if(!isset($viewRenderer->view->base_url)){
		  $viewRenderer->view->base_url = $baseUrl;
		}

		$viewRenderer->view->google_api_key = $googleApiKey;
		$viewRenderer->view->google_api_version = $googleApiVersion;
		
		if(!isset($viewRenderer->view->skin))
		{
		    $hasNoSkin = true;
		}

		$path .= DIRECTORY_SEPARATOR . $layoutsDir . DIRECTORY_SEPARATOR;

		if(!$hasNoSkin)
		{
		    $path .= $viewRenderer->view->skin . DIRECTORY_SEPARATOR;
		}

		// Add shared script path
		$viewRenderer->view->addScriptPath($path . $sharedPath);

		$includePaths = array(
		  'templateFile' => array(
		      'includePath' => array(
    		      '',
    		      $path,
    		      $path . $sharedPath
		      )
		  )
		);

		// Includes
		$viewRenderer->view->setOptions($includePaths);

		$layout = Zend_Layout::startMvc(array(
		    'view'       => $viewRenderer->view,
		    'layoutPath' => $path,
		    'layout'     => $index
		));

		// Create new inflector
		$inflector = new Zend_Filter_Inflector(':script.:suffix');
		$inflector->addRules(array(':script' => new Zend_Filter_Word_CamelCaseToUnderscore(),
		                               'suffix'  => $str_suffix
		                              )
		                         );

		$layout->setInflector($inflector);

		return $layout;
    }
}
