<?php
class Account_Domain_Account_ValueObject_State
{
    const ACCOUNT_STATE_NEW = 'new';
    const ACCOUNT_STATE_INITIALIZED = 'initialized';
    const ACCOUNT_STATE_ACTIVATED = 'activated';
    const ACCOUNT_STATE_DEACTIVATED = 'deactivated';
    
    const EMAIL_ADDRESS_NOT_ACTIVE = 'email-not-active';
    const EMAIL_ADDRESS_ACTIVE = 'email-active';
    
    const PRODUCT_INITIALIZED = 'product-initialized';
    const PRODUCT_CONFIGURATION_REQUESTED = 'product-configuration-requested';
    const PRODUCT_CONFIGURATION_REQUESTED_AGAIN = 'product-configuration-requested-again';
    const PRODUCT_CONFIGURATION_SAVED = 'product-configuration-saved';
    
    const DEVICE_INITIALIZED = 'device-initialized';
    
    const LOGGED_IN = 'logged-in';
    const LOGGED_OUT = 'logged-out';
    
    const SERVICE_CONFIGURATION_INITIALIZED = 'service-configuration-initialized';
    const SERVICE_CONFIGURATION_STARTED = 'service-configuration-started';
    const SERVICE_CONFIGURATION_FINISHED = 'service-configuration-finished';
    
    /**
     * @var string
     */
    private $_state;
    
    /**
     * @param string $state
     */
    public function __construct($state)
    {
        $this->_state = $state;
    }
    
    /**
     * @return string
     */
    public function getState()
    {
        return $this->_state;
    }
    
    /**
     * @return string
     */
    public function __toString()
    {
        return $this->_state;
    }
}