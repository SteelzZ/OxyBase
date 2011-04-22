<?php
class Account_Domain_Account_ValueObject_AccountProperties
    extends Oxy_Domain_ValueObject_ArrayableAbstract
{    
    /**
     * @var Account_Domain_Account_ValueObject_EmailAddress
     */
    protected $_emailAddress;
    
    /**
     * @var Account_Domain_Account_ValueObject_PersonalInformation
     */
    protected $_personalInformation;
    
    /**
     * @var Account_Domain_Account_ValueObject_Locale
     */
    protected $_locale;

    /**
     * @param Account_Domain_Account_ValueObject_EmailAddress $emailAddress
     * @param Account_Domain_Account_ValueObject_PersonalInformation $personalInformation
     * @param Account_Domain_Account_ValueObject_Locale $locale
     */
    public function __construct(
        Account_Domain_Account_ValueObject_EmailAddress $emailAddress, 
        Account_Domain_Account_ValueObject_PersonalInformation $personalInformation,
        Account_Domain_Account_ValueObject_Locale $locale
    )
    {
        $this->_emailAddress = $emailAddress;
        $this->_personalInformation = $personalInformation;
        $this->_locale = $locale;
    }
    
	/**
     * @return Account_Domain_Account_ValueObject_EmailAddress
     */
    public function getEmailAddress()
    {
        return $this->_emailAddress;
    }

    /**
     * @return Account_Domain_Account_ValueObject_PersonalInformation
     */
    public function getPersonalInformation()
    {
        return $this->_personalInformation;
    }

	/**
     * @return Account_Domain_Account_ValueObject_Locale
     */
    public function getLocale()
    {
        return $this->_locale;
    }
    
    /**
     * @see Oxy_Domain_ValueObject_ArrayableAbstract::toArray()
     */
    public function toArray()
    {
        return array(
            'emailAddress' => (string)$this->_emailAddress,
            'personalInformation' => $this->_personalInformation->toArray(),
            'locale' => $this->_locale->toArray(),
        );        
    }
    
    /**
     * @param string $className
     * @param array $params
     * 
     * @return Oxy_Domain_ValueObject_ArrayableAbstract
     */
    public static function createFromArray($className, $params)
    {
        $reflectedClass = new ReflectionClass($className);
        
        $constructedParams = array(
            new Account_Domain_Account_ValueObject_EmailAddress(
                $params['emailAddress']
            ),
            Oxy_Domain_ValueObject_ArrayableAbstract::createFromArray(
                'Account_Domain_Account_ValueObject_PersonalInformation', 
                $params['personalInformation']
            ),
            new Account_Domain_Account_ValueObject_Locale(
                new Account_Domain_Account_ValueObject_Country(
                    $params['locale']['country']['code'],
                    $params['locale']['country']['title']
                ),
                new Account_Domain_Account_ValueObject_Language(
                    $params['locale']['language']['code'],
                    $params['locale']['language']['title']
                )
            )
        );        
        $classInstance = $reflectedClass->newInstanceArgs($constructedParams);
        
        return $classInstance;         
    }
}