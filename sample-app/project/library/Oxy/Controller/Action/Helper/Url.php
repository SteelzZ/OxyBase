<?php
/**
 * Extended Zend helper for creating URLs for redirects and other tasks
 *
 * @category Oxy
 * @package Oxy_Controller
 * @subpackage Action
 * @author Tomas Bartkus <to.bartkus@gmail.com>
 **/
class Oxy_Controller_Action_Helper_Url extends Zend_Controller_Action_Helper_Url
{
	/**
     * Retrieve front controller instance
     *
     * @return Oxy_Controller_Front
     */
    public function getFrontController()
    {
        if (null === $this->_frontController)
        {
            $this->_frontController = Oxy_Controller_Front::getInstance();
        }

        return $this->_frontController;
    }

    /**
     * Create URL based on default route
     *
     * @param  string $strAction
     * @param  string $strController
     * @param  string $strModule
     * @param  string $strDomain
     * @param  array  $params
     * @return string
     */
    public function simple($strAction,
                           $strController = null,
                           $strModule = null,
                           $strDomain = null,
                           Array $arrParams = null)
    {
        $objRequest = $this->getRequest();
        if (null === $strController)
        {
            $strController = $objRequest->getControllerName();
        }
        if (null === $strModule)
        {
            $strModule = $objRequest->getModuleName();
        }
        if (null === $strDomain)
        {
            $strDomain = $objRequest->getDomainName();
        }

        $strUrl = $strController . '/' . $strAction;

        if ($strModule != $this->getFrontController()->getDispatcher()->getDefaultModule())
        {
            $strUrl = $strModule . '/' . $strUrl;
        }

        if ($strDomain != $this->getFrontController()->getDispatcher()->getDefaultDomain())
        {
            $strUrl = $strDomain . '/' . $strUrl;
        }

        if ('' !== ($baseUrl = $this->getFrontController()->getBaseUrl()))
        {
            $strUrl = $baseUrl . '/' . $strUrl;
        }
        if (null !== $arrParams)
        {
            $arrParamPairs = array();
            foreach ($arrParams as $strKey => $mixValue)
            {
                $arrParamPairs[] = urlencode($strKey) . '/' . urlencode($mixValue);
            }
            $strParamString = implode('/', $arrParamPairs);
            $strUrl .= '/' . $strParamString;
        }
        $strUrl = '/' . ltrim($strUrl, '/');
        return $strUrl;
    }

    /**
     * Assembles a URL based on a given route
     *
     * This method will typically be used for more complex operations, as it
     * ties into the route objects registered with the router.
     *
     * @param  array   $urlOptions Options passed to the assemble method of the Route object.
     * @param  mixed   $name       The name of a Route to use. If null it will use the current Route
     * @param  boolean $reset
     * @param  boolean $encode
     * @return string Url for the link href attribute.
     */
    public function url($urlOptions = array(), $name = null, $reset = false, $encode = true)
    {
        $router = $this->getFrontController()->getRouter();
        return $router->assemble($urlOptions, $name, $reset, $encode);
    }

    /**
     * Perform helper when called as $this->_helper->url() from an action controller
     *
     * Proxies to {@link simple()}
     *
     * @param  string $strAction
     * @param  string $strController
     * @param  string $strModule
     * @param  string $strDomain
     * @param  array  $arrParams
     * @return string
     */
    public function direct($strAction,
                           $strController = null,
                           $strModule = null,
                           $strDomain = null,
                           Array $arrParams = null)
    {
        return $this->simple($strAction, $strController, $strModule, $strDomain, $arrParams);
    }
}
?>