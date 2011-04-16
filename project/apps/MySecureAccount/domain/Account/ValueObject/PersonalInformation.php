<?php
class MySecureAccount_Domain_Account_ValueObject_PersonalInformation
    extends Oxy_Domain_ValueObject_ArrayableAbstract
{
    /**
     * @var string
     */
    protected $_firstName;
    
    /**
     * @var string
     */    
    protected $_lastName;
    
    /**
     * @var string
     */    
    protected $_dateOfBirth;
    
    /**
     * @var string
     */    
    protected $_gender;
    
    /**
     * @var string
     */    
    protected $_nickName;
    
    /**
     * @var string
     */    
    protected $_mobileNumber;
    
    /**
     * @var string
     */    
    protected $_homeNumber;
    
    /**
     * @var string
     */    
    protected $_additionalInformation;
    
    /**
     * @param string $firstName
     * @param string $lastName
     * @param string $dateOfBirth
     * @param string $gender
     * @param string $nickName
     * @param string $mobileNumber
     * @param string $homeNumber
     * @param string $additionalInformation
     */
    public function __construct(
        $firstName, 
        $lastName, 
        $dateOfBirth, 
        $gender, 
        $nickName, 
        $mobileNumber, 
        $homeNumber, 
        $additionalInformation
    )
    {
        $this->_firstName = $firstName;        
        $this->_lastName = $lastName;        
        $this->_dateOfBirth = $dateOfBirth;        
        $this->_gender = $gender;        
        $this->_nickName = $nickName;        
        $this->_mobileNumber = $mobileNumber;        
        $this->_homeNumber = $homeNumber;        
        $this->_additionalInformation = $additionalInformation;        
    }
    
	/**
     * @return field_type
     */
    public function getFirstName()
    {
        return $this->_firstName;
    }

	/**
     * @return field_type
     */
    public function getLastName()
    {
        return $this->_lastName;
    }

	/**
     * @return field_type
     */
    public function getDateOfBirth()
    {
        return $this->_dateOfBirth;
    }

	/**
     * @return field_type
     */
    public function getGender()
    {
        return $this->_gender;
    }

	/**
     * @return field_type
     */
    public function getNickName()
    {
        return $this->_nickName;
    }

	/**
     * @return field_type
     */
    public function getMobileNumber()
    {
        return $this->_mobileNumber;
    }

	/**
     * @return field_type
     */
    public function getHomeNumber()
    {
        return $this->_homeNumber;
    }

	/**
     * @return field_type
     */
    public function getAdditionalInformation()
    {
        return $this->_additionalInformation;
    }
}