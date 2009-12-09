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
		$arrSkin = array();
		$blCaching = false;
		$blCompileCheck = true;
		$blForceCompile = true;
		$strCacheDir = '/tmp';
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
           		case 'caching':
           			$blCaching = (bool) $mixValue;
           			break;
           		case 'compile_check':
           			$blCompileCheck = (bool) $mixValue;
           			break;
           		case 'force_compile':
           			$blForceCompile = (bool) $mixValue;
           			break;
           		case 'cache_dir':
           			$strCacheDir = (string) $mixValue;
           			break;
           		case 'suffix':
           			$strSuffix = (string) $mixValue;
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
		$objView = new Oxy_View_Smarty($arrParams);

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
            $arrModuleConfig = array();
            if(isset($arrDomain[$objRequest->getModuleName()]))
            {
                $arrModuleConfig = $arrDomain[$objRequest->getModuleName()];
            }

            $objView->assign('moduleConfig', $arrModuleConfig);

        }
        $objView->assign('appConfig', $objConf);


		$objView->getEngine()->caching = $blCaching;
		$objView->getEngine()->compile_check = $blCompileCheck;
		$objView->getEngine()->force_compile = $blForceCompile;
		$objView->getEngine()->cache_dir = $strCacheDir;

		$arrRequestData = array(
		  'domain'     => $objRequest->getDomainName(),
		  'module'     => $objRequest->getModuleName(),
		  'controller' => $objRequest->getControllerName(),
		  'action'     => $objRequest->getActionName(),
		);

		$objView->assign('requestData', $arrRequestData);

		Zend_Controller_Action_HelperBroker::addPrefix('Oxy_Controller_Action_Helper');
		//$obj_view_renderer = Zend_Controller_Action_HelperBroker::getStaticHelper('ViewRenderer');
		$obj_view_renderer = new Oxy_Controller_Action_Helper_ViewRenderer();
		$obj_view_renderer->setViewScriptPathSpec($arrSkin[$objRequest->getDomainName()] . '/:controller/:action.:suffix');
		$obj_view_renderer->setView($objView);
		$obj_view_renderer->setViewSuffix($strSuffix);
		Zend_Controller_Action_HelperBroker::addHelper($obj_view_renderer);

		return $objView;
    }
}
