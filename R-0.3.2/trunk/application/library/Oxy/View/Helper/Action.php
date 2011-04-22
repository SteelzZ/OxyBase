<?php

class Oxy_View_Helper_Action extends Zend_View_Helper_Action
{
	/**
	 * Default module
	 * @var String
	 */
	protected $strDefaultDomain;
	
	/**
     * Constructor
     *
     * Grab local copies of various MVC objects
     * 
     * @return void
     */
    public function __construct()
    {
        $objFront   = Oxy_Controller_Front::getInstance(); 
        $mixModules = $objFront->getControllerDirectory();
        if (empty($mixModules)) {
            require_once 'Zend/View/Exception.php';
            throw new Zend_View_Exception('Action helper depends on valid front controller instance');
        }

        $objRequest  = $objFront->getRequest(); 
        $objResponse = $objFront->getResponse(); 

        if (empty($objRequest) || empty($objResponse)) {
            require_once 'Zend/View/Exception.php';
            throw new Zend_View_Exception('Action view helper requires both a registered request and response object in the front controller instance');
        }

        $this->request       = clone $objRequest;
        $this->response      = clone $objResponse;
        $this->dispatcher    = clone $objFront->getDispatcher(); 
        $this->defaultModule = $objFront->getDefaultModule();
        $this->strDefaultDomain = $objFront->getDefaultDomain();
    }	
    
	/**
     * Retrieve rendered contents of a controller action
     *
     * If the action results in a forward or redirect, returns empty string.
     * 
     * @param  string $action 
     * @param  string $controller 
     * @param  string $module Defaults to default module
     * @param  array $params 
     * @return string
     */
    public function action($strAction, $strController, $strModule = null, $strDomain = null, array $arrParams = array())
    {
        $this->resetObjects(); 
        if (null === $strModule) 
        { 
            $strModule = $this->defaultModule; 
        } 
        
        if (null === $strDomain) 
        { 
            $strDomain = $this->strDefaultDomain; 
        } 

        // clone the view object to prevent over-writing of view variables
        $objViewRendererObj = Zend_Controller_Action_HelperBroker::getStaticHelper('viewRenderer');
        Zend_Controller_Action_HelperBroker::addHelper(clone $objViewRendererObj); 
        
        $this->request->setParams($arrParams) 
                      ->setDomainName($strDomain) 
                      ->setModuleName($strModule) 
                      ->setControllerName($strController) 
                      ->setActionName($strAction) 
                      ->setDispatched(true); 
 
        $this->dispatcher->dispatch($this->request, $this->response); 
 
        // reset the viewRenderer object to it's original state
        Zend_Controller_Action_HelperBroker::addHelper($objViewRendererObj);

        
        if (!$this->request->isDispatched() 
            || $this->response->isRedirect()) 
        { 
            // forwards and redirects render nothing 
            return ''; 
        } 
 
        $strReturn = $this->response->getBody();
        $this->resetObjects(); 
        return $strReturn;
    }
}
?>