<?php
class MySecureAccount_Domain_Account_ValueObject_Locale
    extends Oxy_Domain_ValueObject_ArrayableAbstract
{
    /**
     * @var MySecureAccount_Domain_Account_ValueObject_Language
     */
    protected $_language;    
    
    /**
     * @var MySecureAccount_Domain_Account_ValueObject_Country
     */
    protected $_country;   

    /**
     * @param MySecureAccount_Domain_Account_ValueObject_Country $country
     * @param MySecureAccount_Domain_Account_ValueObject_Language $language
     */
    public function __construct(
        MySecureAccount_Domain_Account_ValueObject_Country $country, 
        MySecureAccount_Domain_Account_ValueObject_Language $language
    )
    {
        $this->_country = $country;        
        $this->_language = $language;        
    }
    
	/**
     * @return MySecureAccount_Domain_Account_ValueObject_Language
     */
    public function getLanguage()
    {
        return $this->_language;
    }

	/**
     * @return MySecureAccount_Domain_Account_ValueObject_Country
     */
    public function getCountry()
    {
        return $this->_country;
    }
    
	/**
     * @see Oxy_Domain_ValueObject_ArrayableAbstract::toArray()
     */
    public function toArray()
    {
        return array(
            'country' => $this->_country->toArray(),
            'language' => $this->_language->toArray(),
        );        
    }
}