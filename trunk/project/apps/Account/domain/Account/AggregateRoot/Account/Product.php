<?php
class Account_Domain_Account_AggregateRoot_Account_Product
    extends Oxy_Domain_AggregateRoot_EventSourcedChildEntityAbstract
    implements Account_Domain_Account_AggregateRoot_Account_Product_ConfigurableInterface
{        
    /**
     * @var Account_Domain_Account_ValueObject_State
     */
    protected $_state;
        
	/**
     * @var Oxy_Guid
     */ 
    protected $_configurationRequestGuid;    
    
    /**
     * @var Account_Domain_Account_ValueObject_Name
     */
    protected $_productName;
    
    /**
     * @var Account_Domain_Account_ValueObject_License
     */
    protected $_productLicense;
    
    /**
     * @var Account_Domain_Account_AggregateRoot_Account
     */
    protected $_account;

	/**
     * @param Oxy_Guid $guid
     * @param Account_Domain_Account_AggregateRoot_Account $aggregateRoot
     * @param Account_Domain_Account_ValueObject_Name $name
     * @param Account_Domain_Account_ValueObject_License $license
     */
    public function __construct(
        Oxy_Guid $guid,
        Account_Domain_Account_AggregateRoot_Account $aggregateRoot = null,
        Account_Domain_Account_ValueObject_Name $name,
        Account_Domain_Account_ValueObject_License $license
    ) 
    {
        parent::__construct($guid, (string)$guid, $aggregateRoot);
        $this->_state = new Account_Domain_Account_ValueObject_State(
            Account_Domain_Account_ValueObject_State::PRODUCT_INITIALIZED
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
        if((string)$this->_state === Account_Domain_Account_ValueObject_State::PRODUCT_INITIALIZED){
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
        if( (string)$this->_state === Account_Domain_Account_ValueObject_State::PRODUCT_CONFIGURATION_REQUESTED_AGAIN
            || (string)$this->_state === Account_Domain_Account_ValueObject_State::PRODUCT_CONFIGURATION_REQUESTED){
            return true;
        } else {
            return false;
        }
    }
    
    /**
     * @see Account_Domain_Account_AggregateRoot_Account_Product_ConfigurableInterface::configure()
     */
    public function configure(Account_Domain_Account_AggregateRoot_Account_Device $forDevice)
    {
        if($this->_isInitialized()){           
            $this->_handleEvent(
                new Account_Domain_Account_AggregateRoot_Account_Event_RequestToConfigureProductIssued(
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
                        'state' => Account_Domain_Account_ValueObject_State::PRODUCT_CONFIGURATION_REQUESTED
                    )
                )
            );
        } else {
            if($this->_isRequested()){
                $this->_handleEvent(
                    new Account_Domain_Account_AggregateRoot_Account_Event_RequestToReconfigureProductIssued(
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
                            'state' => Account_Domain_Account_ValueObject_State::PRODUCT_CONFIGURATION_REQUESTED_AGAIN
                        )
                    )
                );
            } else {
                $this->_throwWrongStateException('MSA::Account::Product::configure', $this->_state);
            }
        }   
    }
          
    /**
     * @param Account_Domain_Account_AggregateRoot_Account_Event_RequestToConfigureProductIssued $event
     */
    protected function onRequestToConfigureProductIssued(
        Account_Domain_Account_AggregateRoot_Account_Event_RequestToConfigureProductIssued $event
    )
    {
        $this->_state = new Account_Domain_Account_ValueObject_State(
            $event->getState()
        );
        $this->_configurationRequestGuid = new Oxy_Guid(
            $event->getConfigurationRequestGuid()
        );
        $this->_productName = new Account_Domain_Account_ValueObject_Name(
            $event->getProductName()
        );
        $this->_productLicense = new Account_Domain_Account_ValueObject_License(
            $event->getProductLicense(),
            $event->getProductLicenseType()
        );
    }
          
    /**
     * @param Account_Domain_Account_AggregateRoot_Account_Event_RequestToReconfigureProductIssued $event
     */
    protected function onRequestToReconfigureProductIssued(
        Account_Domain_Account_AggregateRoot_Account_Event_RequestToReconfigureProductIssued $event
    )
    {
        $this->_state = new Account_Domain_Account_ValueObject_State(
            $event->getState()
        );
        $this->_configurationRequestGuid = new Oxy_Guid(
            $event->getConfigurationGuid()
        );
        $this->_productName = new Account_Domain_Account_ValueObject_Name(
            $event->getProductName()
        );
        $this->_productLicense = new Account_Domain_Account_ValueObject_License(
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
          
        return new Account_Domain_Account_AggregateRoot_Account_Memento_Product(
            $memento
        );         
    }

	/**
     * @see Oxy_EventStore_Storage_Memento_Originator_OriginatorInterface::setMemento()
     */
    public function setMemento(Oxy_EventStore_Storage_Memento_MementoInterface $memento)
    {
        $this->_productName = new Account_Domain_Account_ValueObject_Name(
            $memento->getProductName()
        );
        
        $this->_productLicense = new Account_Domain_Account_ValueObject_License(
            $memento->getProductLicense(),
            $memento->getProductLicenseType()
        );
        
        $this->_configurationRequestGuid = new Oxy_Guid(
            $memento->getConfigurationRequestGuid()
        );
        
        $this->_state = new Account_Domain_Account_ValueObject_State(
            $memento->getState()
        );
    }
}