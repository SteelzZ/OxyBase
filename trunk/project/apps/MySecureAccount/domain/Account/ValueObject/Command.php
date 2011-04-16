<?php
class MySecureAccount_Domain_Account_ValueObject_Command
    extends Oxy_Domain_ValueObject_ArrayableAbstract
{
    /**
     * @var array
     */
    protected $_arguments;
    
    /**
     * @var array
     */
    protected $_serviceName;

    /**
     * @param array $properties
     */
    public function __construct($serviceName, array $arguments = array())
    {
        $this->_arguments = $arguments;
        $this->_serviceName = $serviceName;
    }
    
	/**
     * @return array
     */
    public function getArguments()
    {
        return $this->_arguments;
    }

	/**
     * @return array
     */
    public function getServiceName()
    {
        return $this->_serviceName;
    }
}