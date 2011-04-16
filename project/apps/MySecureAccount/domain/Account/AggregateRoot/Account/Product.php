<?php
class MySecureAccount_Domain_Account_AggregateRoot_Account_Product
    extends Oxy_Domain_AggregateRoot_EventSourcedChildEntityAbstract
    implements MySecureAccount_Domain_Account_AggregateRoot_Account_Product_ConfigurableInterface
{        
    /**
     * @var MySecureAccount_Domain_Account_ValueObject_State
     */
    protected $_state;
        
	/**
     * @var Oxy_Guid
     */ 
    protected $_configurationRequestGuid;    
    
    /**
     * @var MySecureAccount_Domain_Account_ValueObject_Name
     */
    protected $_productName;
    
    /**
     * @var MySecureAccount_Domain_Account_ValueObject_License
     */
    protected $_productLicense;
    
    /**
     * @var MySecureAccount_Domain_Account_AggregateRoot_Account
     */
    protected $_account;

    /**
     * @return MySecureAccount_Domain_Account_ValueObject_Name
     */
    public function getProductName()
    {
        return $this->_productName;
    }

	/**
     * @return MySecureAccount_Domain_Account_ValueObject_License
     */
    public function getProductLicense()
    {
        return $this->_productLicense;
    }
        
	/**
     * @return Oxy_Guid
     */
    public function getConfigurationRequestGuid()
    {
        return $this->_configurationRequestGuid;
    }

	/**
     * @param Oxy_Guid $guid
     * @param MySecureAccount_Domain_Account_AggregateRoot_Account $aggregateRoot
     * @param MySecureAccount_Domain_Account_ValueObject_Name $name
     * @param MySecureAccount_Domain_Account_ValueObject_License $license
     */
    public function __construct(
        Oxy_Guid $guid,
        MySecureAccount_Domain_Account_AggregateRoot_Account $aggregateRoot = null,
        MySecureAccount_Domain_Account_ValueObject_Name $name,
        MySecureAccount_Domain_Account_ValueObject_License $license
    ) 
    {
        parent::__construct($guid, (string)$guid, $aggregateRoot);
        $this->_state = new MySecureAccount_Domain_Account_ValueObject_State(
            MySecureAccount_Domain_Account_ValueObject_State::PRODUCT_INITIALIZED
        );
        $this->_account = $aggregateRoot;
        $this->_productLicense = $license; 
        $this->_productName = $name; 
    } 
    
    /**
     * @return boolean
     */
    protected function _isInitialized()
    {
        if((string)$this->_state === MySecureAccount_Domain_Account_ValueObject_State::PRODUCT_INITIALIZED){
            return true;
        } else {
            return false;
        }
    }
    
    /**
     * @return boolean
     */
    protected function _isRequested()
    {
        if( (string)$this->_state === MySecureAccount_Domain_Account_ValueObject_State::PRODUCT_CONFIGURATION_REQUESTED_AGAIN
            || (string)$this->_state === MySecureAccount_Domain_Account_ValueObject_State::PRODUCT_CONFIGURATION_REQUESTED){
            return true;
        } else {
            return false;
        }
    }
    
    /**
     * @see MySecureAccount_Domain_Account_AggregateRoot_Account_Product_ConfigurableInterface::configure()
     */
    public function configure(MySecureAccount_Domain_Account_AggregateRoot_Account_Device $forDevice)
    {
        if($this->_isInitialized()){           
            $this->_handleEvent(
                new MySecureAccount_Domain_Account_AggregateRoot_Account_Event_RequestToConfigureProductIssued(
                    array(
                        'accountGuid' => (string)$this->_account->getGuid(),
                        'accountEmail' => (string)$this->_account->getPrimaryEmail(),
                        'productGuid' => (string)$this->_guid,
                        'deviceGuid' => (string)$forDevice->getGuid(),
                        'deviceName' => (string)$forDevice->getDeviceName(),
                        'productName' => (string)$this->_productName,
                        'productLicense' => (string)$this->_productLicense->getCode(),
                        'productLicenseType' => (string)$this->_productLicense->getType(),
                        'date' => date('Y-m-d H:i:s'),
                        'configurationRequestGuid' => (string)new Oxy_Guid(),
                        'state' => MySecureAccount_Domain_Account_ValueObject_State::PRODUCT_CONFIGURATION_REQUESTED
                    )
                )
            );
        } else {
            if($this->_isRequested()){
                $this->_handleEvent(
                    new MySecureAccount_Domain_Account_AggregateRoot_Account_Event_RequestToReconfigureProductIssued(
                        array(
                            'accountGuid' => (string)$this->_account->getGuid(),
                            'accountEmail' => (string)$this->_account->getPrimaryEmail(),
                            'productGuid' => (string)$this->_guid,
                            'deviceGuid' => (string)$forDevice->getGuid(),
                            'deviceName' => (string)$forDevice->getDeviceName(),
                            'productName' => (string)$this->_productName,
                            'productLicense' => (string)$this->_productLicense->getCode(),
                            'productLicenseType' => (string)$this->_productLicense->getType(),
                            'date' => date('Y-m-d H:i:s'),
                            'configurationGuid' => (string)$this->_configurationRequestGuid,
                            'state' => MySecureAccount_Domain_Account_ValueObject_State::PRODUCT_CONFIGURATION_REQUESTED_AGAIN
                        )
                    )
                );
            } else {
                $this->_throwWrongStateException('MSA::Account::Product::configure', $this->_state);
            }
        }   
    }
          
    /**
     * @param MySecureAccount_Domain_Account_AggregateRoot_Account_Event_RequestToConfigureProductIssued $event
     */
    protected function onRequestToConfigureProductIssued(
        MySecureAccount_Domain_Account_AggregateRoot_Account_Event_RequestToConfigureProductIssued $event
    )
    {
        $this->_state = new MySecureAccount_Domain_Account_ValueObject_State(
            $event->getState()
        );
        $this->_configurationRequestGuid = new Oxy_Guid(
            $event->getConfigurationRequestGuid()
        );
        $this->_productName = new MySecureAccount_Domain_Account_ValueObject_Name(
            $event->getProductName()
        );
        $this->_productLicense = new MySecureAccount_Domain_Account_ValueObject_License(
            $event->getProductLicense(),
            $event->getProductLicenseType()
        );
    }
          
    /**
     * @param MySecureAccount_Domain_Account_AggregateRoot_Account_Event_RequestToReconfigureProductIssued $event
     */
    protected function onRequestToReconfigureProductIssued(
        MySecureAccount_Domain_Account_AggregateRoot_Account_Event_RequestToReconfigureProductIssued $event
    )
    {
        $this->_state = new MySecureAccount_Domain_Account_ValueObject_State(
            $event->getState()
        );
        $this->_configurationRequestGuid = new Oxy_Guid(
            $event->getConfigurationGuid()
        );
        $this->_productName = new MySecureAccount_Domain_Account_ValueObject_Name(
            $event->getProductName()
        );
        $this->_productLicense = new MySecureAccount_Domain_Account_ValueObject_License(
            $event->getProductLicense(),
            $event->getProductLicenseType()
        );
    }
    
	/**
     * @see Oxy_EventStore_Storage_Memento_Originator_OriginatorInterface::createMemento()
     */
    public function createMemento()
    {
        $memento = array(
            'productGuid' => (string)$this->_guid,
            'productName' => (string)$this->_productName,
            'productLicense' => (string)$this->_productLicense->getCode(),
            'productLicenseType' => (string)$this->_productLicense->getType(),
            'configurationRequestGuid' => (string)$this->_configurationRequestGuid,
            'state' => (string)$this->_state,
            'eventName' => 'MementoCreated',
        );
          
        return new MySecureAccount_Domain_Account_AggregateRoot_Account_Memento_Product(
            $memento
        );         
    }

	/**
     * @see Oxy_EventStore_Storage_Memento_Originator_OriginatorInterface::setMemento()
     */
    public function setMemento(Oxy_EventStore_Storage_Memento_MementoInterface $memento)
    {
        $this->_productName = new MySecureAccount_Domain_Account_ValueObject_Name(
            $memento->getProductName()
        );
        
        $this->_productLicense = new MySecureAccount_Domain_Account_ValueObject_License(
            $memento->getProductLicense(),
            $memento->getProductLicenseType()
        );
        
        $this->_configurationRequestGuid = new Oxy_Guid(
            $memento->getConfigurationRequestGuid()
        );
        
        $this->_state = new MySecureAccount_Domain_Account_ValueObject_State(
            $memento->getState()
        );
    }
}