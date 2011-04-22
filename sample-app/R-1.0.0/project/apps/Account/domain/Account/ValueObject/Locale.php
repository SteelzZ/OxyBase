<?php
class Account_Domain_Account_ValueObject_Locale
    extends Oxy_Domain_ValueObject_ArrayableAbstract
{
    /**
     * @var Account_Domain_Account_ValueObject_Language
     */
    protected $_language;    
    
    /**
     * @var Account_Domain_Account_ValueObject_Country
     */
    protected $_country;   

    /**
     * @param Account_Domain_Account_ValueObject_Country $country
     * @param Account_Domain_Account_ValueObject_Language $language
     */
    public function __construct(
        Account_Domain_Account_ValueObject_Country $country, 
        Account_Domain_Account_ValueObject_Language $language
    )
    {
        $this->_country = $country;        
        $this->_language = $language;        
    }
    
	/**
     * @return Account_Domain_Account_ValueObject_Language
     */
    public function getLanguage()
    {
        return $this->_language;
    }

	/**
     * @return Account_Domain_Account_ValueObject_Country
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