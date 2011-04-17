<?php
class Account_Domain_Account_AggregateRoot_Account 
    extends Oxy_Domain_AggregateRoot_EventSourcedAbstract
{        
    /**
     * @var Account_Domain_Account_ValueObject_EmailAddress
     */
    private $_primaryEmail;
    
    /**
     * @var Account_Domain_Account_ValueObject_Password
     */
    private $_currentPassword;
    
    /**
     * @var Account_Domain_Account_ValueObject_State
     */
    private $_state;
    
    /**
     * @var Account_Domain_Account_ValueObject_State
     */
    private $_loginState;
    
    /**
     * @var Oxy_Guid
     */
    private $_activationKey;
    
    /**
     * @var Account_Domain_Account_ValueObject_ProductsCollection
     */
    private $_activeProducts;
    
    /**
     * @var Account_Domain_Account_ValueObject_DevicesCollection
     */
    private $_devices;
    
    /**
     * @var Account_Domain_Account_ValueObject_Properties
     */
    private $_settings;
       
    /**
     * @return Account_Domain_Account_ValueObject_EmailAddress
     */
    public function getPrimaryEmail()
    {
        return $this->_primaryEmail;
    }
    
	/**
     * Initialize aggregate root
     * 
     * @param Oxy_Guid $guid
     * @param string $realIdntifier
     */
    public function __construct(
        Oxy_Guid $guid, 
        $realIdntifier
    )
    {
        parent::__construct($guid, $realIdntifier);
        $this->_state = new Account_Domain_Account_ValueObject_State(
            Account_Domain_Account_ValueObject_State::ACCOUNT_STATE_NEW
        );
        
        $this->_loginState = new Account_Domain_Account_ValueObject_State(
            Account_Domain_Account_ValueObject_State::LOGGED_OUT
        );
        
        $this->_activeProducts = new Account_Domain_Account_ValueObject_ProductsCollection();
        $this->_devices = new Account_Domain_Account_ValueObject_DevicesCollection();
        $this->_primaryEmail = null;
        $this->_activationKey = null;
        $this->_currentPassword = null;
        $this->_settings = new Account_Domain_Account_ValueObject_Properties();
    }
        
	/**
     * Is account state - new ?
     * This is fresh account state, which was just created 
     * 
     * @return boolean
     */
    private function _isNew()
    {     
        if((string)$this->_state === Account_Domain_Account_ValueObject_State::ACCOUNT_STATE_NEW){
            return true;
        } else {
            return false;
        } 
    }
    
    /**
     * Is account state - deactivated (deleted) ?
     * Account has been deleted
     * 
     * @return boolean
     */
    private function _isDeactivated()
    {     
        if((string)$this->_state === Account_Domain_Account_ValueObject_State::ACCOUNT_STATE_DEACTIVATED){
            return true;
        } else {
            return false;
        } 
    }
    
    /**
     * Is account state - initialized ?
     * Once account data is setup and is ready to be confirmed, account exists in - initialized state
     * 
     * @return boolean
     */
    private function _isInitialized()
    {      
        if((string)$this->_state === Account_Domain_Account_ValueObject_State::ACCOUNT_STATE_INITIALIZED){
            return true;
        } else {
            return false;
        }
    }
    
    /**
     * Once account is activated it's state becomes - activated
     * Now we can perform all the rest behaviours on this AR
     * 
     * @return boolean
     */
    private function _isActive()
    {      
        if((string)$this->_state === Account_Domain_Account_ValueObject_State::ACCOUNT_STATE_ACTIVATED){
            return true;
        } else {
            return false;
        }
    }
    
    /**
     * @return boolean
     */
    private function _isLoggedIn()
    {      
        if((string)$this->_loginState === Account_Domain_Account_ValueObject_State::LOGGED_IN){
            return true;
        } else {
            return false;
        }
    }
    
    /**
     * Compare passwords
     * 
     * @param Account_Domain_Account_ValueObject_Password $password
     * @param Account_Domain_Account_ValueObject_Password $passwordAgain
     * 
     * @return boolean
     */
    private function _comparePasswords(
        Account_Domain_Account_ValueObject_Password $password,
        Account_Domain_Account_ValueObject_Password $passwordAgain
    )
    {
        if((string)$password->getEncoded() === (string)$passwordAgain->getEncoded()){
            return true;
        } else {
            return false;
        }        
    }
    
    /**
     * Compare activation keys
     * 
     * @param Oxy_Guid $userProvidedActivationKey
     * 
     * @return boolean
     */
    private function _compareActivationKeys(Oxy_Guid $userProvidedActivationKey)
    {
        if((string)$userProvidedActivationKey === (string)$this->_activationKey){
            return true;
        } else {
            return false;
        }        
    }
    
    /**
     * Check password strength
     * 
     * @param Account_Domain_Account_ValueObject_Password $password
     * 
     * @return boolean
     */
    private function _checkPasswordStrength(
        Account_Domain_Account_ValueObject_Password $password
    )
    {
        // Allow any password
        return true;       
    }
    
    /**
     * Create random password
     * 
     * @return string
     */
    private function _createRandomPassword()
    {
        $chars = "abcdefghijkmnopqrstuvwxyz023456789";
        srand((double) microtime() * 1000000);
        $i = 0;
        $pass = '';
        while ($i <= 7) {
            $num = rand() % 33;
            $tmp = substr($chars, $num, 1);
            $pass = $pass . $tmp;
            $i ++;
        }
        return $pass;
    }
    
    /**
     * @param Oxy_Guid $productGuid
     * @return boolean
     */
    private function _doesInstallationExists($productName)
    {
        foreach ($this->_devices as $existingDevice){
            foreach ($existingDevice->getInstallations() as $installation){
                if((string)$installation->getProductGuid() === (string)$productGuid){
                    return true;
                }
            }
        }
        
        return false;
    }
             
    /**
     * Setup new account
     * 
     * @param Account_Domain_Account_ValueObject_EmailAddress $primaryEmailAddress
     * @param Account_Domain_Account_ValueObject_Password $password
     * @param Account_Domain_Account_ValueObject_Password $passwordAgain
     * @param Account_Domain_Account_ValueObject_PersonalInformation $ownerPersonalInformation
     * @param Account_Domain_Account_ValueObject_DeliveryInformation $ownerDeliveryInformation
     * @param Account_Domain_Account_ValueObject_Properties $settings
     */
    public function setup(
        Account_Domain_Account_ValueObject_EmailAddress $primaryEmailAddress,
        Account_Domain_Account_ValueObject_Password $password,
        Account_Domain_Account_ValueObject_Password $passwordAgain,
        Account_Domain_Account_ValueObject_PersonalInformation $ownerPersonalInformation,
        Account_Domain_Account_ValueObject_DeliveryInformation $ownerDeliveryInformation,
        Account_Domain_Account_ValueObject_Properties $settings 
    )
    {
        if($this->_isNew()){
            if($this->_comparePasswords($password, $passwordAgain)){
                if($this->_checkPasswordStrength($password)){
                    $emailActivationKey = new Oxy_Guid();
                    $encodedPassword = (string)$password->getEncoded();
                    
                    $generatedPassword = $password->isAutoGenerated() == true ? (string)$password : '';
                    $this->_handleEvent(
                        new Account_Domain_Account_AggregateRoot_Account_Event_NewAccountCreated(
                            array(
                                'accountGuid' => (string)$this->_guid,
                                'primaryEmail' => (string)$primaryEmailAddress,
                                'password' => (string)$password,
                                'passwordAgain' => (string)$passwordAgain,
                                'isAutoGenerated' => (boolean)$password->isAutoGenerated(),
                                'encodedPassword' => $encodedPassword,
                                'personalInformation' => $ownerPersonalInformation->toArray(),
                                'deliveryInformation' => $ownerDeliveryInformation->toArray(),
                                'settings' => $settings->getProperties(),
                                'state' => Account_Domain_Account_ValueObject_State::ACCOUNT_STATE_INITIALIZED,
                                'emailActivationKey' => (string)$emailActivationKey,
                            	'date' => date('Y-m-d H:i:s'),
                            	'loginState' => (string)$this->_loginState,
                            	'generatedPassword' => $generatedPassword,
                            )
                        )
                    );
                } else {                
                    $this->_handleEvent(
                        new Account_Domain_Account_AggregateRoot_Account_Event_PasswordTooWeakExceptionThrown(
                            array(
                                'accountGuid' => (string)$this->_guid,
                                'message' => 'account.error.weak.password',
                                'date' => date('Y-m-d H:i:s'),
                                'additional' => sprintf(
                                    'Password [%s] way to weak',
                                    (string)$password
                                )
                            )
                        )
                    );
                }
            } else {                
                $this->_handleEvent(
                    new Account_Domain_Account_AggregateRoot_Account_Event_PasswordsDidNotMatchExceptionThrown(
                        array(
                            'accountGuid' => (string)$this->_guid,
                            'message' => 'account.error.passwords.didnt.match',
                            'date' => date('Y-m-d H:i:s'),
                            'additional' => sprintf(
                            	'[%s:%s] not equals [%s:%s]', 
                                (string)$password, 
                                (string)$password->getEncoded(), 
                                (string)$passwordAgain, 
                                (string)$passwordAgain->getEncoded()
                            )
                        )
                    )
                );
            }
        } else if($this->_isDeactivated()){
            $this->resurect(
                $primaryEmailAddress, 
                $password, 
                $passwordAgain, 
                $ownerPersonalInformation, 
                $ownerDeliveryInformation, 
                $settings
            );
        }else {
            $this->_handleEvent(
                new Account_Domain_Account_AggregateRoot_Account_Event_AccountAlreadyExistsExceptionThrown(
                    array(
                        'accountGuid' => (string)$this->_guid,
                        'message' => 'account.error.account.already.exists',
                        'date' => date('Y-m-d H:i:s'),
                        'additional' => sprintf(
                            'This email [%s] was trying to setup more than once',
                            (string)$primaryEmailAddress
                        )
                    )
                )
            );
        }
    }
        
    /**
     * @param Account_Domain_Account_AggregateRoot_Account_Event_NewAccountCreated $event
     */
    protected function onNewAccountCreated(
        Account_Domain_Account_AggregateRoot_Account_Event_NewAccountCreated $event
    )
    {
        $this->_primaryEmail = new Account_Domain_Account_ValueObject_EmailAddress(
            $event->getPrimaryEmail()
        );
        
        $this->_state = new Account_Domain_Account_ValueObject_State(
            $event->getState()
        );    
        $this->_currentPassword = new Account_Domain_Account_ValueObject_Password(
            $event->getEncodedPassword(),
            true,
            $event->getIsAutoGenerated()
        );     
        
        $this->_activationKey = new Oxy_Guid($event->getEmailActivationKey());
        $this->_settings = new Account_Domain_Account_ValueObject_Properties($event->getSettings());
    }
    
    /**
     * @param Account_Domain_Account_AggregateRoot_Account_Event_AccountAlreadyExistsExceptionThrown $event
     */
    protected function onAccountAlreadyExistsExceptionThrown(
        Account_Domain_Account_AggregateRoot_Account_Event_AccountAlreadyExistsExceptionThrown $event
    )
    {
    }
    
    /**
     * @param Account_Domain_Account_AggregateRoot_Account_Event_PasswordsDidNotMatchExceptionThrown $event
     */
    protected function onPasswordsDidNotMatchExceptionThrown(
        Account_Domain_Account_AggregateRoot_Account_Event_PasswordsDidNotMatchExceptionThrown $event
    )
    {       
    }
    
    /**
     * @param Account_Domain_Account_AggregateRoot_Account_Event_PasswordTooWeakExceptionThrown $event
     */
    protected function onPasswordTooWeakExceptionThrown(
        Account_Domain_Account_AggregateRoot_Account_Event_PasswordTooWeakExceptionThrown $event
    )
    {       
    }
    
    /**
     * Confirm primary email address
     * 
     * @param Oxy_Guid $activationKey
     */
    public function confirmPrimaryEmailAddress(Oxy_Guid $activationKey)
    {
        if($this->_isInitialized()){
            if(!$this->_isActive()){
                if($this->_compareActivationKeys($activationKey)){
                    $this->_handleEvent(
                        new Account_Domain_Account_AggregateRoot_Account_Event_AccountActivated(
                            array(
                                'accountGuid' => (string)$this->_guid,
                                'emailAddress' => (string)$this->_primaryEmail,
                                'activationDate' => date('Y-m-d H:i:s'),
                                'state' => Account_Domain_Account_ValueObject_State::ACCOUNT_STATE_ACTIVATED
                            )
                        )
                    );
                } else {
                    $this->_handleEvent(
                        new Account_Domain_Account_AggregateRoot_Account_Event_ActivationKeysDidntMatchExceptionThrown(
                            array(
                                'accountGuid' => (string)$this->_guid,
                                'message' => 'account.error.activation.keys.didnt.match',
                                'date' => date('Y-m-d H:i:s'),
                                'additional' => sprintf(
                                    'Activation keys does not match provided:[%s] - required:[%s]',
                                    (string)$activationKey,
                                    (string)$this->_activationKey
                                )
                            )
                        )
                    );
                }
            } else {
                $this->_handleEvent(
                    new Account_Domain_Account_AggregateRoot_Account_Event_AccountAlreadyActivatedExceptionThrown(
                        array(
                            'accountGuid' => (string)$this->_guid,
                            'message' => 'account.error.account.already.activated',
                            'date' => date('Y-m-d H:i:s'),
                            'additional' => sprintf(
                                'Account [%s] is already activated',
                                (string)$this->_primaryEmail
                            )
                        )
                    )
                );
            }
        } else {
            if($this->_isActive()){
                $this->_handleEvent(
                    new Account_Domain_Account_AggregateRoot_Account_Event_AccountAlreadyActivatedExceptionThrown(
                        array(
                            'accountGuid' => (string)$this->_guid,
                            'message' => 'account.error.account.already.activated',
                            'date' => date('Y-m-d H:i:s'),
                            'additional' => sprintf(
                                'Account [%s] is already activated',
                                (string)$this->_primaryEmail
                            )
                        )
                    )
                );
            } else {
                $this->_throwWrongStateException('MSA::Account::confirmPrimaryEmailAddress', $this->_state);
            }
        }
    }
    
    /**
     * @param Account_Domain_Account_AggregateRoot_Account_Event_AccountActivated $event
     */
    protected function onAccountActivated(
        Account_Domain_Account_AggregateRoot_Account_Event_AccountActivated $event
    )
    {       
        $this->_state = new Account_Domain_Account_ValueObject_State(
            $event->getState()
        );
    }
    
    /**
     * @param Account_Domain_Account_AggregateRoot_Account_Event_AccountAlreadyActivatedExceptionThrown $event
     */
    protected function onAccountAlreadyActivatedExceptionThrown(
        Account_Domain_Account_AggregateRoot_Account_Event_AccountAlreadyActivatedExceptionThrown $event
    )
    {       
    }
    
    /**
     * @param Account_Domain_Account_AggregateRoot_Account_Event_ActivationKeysDidntMatchExceptionThrown $event
     */
    protected function onActivationKeysDidntMatchExceptionThrown(
        Account_Domain_Account_AggregateRoot_Account_Event_ActivationKeysDidntMatchExceptionThrown $event
    )
    {       
    }
    
    /**
     * @param Account_Domain_Account_ValueObject_EmailAddress $primaryEmailAddress
     * @param Account_Domain_Account_ValueObject_Password $password
     */
    public function login(
        Account_Domain_Account_ValueObject_EmailAddress $primaryEmailAddress,
        Account_Domain_Account_ValueObject_Password $password
    )
    {
        if($this->_isActive() && !$this->_isLoggedIn()){
            if($this->_comparePasswords($password, $this->_currentPassword)){
                $this->_handleEvent(
                    new Account_Domain_Account_AggregateRoot_Account_Event_UserLoggedInSuccessfully(
                        array(
                            'accountGuid' => (string)$this->_guid,
                            'email' => (string)$this->_primaryEmail,
                            'loginState' => Account_Domain_Account_ValueObject_State::LOGGED_IN,
                            'date' => date('Y-m-d H:i:s')
                        )
                    )
                );
            } else {
                $this->_handleEvent(
                    new Account_Domain_Account_AggregateRoot_Account_Event_WrongPasswordExceptionThrown(
                        array(
                            'accountGuid' => (string)$this->_guid,
                            'message' => 'account.error.wrong.password',
                            'date' => date('Y-m-d H:i:s'),
                            'additional' => sprintf(
                                'Password [%s] for account [%s] is wrong',
                                (string)$password,
                                $this->_primaryEmail
                            )
                        )
                    )
                );
            }
        } else {
            if($this->_isActive() && $this->_isLoggedIn()){
                $this->_handleEvent(
                    new Account_Domain_Account_AggregateRoot_Account_Event_AlreadyLoggedInExceptionThrown(
                        array(
                            'accountGuid' => (string)$this->_guid,
                            'message' => 'account.error.already.logged.in',
                            'date' => date('Y-m-d H:i:s'),
                            'additional' => sprintf(
                                'User has already logged in [%s]',
                                (string)$this->_primaryEmail
                            )
                        )
                    )
                );
            } else if(!$this->_isActive()){
                $this->_throwWrongStateException('MSA::Account::login', $this->_state);
            }
        }
    }
    
    /**
     * @param Account_Domain_Account_AggregateRoot_Account_Event_AlreadyLoggedInExceptionThrown $event
     */
    protected function onAlreadyLoggedInExceptionThrown(
        Account_Domain_Account_AggregateRoot_Account_Event_AlreadyLoggedInExceptionThrown $event
    )
    {       
    }
    
    /**
     * @param Account_Domain_Account_AggregateRoot_Account_Event_WrongPasswordExceptionThrown $event
     */
    protected function onWrongPasswordExceptionThrown(
        Account_Domain_Account_AggregateRoot_Account_Event_WrongPasswordExceptionThrown $event
    )
    {       
    }
    
    /**
     * @param Account_Domain_Account_AggregateRoot_Account_Event_UserLoggedInSuccessfully $event
     */
    protected function onUserLoggedInSuccessfully(
        Account_Domain_Account_AggregateRoot_Account_Event_UserLoggedInSuccessfully $event
    )
    {   
        $this->_loginState = new Account_Domain_Account_ValueObject_State(
            $event->getLoginState()
        );     
    }
    
    /**
     * Logout
     */
    public function logout()
    {
        if($this->_isActive()){
            $this->_handleEvent(
                new Account_Domain_Account_AggregateRoot_Account_Event_UserLoggedOutSuccessfully(
                    array(
                        'accountGuid' => (string)$this->_guid,
                        'email' => (string)$this->_primaryEmail,
                        'loginState' => Account_Domain_Account_ValueObject_State::LOGGED_OUT,
                        'date' => date('Y-m-d H:i:s')
                    )
                )
            );
        } else {
            $this->_throwWrongStateException('MSA::Account::logout', $this->_state);
        }        
    }
    
	/**
     * @param Account_Domain_Account_AggregateRoot_Account_Event_UserLoggedOutSuccessfully $event
     */
    protected function onUserLoggedOutSuccessfully(
        Account_Domain_Account_AggregateRoot_Account_Event_UserLoggedOutSuccessfully $event
    )
    {   
        $this->_loginState = new Account_Domain_Account_ValueObject_State(
            $event->getLoginState()
        );     
    }
    
    /**
     * Remind password
     */
    public function remindPassword()
    {
        if($this->_isInitialized() || $this->_isActive()){
            $newPassword = $this->_createRandomPassword();
            $newPassword = new Account_Domain_Account_ValueObject_Password($newPassword);
            $this->_handleEvent(
                new Account_Domain_Account_AggregateRoot_Account_Event_NewPasswordGeneratedForAccount(
                    array(
                        'accountGuid' => (string)$this->_guid,
                        'email' => (string)$this->_primaryEmail,
                        'password' => (string)$newPassword,
                        'settings' => $this->_settings->getProperties(),
                        'encodedPassword' => (string)$newPassword->getEncoded(),
                        'date' => date('Y-m-d H:i:s')
                    )
                )
            );            
        } else {
            $this->_throwWrongStateException('MSA::Account::remindPassword', $this->_state);
        }
    }
    
	/**
     * @param Account_Domain_Account_AggregateRoot_Account_Event_NewPasswordGeneratedForAccount $event
     */
    protected function onNewPasswordGeneratedForAccount(
        Account_Domain_Account_AggregateRoot_Account_Event_NewPasswordGeneratedForAccount $event
    )
    {   
        $this->_currentPassword = new Account_Domain_Account_ValueObject_Password(
            $event->getEncodedPassword(),
            true
        );     
    }
    
    /**
     * Remind password
     */
    public function remindActivationKey()
    {
        if($this->_isInitialized()){
            $this->_handleEvent(
                new Account_Domain_Account_AggregateRoot_Account_Event_NewActivationKeyGeneratedForAccount(
                    array(
                        'accountGuid' => (string)$this->_guid,
                        'primaryEmail' => (string)$this->_primaryEmail,
                        'emailActivationKey' => (string)$this->_activationKey,
                        'settings' => $this->_settings->getProperties(),
                        'date' => date('Y-m-d H:i:s')
                    )
                )
            );            
        } else {
            $this->_throwWrongStateException('MSA::Account::remindActivationKey', $this->_state);
        }
    }
    
	/**
     * @param Account_Domain_Account_AggregateRoot_Account_Event_NewActivationKeyGeneratedForAccount $event
     */
    protected function onNewActivationKeyGeneratedForAccount(
        Account_Domain_Account_AggregateRoot_Account_Event_NewActivationKeyGeneratedForAccount $event
    )
    {       
    }
    
    /**
     * Delete account
     */
    public function deleteAccount()
    {
        if($this->_isActive() && $this->_isLoggedIn()){
            $this->_handleEvent(
                new Account_Domain_Account_AggregateRoot_Account_Event_AccountDeactivated(
                    array(
                        'accountGuid' => (string)$this->_guid,
                        'email' => (string)$this->_primaryEmail,
                        'date' => date('Y-m-d H:i:s'),
                        'state' => Account_Domain_Account_ValueObject_State::ACCOUNT_STATE_DEACTIVATED,
                        'loginState' => Account_Domain_Account_ValueObject_State::LOGGED_OUT
                    )
                )
            );            
        } else {
            $this->_throwWrongStateException('MSA::Account::deleteAccount', $this->_state);
        }
    }
    
	/**
     * @param Account_Domain_Account_AggregateRoot_Account_Event_AccountDeactivated $event
     */
    protected function onAccountDeactivated(
        Account_Domain_Account_AggregateRoot_Account_Event_AccountDeactivated $event
    )
    {   
        $this->_state = new Account_Domain_Account_ValueObject_State(
            $event->getState()
        );     
        $this->_loginState = new Account_Domain_Account_ValueObject_State(
            $event->getLoginState()
        );     
    }
    
    /**
     * Resurect customer 
     * Basically account has been deleted and now someone is trying to register again
     */
    public function resurect(
        Account_Domain_Account_ValueObject_EmailAddress $primaryEmailAddress,
        Account_Domain_Account_ValueObject_Password $password,
        Account_Domain_Account_ValueObject_Password $passwordAgain,
        Account_Domain_Account_ValueObject_PersonalInformation $ownerPersonalInformation,
        Account_Domain_Account_ValueObject_DeliveryInformation $ownerDeliveryInformation,
        Account_Domain_Account_ValueObject_Properties $settings 
    )
    {
        if($this->_isDeactivated()){
            if($this->_comparePasswords($password, $passwordAgain)){
                if($this->_checkPasswordStrength($password)){
                    $emailActivationKey = new Oxy_Guid();
                    $encodedPassword = (string)$password->getEncoded();
                                        
                    $this->_handleEvent(
                        new Account_Domain_Account_AggregateRoot_Account_Event_AccountResurected(
                            array(
                                'accountGuid' => (string)$this->_guid,
                                'primaryEmail' => (string)$primaryEmailAddress,
                                'password' => (string)$password,
                                'passwordAgain' => (string)$passwordAgain,
                                'encodedPassword' => $encodedPassword,
                                'personalInformation' => $ownerPersonalInformation->toArray(),
                                'deliveryInformation' => $ownerDeliveryInformation->toArray(),
                                'settings' => $settings->getProperties(),
                                'state' => Account_Domain_Account_ValueObject_State::ACCOUNT_STATE_INITIALIZED,
                                'emailActivationKey' => (string)$emailActivationKey,
                            	'date' => date('Y-m-d H:i:s'),
                            	'loginState' => (string)$this->_loginState,
                            )
                        )
                    );
                } else {                
                    $this->_handleEvent(
                        new Account_Domain_Account_AggregateRoot_Account_Event_PasswordTooWeakExceptionThrown(
                            array(
                                'accountGuid' => (string)$this->_guid,
                                'message' => 'account.error.weak.password',
                                'date' => date('Y-m-d H:i:s'),
                                'additional' => sprintf(
                                    'Password [%s] way to weak',
                                    (string)$password
                                )
                            )
                        )
                    );
                }
            } else {                
                $this->_handleEvent(
                    new Account_Domain_Account_AggregateRoot_Account_Event_PasswordsDidNotMatchExceptionThrown(
                        array(
                            'accountGuid' => (string)$this->_guid,
                            'message' => 'account.error.passwords.didnt.match',
                            'date' => date('Y-m-d H:i:s'),
                            'additional' => sprintf(
                            	'[%s:%s] not equals [%s:%s]', 
                                (string)$password, 
                                (string)$password->getEncoded(), 
                                (string)$passwordAgain, 
                                (string)$passwordAgain->getEncoded()
                            )
                        )
                    )
                );
            }
        } else {
            $this->_handleEvent(
                new Account_Domain_Account_AggregateRoot_Account_Event_AccountAlreadyExistsExceptionThrown(
                    array(
                        'accountGuid' => (string)$this->_guid,
                        'message' => 'account.error.account.already.exists',
                        'date' => date('Y-m-d H:i:s'),
                        'additional' => sprintf(
                            'This email [%s] was trying to setup more than once',
                            (string)$primaryEmailAddress
                        )
                    )
                )
            );
        }        
    }
    
    /**
     * @param Account_Domain_Account_AggregateRoot_Account_Event_AccountResurected $event
     */
    protected function onAccountResurected(
        Account_Domain_Account_AggregateRoot_Account_Event_AccountResurected $event
    )
    {        
        $this->_state = new Account_Domain_Account_ValueObject_State(
            $event->getState()
        );    
        
        $this->_currentPassword = new Account_Domain_Account_ValueObject_Password(
            $event->getEncodedPassword(),
            true
        );     
        
        $this->_activationKey = new Oxy_Guid($event->getEmailActivationKey());
    }
    
    /**
     * @param Account_Domain_Account_ValueObject_ProductsInformationCollection $productsToAdd
     */
    public function addNewProducts(
        Account_Domain_Account_ValueObject_ProductsInformationCollection $productsToAdd
    )
    { 
        if($this->_isInitialized() || $this->_isActive()){
            $addedProducts = array();
            $instances = array();
 
            if($productsToAdd->count() > 0){
                
                foreach($productsToAdd as $product){ 
                    if(!isset($instances[$product->getProductName()])){
                        $instances[$product->getProductName()] = 0;
                    }  
                            
                    $productKey = $this->_activeProducts->makeKey(
                        array(
                            (string)$product->getProductName(), 
                            (string)$product->getLicense()
                        )
                    );
                    
                    if(!$this->_activeProducts->exists($productKey)){
                        $productGuid = new Oxy_Guid();
                        $addedProducts[(string)$productGuid] = $product->toArray();
                        $instances[$product->getProductName()] += 1;
                    } 
                }     
                              
                if(count($addedProducts) > 0){       
                    $this->_handleEvent(
                        new Account_Domain_Account_AggregateRoot_Account_Event_ProductsAddedToAccount(
                            array(
                                'accountGuid' => (string)$this->_guid,
                                'email' => (string)$this->_primaryEmail,
                            	'products' => $addedProducts,
                                'date' => date('Y-m-d H:i:s'),
                                'instances' => $instances
                            )
                        )
                    );     
                }      
            }
        } else {
            $this->_throwWrongStateException('MSA::Account::addNewProduct', $this->_state);
        }
    }
        
    /**
     * @param Account_Domain_Account_AggregateRoot_Account_Event_ProductsAddedToAccount $event
     */
    protected function onProductsAddedToAccount(
        Account_Domain_Account_AggregateRoot_Account_Event_ProductsAddedToAccount $event
    )
    {     
        $this->_activeProducts->createFromArray($event->getProducts(), $this);
        $this->_childEntities->addCollection($this->_activeProducts);
    }
              
    /**
     * @param Account_Domain_Account_ValueObject_DevicesInformationCollection $devicesToAdd
     */
    public function addNewDevices(
        Account_Domain_Account_ValueObject_DevicesInformationCollection $devicesToAdd
    )
    {
        if($this->_isActive() && $this->_isLoggedIn()){
            $addedDevices = array();
            
            if($devicesToAdd->count() > 0){
                
                foreach($devicesToAdd as $device){   
                    if(!$this->_devices->exists((string)$device->getName())){
                        $deviceGuid = new Oxy_Guid();
                        $addedDevices[(string)$deviceGuid] = $device->toArray();
                    }
                }     
                                                    
                if(count($addedDevices) > 0){
                                        
                    $this->_handleEvent(
                        new Account_Domain_Account_AggregateRoot_Account_Event_DevicesAddedToAccount(
                            array(
                                'accountGuid' => (string)$this->_guid,
                                'email' => (string)$this->_primaryEmail,
                            	'devices' => $addedDevices,
                                'date' => date('Y-m-d H:i:s')
                            )
                        )
                    );
                } 
            }
        } else {
            $this->_throwWrongStateException('MSA::Account::addNewDevices', $this->_state);
        }
    }
    
    /**
     * @param Account_Domain_Account_AggregateRoot_Account_Event_DevicesAddedToAccount $event
     */
    protected function onDevicesAddedToAccount(
        Account_Domain_Account_AggregateRoot_Account_Event_DevicesAddedToAccount $event
    )
    {     
        $this->_devices->createFromArray($event->getDevices(), $this);
        $this->_childEntities->addCollection($this->_devices);
    }

    /**
     * @param Oxy_Guid $productGuid
     * @param Account_Domain_Account_ValueObject_License $deviceGuid
     * @param Account_Domain_Account_ValueObject_Properties $settings
     */
    public function installProductOnDevice(
        Account_Domain_Account_ValueObject_Name $productName,
        Account_Domain_Account_ValueObject_License $productLicense,
        Account_Domain_Account_ValueObject_Name $deviceName,
        Account_Domain_Account_ValueObject_Properties $settings
    )
    {
        if($this->_isActive() && $this->_isLoggedIn()){
            $productKey = $this->_activeProducts->makeKey(
                array(
                    (string)$productName,
                    (string)$productLicense,
                )
            );
            
            if($this->_activeProducts->exists($productKey) && $this->_devices->exists((string)$deviceName)){
                $skip = false;
                $deviceNameThatHasInstallation = null;
                /*foreach ($this->_devices as $device){
                    if($device->getInstallations()->exists((string)$productName)){
                        $skip = true;
                        $deviceNameThatHasInstallation = $device->getDeviceName();
                        break;
                    }
                }*/
                
                if($this->_devices->get((string)$deviceName)->getInstallations()->exists((string)$productName)){
                    $skip = true;
                    $deviceNameThatHasInstallation = (string)$deviceName;
                }
                
                if(!$skip){
                    $this->_devices
                             ->get((string)$deviceName)
                             ->installProduct($this->_activeProducts->get($productKey), $settings);
                } else {
                    $this->_handleEvent(
                        new Account_Domain_Account_AggregateRoot_Account_Event_InstallationAlreadyExistsExceptionThrown(
                            array(
                                'accountGuid' => (string)$this->_guid,
                                'message' => 'account.error.installation.already.exists',
                                'date' => date('Y-m-d H:i:s'),
                                'additional' => sprintf(
                                    'This installation of product [%s] was found on installed other device [%s]',
                                    (string)$productName,
                                    (string)$deviceNameThatHasInstallation
                                )
                            )
                        )
                    );    
                }
            } else {
                $this->_throwWrongStateException(
                	sprintf(
                	    'MSA::Account::installProductOnDevice - product does not exists or device try again, name [%s], license [%s], key [%s], device [%s] - ',
                	    $productName, $productLicense, $productKey, $deviceName
                	), 
                	$this->_state
                );
            }
        } else {
            $this->_throwWrongStateException('MSA::Account::installProductOnDevice', $this->_state);
        }       
    }
    
    /**
     * @param Account_Domain_Account_AggregateRoot_Account_Event_InstallationAlreadyExistsExceptionThrown $event
     */
    protected function onInstallationAlreadyExistsExceptionThrown(
        Account_Domain_Account_AggregateRoot_Account_Event_InstallationAlreadyExistsExceptionThrown $event
    )
    {     
    }
    
    /**
     * @param Oxy_Guid $productGuid
     * @param Oxy_Guid $deviceGuid
     * @param Account_Domain_Account_ValueObject_Properties $settings
     */
    public function uninstallProductFromDevice(
        Account_Domain_Account_ValueObject_Name $productName,
        Account_Domain_Account_ValueObject_Name $deviceName,
        Account_Domain_Account_ValueObject_Properties $settings
    )
    {      
        if($this->_isActive() && $this->_isLoggedIn()){
            if($this->_devices->exists((string)$deviceName)){
                $this->_devices
                     ->get((string)$deviceName)
                     ->uninstallProduct($productName);
            }
        } else {
            $this->_throwWrongStateException('MSA::Account::installProductOnDevice', $this->_state);
        }              
    }
    
    /**
     * Update device information
     *  
     * @param Account_Domain_Account_ValueObject_Name $deviceName
     * @param Account_Domain_Account_ValueObject_DeviceInformation $deviceInforamtion
     */
    public function updateDeviceInformation(
        Account_Domain_Account_ValueObject_Name $deviceName,
        Account_Domain_Account_ValueObject_DeviceInformation $deviceInforamtion
    )
    {
        if($this->_isActive() && $this->_isLoggedIn()){
            if($this->_devices->exists((string)$deviceName)){
                $this->_devices
                     ->get((string)$deviceName)
                     ->updateDetails($deviceInforamtion);
            }
        } else {
            $this->_throwWrongStateException('MSA::Account::installProductOnDevice', $this->_state);
        }           
    }
       
    /**
     * Move product installation from one device to another device
     * 
     * @param Oxy_Guid $productGuid
     * @param Oxy_Guid $fromDeviceGuid
     * @param Oxy_Guid $toDeviceGuid
     * @param Account_Domain_Account_ValueObject_Properties $settings
     */
    public function moveProductInstallationFromDeviceToDevice(
        Oxy_Guid $productGuid,
        Oxy_Guid $fromDeviceGuid,
        Oxy_Guid $toDeviceGuid,
        Account_Domain_Account_ValueObject_Properties $settings
    )
    {  
        throw new Exception('Not implemented!');
    }
    
    /**
     * Reinstall product
     * 
     * @param Oxy_Guid $productGuid
     * @param Oxy_Guid $deviceGuid
     * @param Account_Domain_Account_ValueObject_Properties $settings
     */
    public function reinstallProductOnDevice(
        Account_Domain_Account_ValueObject_Name $productName,
        Account_Domain_Account_ValueObject_Name $deviceName,
        Account_Domain_Account_ValueObject_Properties $settings
    )
    { 
        if($this->_isActive() && $this->_isLoggedIn()){
            if($this->_devices->exists((string)$deviceName)){
                if($this->_devices->get((string)$deviceName)->getInstallations()->exists((string)$productName)){
                    $installation = $this->_devices->get((string)$deviceName)->getInstallations()->get((string)$productName);
                    // State check inside
                    $this->uninstallProductFromDevice($productName, $deviceName, $settings);        
                    
                    
                    // State check inside
                    $this->installProductOnDevice(
                        $productName, 
                        new Account_Domain_Account_ValueObject_License(
                            $installation->getProductLicense(),
                            $installation->getProductLicenseType()
                        ),
                        $deviceName, 
                        $settings
                    );
                }
            }
        } else {
            $this->_throwWrongStateException('MSA::Account::reinstallProductOnDevice', $this->_state);
        }  
    }
        
    /**
     * Changed owner personal information
     * 
     * @param Account_Domain_Account_ValueObject_PersonalInformation $ownerPersonalInformation
     */
    public function changeOwnerPersonalInformation(
        Account_Domain_Account_ValueObject_PersonalInformation $ownerPersonalInformation
    )
    {
        if($this->_isActive() && $this->_isLoggedIn()){
            $this->_handleEvent(
                new Account_Domain_Account_AggregateRoot_Account_Event_PersonalDetailsChanged(
                    array(
                        'accountGuid' => (string)$this->_guid,
                        'date' => date('Y-m-d H:i:s'),
                        'personalInformation' => $ownerPersonalInformation->toArray()
                    )
                )
            );
        } else {
            $this->_throwWrongStateException('MSA::Account::changeOwnerPersonalInformation', $this->_state);
        }
    }
    
	/**
     * @param Account_Domain_Account_AggregateRoot_Account_Event_PersonalDetailsChanged $event
     */
    protected function onPersonalDetailsChanged(
        Account_Domain_Account_AggregateRoot_Account_Event_PersonalDetailsChanged $event
    )
    {     
    }
        
    /**
     * Changed owner password
     * 
     * @param Account_Domain_Account_ValueObject_Password $password
     */
    public function changePassword(
        Account_Domain_Account_ValueObject_Password $password
    )
    {
        if($this->_isActive() && $this->_isLoggedIn()){
            $this->_handleEvent(
                new Account_Domain_Account_AggregateRoot_Account_Event_PasswordChanged(
                    array(
                        'accountGuid' => (string)$this->_guid,
                        'email' => (string)$this->_primaryEmail,
                        'date' => date('Y-m-d H:i:s'),
                        'encodedPassword' => (string)$password->getEncoded(),
                    )
                )
            );
        } else {
            $this->_throwWrongStateException('MSA::Account::changePassword', $this->_state);
        }
    }
    
	/**
     * @param Account_Domain_Account_AggregateRoot_Account_Event_PasswordChanged $event
     */
    protected function onPasswordChanged(
        Account_Domain_Account_AggregateRoot_Account_Event_PasswordChanged $event
    )
    {     
        $this->_currentPassword = new Account_Domain_Account_ValueObject_Password(
            $event->getEncodedPassword(),
            true
        ); 
    }
    
    /**
     * Change delivery in formation
     * 
     * @param Account_Domain_Account_ValueObject_DeliveryInformation $ownerDeliveryInformation
     */
    public function changeOwnerDeliveryInformation(
        Account_Domain_Account_ValueObject_DeliveryInformation $ownerDeliveryInformation
    )
    {
        if($this->_isActive() && $this->_isLoggedIn()){
            $this->_handleEvent(
                new Account_Domain_Account_AggregateRoot_Account_Event_DeliveryAddressChanged(
                    array(
                        'accountGuid' => (string)$this->_guid,
                        'date' => date('Y-m-d H:i:s'),
                        'deliveryInformation' => $ownerDeliveryInformation->toArray()
                    )
                )
            );
        } else {
            $this->_throwWrongStateException('MSA::Account::changeOwnerDeliveryInformation', $this->_state);
        }
    }
    
	/**
     * @param Account_Domain_Account_AggregateRoot_Account_Event_DeliveryAddressChanged $event
     */
    protected function onDeliveryAddressChanged(
        Account_Domain_Account_AggregateRoot_Account_Event_DeliveryAddressChanged $event
    )
    {     
    }
        
    /**
     * @param Account_Domain_Account_ValueObject_EmailAddress $newPrimaryEmailAddress
     */
    public function changePrimaryEmailAddress(
        Account_Domain_Account_ValueObject_EmailAddress $newPrimaryEmailAddress
    )
    {
        throw new Exception('Not implemented!');
    }
    
    /**
     * @param Account_Domain_Account_ValueObject_EmailAddress $newPrimaryEmailAddress
     */
    public function addSecondaryEmailAddress(
        Account_Domain_Account_ValueObject_EmailAddress $newSecondaryEmailAddress
    )
    {
        throw new Exception('Not implemented!');
    }
        
    /**
     * @param Account_Domain_Account_ValueObject_EmailAddress $newPrimaryEmailAddress
     */    
    public function removeSecondaryEmailAddress(
        Account_Domain_Account_ValueObject_EmailAddress $secondaryEmailAddress
    )
    {
        throw new Exception('Not implemented!');
    }

    /**
     * Remove products
     * 
     * @param Account_Domain_Account_ValueObject_GuidsCollection $products
     */
    public function removeProducts(
        Account_Domain_Account_ValueObject_GuidsCollection $products
    )
    {
        throw new Exception('Not implemented!');
    }

    /**
     * Remove devices
     * 
     * @param Account_Domain_Account_ValueObject_GuidsCollection $devices
     */
    public function removeDevices(
         Account_Domain_Account_ValueObject_GuidsCollection $devices
    )
    {
        throw new Exception('Not implemented!');
    }

    /**
     * Create snapshot
     *
     * @return Oxy_EventStore_Storage_Memento_MementoInterface
     */
    public function createMemento()
    {   
        $memento = array(
            'primaryEmail' => (string)$this->_primaryEmail,
            'currentPassword' => array(
                'password' => $this->_currentPassword->getPassword(),
                'isEncoded' => $this->_currentPassword->isEncoded(),
                'isAutoGenerated' => $this->_currentPassword->isAutoGenerated(),
            ),
            'state' => (string)$this->_state,
            'loginState' => (string)$this->_loginState,
            'activationKey' => (string)$this->_activationKey,
            'activeProducts' => $this->_activeProducts->toArray(),
            'activeDevices' => $this->_devices->toArray(),
            'accountGuid' => (string)$this->_guid,
            'settings' => $this->_settings->getProperties(),
            'eventName' => 'MementoCreated',
        );
          
        return new Account_Domain_Account_AggregateRoot_Account_Memento_Account(
            $memento
        );
    }
        
    /**
     * Load snapshot
     *
     * @param Oxy_EventStore_Storage_Memento_MementoInterface $memento
     * @return void
     */
    public function setMemento(Oxy_EventStore_Storage_Memento_MementoInterface $memento)
    {
        $this->_primaryEmail = new Account_Domain_Account_ValueObject_EmailAddress(
            $memento->getPrimaryEmail()
        );
        
        $passwordData = $memento->getCurrentPassword();
        $this->_currentPassword = new Account_Domain_Account_ValueObject_Password(
            $passwordData['password'],
            $passwordData['isEncoded'],
            $passwordData['isAutoGenerated']
        );
        
        $this->_state = new Account_Domain_Account_ValueObject_State(
            $memento->getState()
        );
        $this->_loginState = new Account_Domain_Account_ValueObject_State(
            $memento->getLoginState()
        );
        $this->_activationKey = new Oxy_Guid(
            $memento->getActivationKey()
        );        
        
        $this->_activeProducts = new Account_Domain_Account_ValueObject_ProductsCollection();  

        $productMementos = array();
        foreach ($memento->getActiveProducts() as $productGuid => $mementoData){
            $productMementos[$productGuid] = new Account_Domain_Account_AggregateRoot_Account_Memento_Product(
                $mementoData
            );
        }
        
        $this->_activeProducts->createAndRestoreStateFromArray(
            $productMementos,
            $this
        );
        
        $this->_childEntities->addCollection($this->_activeProducts);
        
        
        $this->_devices = new Account_Domain_Account_ValueObject_DevicesCollection();

        $devicesMementos = array();
        foreach ($memento->getActiveDevices() as $deviceGuid => $mementoData){
            $devicesMementos[$deviceGuid] = new Account_Domain_Account_AggregateRoot_Account_Memento_Device(
                $mementoData
            );
        }
        
        $this->_devices->createAndRestoreStateFromArray(
            $devicesMementos,
            $this
        );
                
        $this->_childEntities->addCollection($this->_devices);
        
        $this->_guid = new Oxy_Guid($memento->getAccountGuid());
        $this->_settings = new Account_Domain_Account_ValueObject_Properties($memento->getSettings());
    }
}