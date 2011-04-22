<?php
/**
 * test case.
 */
class Account_Domain_Account_AccountTest extends Oxy_Test_PHPUnit_EventSourcedAggregateRootTestCase
{
    /**
     * @var Oxy_Guid
     */
    private $_accountGuid;
    
    /**
     * @var Oxy_Guid
     */
    private $_activationKey;
 
    /**
     * @var Account_Domain_Account_ValueObject_EmailAddress
     */
    private $_primaryEmail;
    
    /**
     * @var Account_Domain_Account_ValueObject_PersonalInformation
     */
    private $_ownerInformation;
    
    /**
     * @var Account_Domain_Account_ValueObject_DeliveryInformation
     */
    private $_deliveryInformation;
    
    /**
     * @var Account_Domain_Account_ValueObject_Password
     */
    private $_password;
    
    /**
     * @var Account_Domain_Account_ValueObject_Password
     */
    private $_passwordAgain;
    
    /**
     * @var Account_Domain_Account_ValueObject_Properties
     */
    private $_settings;
        
    /**
     * Prepares the environment before running a test.
     */
    protected function setUp ()
    {
        parent::setUp();
        $this->_accountGuid = new Oxy_Guid('my-account-guid');
        $this->_activationKey = new Oxy_Guid('i-am-a-key');
        $this->_primaryEmail = new Account_Domain_Account_ValueObject_EmailAddress('a@a.com');
        
        $this->_ownerInformation = Oxy_Domain_ValueObject_ArrayableAbstract::createFromArray(
            'Account_Domain_Account_ValueObject_PersonalInformation',
            array(
                'firstName' => 'Tomas',
                'lastName' => 'Bartkus',
                'dateOfBirth' => '1984-11-26',
                'gender' => 'male',
                'nickName' => 'Meilas',
                'mobileNumber' => '0037068257462',
                'homeNumber' => '0037068257462',
                'additionalInformation' => 'Something',
            )
        );
        
        $this->_deliveryInformation = Oxy_Domain_ValueObject_ArrayableAbstract::createFromArray(
            'Account_Domain_Account_ValueObject_DeliveryInformation',
            array(
                'country' => 'Lithuania',
                'city' => 'Kaunas',
                'postCode' => '50135',
                'street' => 'Street',
                'houseNumber' => '114',
                'secondAddressLine' => 'Street2',
                'thirdAddressLine' => 'Street3',
                'additionalInformation' => 'Info'
            )
        );
               
        $this->_password = new Account_Domain_Account_ValueObject_Password(
            'leeeet'
        );
        
        $this->_passwordAgain = new Account_Domain_Account_ValueObject_Password(
            'leeeet'
        );
        
        $this->_settings = Oxy_Domain_ValueObject_ArrayableAbstract::createFromArray(
            'Account_Domain_Account_ValueObject_Properties',
            array(
                'locale' => array(
                    'country' => array(
                        'code' => 'gb',
                        'title' => 'United Kingdom'
                    ),
                    'language' => array(
                        'code' => 'dk',
                        'title' => 'Denmark'
                    ),
                ) 
            )
        );
    }

    /**
     * Cleans up the environment after running a test.
     */
    protected function tearDown ()
    {
        parent::tearDown();
    }

    /**
     * Constructs the test case.
     */
    public function __construct ()
    {
    }
    
    /**
     * @param Account_Domain_Account_AggregateRoot_Account $account
     */
    private function _loadAccountIntoInitializedState(Account_Domain_Account_AggregateRoot_Account $account)
    {
        $account->setMemento(
            new Account_Domain_Account_AggregateRoot_Account_Memento_Account(
                array(
                    'accountGuid' => (string)$this->_accountGuid,
                	'primaryEmail' => (string)$this->_primaryEmail,
                	'currentPassword' => (string)$this->_password,
                	'state' => Account_Domain_Account_ValueObject_State::ACCOUNT_STATE_INITIALIZED,
                	'loginState' => Account_Domain_Account_ValueObject_State::LOGGED_OUT,
                	'activationKey' => (string)$this->_activationKey,
                	'settings' => $this->_settings->toArray(),
                	'activeProducts' => array()
                )
            )
        );        
    }
    
    /**
     * Should setup account
     */
    public function testShouldSetupAccountForCustomer()
    {        
        $account = new Account_Domain_Account_AggregateRoot_Account(
            $this->_accountGuid,
            (string)$this->_primaryEmail
        );        
             
        $account->setup(
            $this->_primaryEmail, 
            $this->_password, 
            $this->_passwordAgain, 
            $this->_ownerInformation, 
            $this->_deliveryInformation, 
            $this->_settings
        );
        
        $this->assertEvents(
            $account,
            array(
            	array('Account_Domain_Account_AggregateRoot_Account_Event_NewAccountCreated')
            )
        );
    }
        
    /**
     * Show generate event that account already is initialized (Exists)
     */
    public function testShouldNotSetupAccountAgainAndShouldThrowExceptionBecauseThatStateIsNotNew()
    {
        $account = new Account_Domain_Account_AggregateRoot_Account(
            $this->_accountGuid,
            (string)$this->_primaryEmail
        );    

        $this->_loadAccountIntoInitializedState($account);
        
        $account->setup(
            $this->_primaryEmail, 
            $this->_password, 
            $this->_passwordAgain, 
            $this->_ownerInformation, 
            $this->_deliveryInformation, 
            $this->_settings
        );
        
        $this->assertEvents(
            $account,
            array(
            	array('Account_Domain_Account_AggregateRoot_Account_Event_AccountAlreadyExistsExceptionThrown')
            )
        );
    }
}