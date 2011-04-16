<?php
class MySecureAccount_Domain_Account_ValueObject_AccountProperties
    extends Oxy_Domain_ValueObject_ArrayableAbstract
{    
    /**
     * @var MySecureAccount_Domain_Account_ValueObject_EmailAddress
     */
    protected $_emailAddress;
    
    /**
     * @var MySecureAccount_Domain_Account_ValueObject_PersonalInformation
     */
    protected $_personalInformation;
    
    /**
     * @var MySecureAccount_Domain_Account_ValueObject_Locale
     */
    protected $_locale;

    /**
     * @param MySecureAccount_Domain_Account_ValueObject_EmailAddress $emailAddress
     * @param MySecureAccount_Domain_Account_ValueObject_PersonalInformation $personalInformation
     * @param MySecureAccount_Domain_Account_ValueObject_Locale $locale
     */
    public function __construct(
        MySecureAccount_Domain_Account_ValueObject_EmailAddress $emailAddress, 
        MySecureAccount_Domain_Account_ValueObject_PersonalInformation $personalInformation,
        MySecureAccount_Domain_Account_ValueObject_Locale $locale
    )
    {
        $this->_emailAddress = $emailAddress;
        $this->_personalInformation = $personalInformation;
        $this->_locale = $locale;
    }
    
	/**
     * @return MySecureAccount_Domain_Account_ValueObject_EmailAddress
     */
    public function getEmailAddress()
    {
        return $this->_emailAddress;
    }

    /**
     * @return MySecureAccount_Domain_Account_ValueObject_PersonalInformation
     */
    public function getPersonalInformation()
    {
        return $this->_personalInformation;
    }

	/**
     * @return MySecureAccount_Domain_Account_ValueObject_Locale
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
            new MySecureAccount_Domain_Account_ValueObject_EmailAddress(
                $params['emailAddress']
            ),
            Oxy_Domain_ValueObject_ArrayableAbstract::createFromArray(
                'MySecureAccount_Domain_Account_ValueObject_PersonalInformation', 
                $params['personalInformation']
            ),
            new MySecureAccount_Domain_Account_ValueObject_Locale(
                new MySecureAccount_Domain_Account_ValueObject_Country(
                    $params['locale']['country']['code'],
                    $params['locale']['country']['title']
                ),
                new MySecureAccount_Domain_Account_ValueObject_Language(
                    $params['locale']['language']['code'],
                    $params['locale']['language']['title']
                )
            )
        );        
        $classInstance = $reflectedClass->newInstanceArgs($constructedParams);
        
        return $classInstance;         
    }
}