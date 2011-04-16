<?php
class MySecureAccount_Domain_Account_ValueObject_DeliveryInformation
    extends Oxy_Domain_ValueObject_ArrayableAbstract
{
    /**
     * @var string
     */
    protected $_country;
    
    /**
     * @var string
     */    
    protected $_city;
    
    /**
     * @var string
     */    
    protected $_postCode;
    
    /**
     * @var string
     */    
    protected $_street;
    
    /**
     * @var string
     */    
    protected $_houseNumber;
    
    /**
     * @var string
     */    
    protected $_secondAddressLine;
    
    /**
     * @var string
     */    
    protected $_thirdAddressLine;
    
    /**
     * @var string
     */    
    protected $_additionalInformation;
    
    /**
     * @param string $country
     * @param string $city
     * @param string $postCode
     * @param string $street
     * @param string $houseNumber
     * @param string $secondAddressLine
     * @param string $thirdAddressLine
     * @param string $additionalInformation
     */
    public function __construct(
        $country, 
        $city, 
        $postCode, 
        $street, 
        $houseNumber, 
        $secondAddressLine, 
        $thirdAddressLine, 
        $additionalInformation
    )
    {
        $this->_country = $country;        
        $this->_city = $city;        
        $this->_postCode = $postCode;        
        $this->_street = $street;        
        $this->_houseNumber = $houseNumber;        
        $this->_secondAddressLine = $secondAddressLine;        
        $this->_thirdAddressLine = $thirdAddressLine;        
        $this->_additionalInformation = $additionalInformation;        
    }
    
	/**
     * @return string
     */
    public function getCountry()
    {
        return $this->_country;
    }

	/**
     * @return string
     */
    public function getCity()
    {
        return $this->_city;
    }

	/**
     * @return string
     */
    public function getPostCode()
    {
        return $this->_postCode;
    }

	/**
     * @return string
     */
    public function getStreet()
    {
        return $this->_street;
    }

	/**
     * @return string
     */
    public function getHouseNumber()
    {
        return $this->_houseNumber;
    }

	/**
     * @return string
     */
    public function getSecondAddressLine()
    {
        return $this->_secondAddressLine;
    }

	/**
     * @return string
     */
    public function getThirdAddressLine()
    {
        return $this->_thirdAddressLine;
    }

	/**
     * @return string
     */
    public function getAdditionalInformation()
    {
        return $this->_additionalInformation;
    } 
}