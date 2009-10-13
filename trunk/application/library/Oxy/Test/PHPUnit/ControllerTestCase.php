<?php
abstract class Oxy_Test_PHPUnit_ControllerTestCase extends Zend_Test_PHPUnit_ControllerTestCase
{
	/**
     * @var Oxy_Controller_Request_Abstract
     */
    protected $_request;
	
	/**
     * @var Oxy_Controller_Front
     */
    protected $_frontController;
	
	 /**
     * Retrieve test case request object
     *
     * @return Oxy_Controller_Request_Abstract
     */
    public function getRequest()
    {
        if (null === $this->_request) {
            require_once 'Oxy/Controller/Request/HttpTestCase.php';
            $this->_request = new Oxy_Controller_Request_HttpTestCase;
        }
        return $this->_request;
    }
	
	/**
     * Retrieve front controller instance
     *
     * @return Zend_Controller_Front
     */
    public function getFrontController()
    {
        if (null === $this->_frontController) {
            $this->_frontController = Oxy_Controller_Front::getInstance();
        }
        return $this->_frontController;
    }
}