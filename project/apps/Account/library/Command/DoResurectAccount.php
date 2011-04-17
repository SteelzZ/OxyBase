<?php
/**
 * @category Account
 * @package Account_Lib
 * @subpackage Command
 */
class Account_Lib_Command_DoResurectAccount 
    extends Oxy_Cqrs_Command_CommandAbstract
{    
    /**
     * @var string
     */
    private $_emailAddress;

    /**
     * @var string
     */    
    private $_password;

    /**
     * @var string
     */
    private $_passwordAgain;

    /**
     * @var array
     */    
    private $_personalInformation;

    /**
     * @var array
     */    
    private $_deliveryInformation;

    /**
     * @var array
     */    
    private $_settings;

    /**
     * @param string $commandName
     * @param string $guid
     * @param string $emailAddress
     * @param string $password
     * @param string $passwordAgain
     * @param array $personalInformation
     * @param array $deliveryInformation
     * @param array $settings
     */
    public function __construct(
        $commandName, 
        $guid, 
        $realIdentifier, 
        $emailAddress, 
        $password, 
        $passwordAgain, 
        $personalInformation, 
        $deliveryInformation, 
        $settings
    )
    {
        parent::__construct($commandName, $guid, $realIdentifier);
        $this->_emailAddress = $emailAddress;
        $this->_password = $password;
        $this->_passwordAgain = $passwordAgain;
        $this->_personalInformation = $personalInformation;
        $this->_deliveryInformation = $deliveryInformation;
        $this->_settings = $settings;
    }
    
	/**
     * @return string
     */
    public function getEmailAddress()
    {
        return $this->_emailAddress;
    }

	/**
     * @return string
     */
    public function getPassword()
    {
        return $this->_password;
    }

	/**
     * @return string
     */
    public function getPasswordAgain()
    {
        return $this->_passwordAgain;
    }

	/**
     * @return array
     */
    public function getPersonalInformation()
    {
        return $this->_personalInformation;
    }

	/**
     * @return array
     */
    public function getDeliveryInformation()
    {
        return $this->_deliveryInformation;
    }

	/**
     * @return array
     */
    public function getSettings()
    {
        return $this->_settings;
    }   
}