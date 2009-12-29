<?php
/**
 * Dwoo resource
 *
 * @category   Oxy
 * @package    Oxy_Application
 * @subpackage Resource
 * @author Tomas Bartkus <to.bartkus@gmail.com>
 */
class Oxy_Application_Resource_Dwoo extends Zend_Application_Resource_ResourceAbstract
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
    	$this->getBootstrap()->bootstrap('Conf');
    	$this->getBootstrap()->bootstrap('Domains');
    	$this->getBootstrap()->bootstrap('Modules');

    	// Retrieve the front controller from the bootstrap registry
        $objFront = $this->getBootstrap()->getResource('Frontcontroller');
        $objConf = $this->getBootstrap()->getResource('Conf');
        $arrModulesConf = $this->getBootstrap()->getResource('Modules');
        $arrDomainsConf = $this->getBootstrap()->getResource('Domains');

		$objRequest = $objFront->getRequest();

		$arrParams = array();
		$arrPaths = array();
		$strSuffix = 'tpl';
		$strPluginsDir = '';
		$arrSkin = array();
		$blNoSkin = false;
   		foreach ($this->getOptions() as $strKey => $mixValue)
    	{
           switch (strtolower($strKey))
           {
           		case 'params':
           			$arrParams = (array) $mixValue;
           			break;
           		case 'helperpaths':
           			$arrPaths = (array) $mixValue;
           			break;
           		case 'suffix':
           			$strSuffix = (string) $mixValue;
               	    break;
           		case 'pluginsdir':
           			$strPluginsDir = (string) $mixValue;
               	    break;
                case 'no_skin':
           			$blNoSkin = (boolean) $mixValue;
               	    break;
           		case 'skins':
           			if (is_array($mixValue))
           			{
                        foreach ($mixValue as $str_domain => $str_skin)
                        {
                            $arrSkin[$str_domain] = $str_skin;
                        }
                    }
                    else
                    {
                        $blNoSkin = true;
                    }
               	    break;
           }
        }

        $objRouter = $objFront->getRouter();
		$objRequest = $objRouter->route($objRequest);

		// Create view object
		$objView = new Dwoo_Adapters_ZendFramework_View($arrParams);
		$objView->setPluginProxy(new Dwoo_Adapters_ZendFramework_PluginProxy(new Zend_View()));

		$objView->getEngine()->getLoader()->addDirectory($strPluginsDir);

		foreach ($arrPaths as $strPrefix => $strPath)
		{
			$objView->addHelperPath($strPath, $strPrefix);
		}

		if(!isset($arrSkin[$objRequest->getDomainName()]))
		{
			$arrSkin[$objRequest->getDomainName()] = 'oxy';
		}

		if(!$blNoSkin)
        {
		    $objView->assign('skin', $arrSkin[$objRequest->getDomainName()]);
        }

        if(isset($arrModulesConf[$objRequest->getModuleName()]))
        {
            $objModuleBootstrap = $arrModulesConf[$objRequest->getModuleName()];
            $arrDomain = $objModuleBootstrap->getApplication()->getOption($objRequest->getDomainName());
            $objView->assign('moduleConfig', $arrDomain[$objRequest->getModuleName()]);

        }
        $objView->assign('appConfig', $objConf);

		$arrRequestData = array(
		  'domain'     => $objRequest->getDomainName(),
		  'module'     => $objRequest->getModuleName(),
		  'controller' => $objRequest->getControllerName(),
		  'action'     => $objRequest->getActionName(),
		);

		$objView->assign('requestData', $arrRequestData);

		Zend_Controller_Action_HelperBroker::addPrefix('Oxy_Controller_Action_Helper');

		$objViewRenderer = new Oxy_Controller_Action_Helper_ViewRenderer();
		$objViewRenderer->setViewScriptPathSpec($arrSkin[$objRequest->getDomainName()] . '/:controller/:action.:suffix');
		$objViewRenderer->setView($objView);
		$objViewRenderer->setViewSuffix($strSuffix);

		Zend_Controller_Action_HelperBroker::addHelper($objViewRenderer);

		return $objView;
    }
}
