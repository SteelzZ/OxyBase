<?php
class Account_Domain_Account_AggregateRoot_Account_Device
    extends Oxy_Domain_AggregateRoot_EventSourcedChildEntityAbstract
{     
    /**
     * @var Account_Domain_Account_ValueObject_State
     */
    protected $_state;
    
    /**
     * @var Account_Domain_Account_ValueObject_Name
     */
    protected $_deviceName;

    /**
     * @var Account_Domain_Account_ValueObject_InstallationsCollection
     */
    protected $_installations;

    /**
     * @var Account_Domain_Account_AggregateRoot_Account
     */
    protected $_account;   
        
    /**
     * @return Account_Domain_Account_ValueObject_State
     */
    public function getState()
    {
        return $this->_state;
    }

	/**
     * @return Account_Domain_Account_ValueObject_InstallationsCollection
     */
    public function getInstallations()
    {
        return $this->_installations;
    }

	/**
     * @return Account_Domain_Account_AggregateRoot_Account
     */
    public function getAccount()
    {
        return $this->_account;
    }

	/**
     * @return Account_Domain_Account_ValueObject_Name
     */
    public function getDeviceName()
    {
        return $this->_deviceName;
    }

	/**
     * @param Oxy_Guid $guid
     * @param Account_Domain_Account_AggregateRoot_Account $aggregateRoot
     */
    public function __construct(
        Oxy_Guid $guid,
        Account_Domain_Account_AggregateRoot_Account $aggregateRoot = null,
        Account_Domain_Account_ValueObject_Name $deviceName
    ) 
    {
        parent::__construct($guid, (string)$guid, $aggregateRoot);
        $this->_state = new Account_Domain_Account_ValueObject_State(
            Account_Domain_Account_ValueObject_State::DEVICE_INITIALIZED
        );
        $this->_installations = new Account_Domain_Account_ValueObject_InstallationsCollection();
        $this->_deviceName = $deviceName; 
        $this->_account = $aggregateRoot; 
    } 
    
    /**
     * @return boolean
     */
    protected function _isInitialized()
    {
        if((string)$this->_state === Account_Domain_Account_ValueObject_State::DEVICE_INITIALIZED){
            return true;
        } else {
            return false;
        }
    }
    
    /**
     * @param Account_Domain_Account_AggregateRoot_Account_Product $product
     * @param Account_Domain_Account_ValueObject_Properties $settings
     */
    public function installProduct(
        Account_Domain_Account_AggregateRoot_Account_Product $product,
        Account_Domain_Account_ValueObject_Properties $settings
    )
    {
        if($this->_isInitialized()){
            if(!$this->_installations->exists((string)$product->getGuid())){
                $this->_handleEvent(
                    new Account_Domain_Account_AggregateRoot_Account_Event_ProductInstalled(
                        array(
                            'accountGuid' => (string)$this->_account->getGuid(),
                            'deviceGuid' => (string)$this->_guid,
                            'productGuid' => (string)$product->getGuid(),
                            'productName' => (string)$product->getProductName(),
                            'productLicense' => (string)$product->getProductLicense()->getCode(),
                            'productLicenseType' => (string)$product->getProductLicense()->getType(),
                            'date' => date('Y-m-d H:i:s'),
                            'installationGuid' => (string)new Oxy_Guid(),
                            'installationSettings' => $settings->getProperties(),
                            'configurationGuid' => (string)$product->getConfigurationRequestGuid()
                        )
                    )
                );
                
                $product->configure($this);
                
            } else {
                $this->_handleEvent(
                    new Account_Domain_Account_AggregateRoot_Account_Event_ProductAlreadyInstalledExceptionThrown(
                        array(
                            'accountGuid' => (string)$this->_account->getGuid(),
                            'childGuid' => (string)$this->_guid,
                            'message' => 'account.device.error.product.already.installed',
                            'date' => date('Y-m-d H:i:s'),
                            'additional' => sprintf(
                                'Product [%s] with license [%s] of [%s] type was already installed! Guid [%s]',
                                (string)$product->getProductName(),
                                (string)$product->getProductLicense()->getCode(),
                                (string)$product->getProductLicense()->getType(),
                                (string)$product->getGuid()
                            )
                        )
                    )
                );
            }
        } else {
            $this->_throwWrongStateException('MSA::Account::installProduct', $this->_state);
        }
    }
        
    /**
     * @param Account_Domain_Account_AggregateRoot_Account_Event_ProductInstalled $event
     */
    protected function onProductInstalled(
        Account_Domain_Account_AggregateRoot_Account_Event_ProductInstalled $event
    )
    {
        $this->_installations->set(
            $event->getProductName(), 
            new Account_Domain_Account_ValueObject_Installation(
                $event->getInstallationGuid(),
                $event->getConfigurationGuid(),
                $event->getProductGuid(),
                $event->getProductLicense(),
                $event->getProductLicenseType(),
                $event->getDate()
            )
        );   
    }
    
    /**
     * @param Account_Domain_Account_AggregateRoot_Account_Event_ProductAlreadyInstalledExceptionThrown $event
     */
    protected function onProductAlreadyInstalledExceptionThrown(
        Account_Domain_Account_AggregateRoot_Account_Event_ProductAlreadyInstalledExceptionThrown $event
    )
    {   
    }
    
    /**
     * @param Account_Domain_Account_ValueObject_Name $productName
     */
    public function uninstallProduct(Account_Domain_Account_ValueObject_Name $productName)
    { 
        if($this->_isInitialized()){
            if($this->_installations->exists((string)$productName)){
                $this->_handleEvent(
                    new Account_Domain_Account_AggregateRoot_Account_Event_ProductUninstalled(
                        array(
                            'accountGuid' => (string)$this->_account->getGuid(),
                            'deviceGuid' => (string)$this->_guid,
                            'productGuid' => (string)$this->_installations->get((string)$productName)->getProductGuid(),
                            'productName' => (string)$productName,
                            'date' => date('Y-m-d H:i:s'),
                            'installationGuid' => (string)$this->_installations->get((string)$productName)->getGuid(),
                            'configurationGuid' => (string)$this->_installations->get((string)$productName)->getConfigurationGuid()
                        )
                    )
                );               
            } else {
                $this->_handleEvent(
                    new Account_Domain_Account_AggregateRoot_Account_Event_ProductIsNotInstalledExceptionThrown(
                        array(
                            'accountGuid' => (string)$this->_account->getGuid(),
                            'childGuid' => (string)$this->_guid,
                            'message' => 'account.device.error.product.already.installed',
                            'date' => date('Y-m-d H:i:s'),
                            'additional' => sprintf(
                                'Product [%s] was already installed! Guid [%s]',
                                (string)$productName,
                                (string)(string)$this->_installations->get((string)$productName)->getProductGuid()
                            )
                        )
                    )
                );
            }
        } else {
            $this->_throwWrongStateException('MSA::Account::installProduct', $this->_state);
        }
    }
    
    /**
     * @param Account_Domain_Account_AggregateRoot_Account_Event_ProductUninstalled $event
     */
    protected function onProductUninstalled(
        Account_Domain_Account_AggregateRoot_Account_Event_ProductUninstalled $event
    )
    {
        $this->_installations->remove($event->getProductName()); 
    }
    
    /**
     * @param Account_Domain_Account_AggregateRoot_Account_Event_ProductIsNotInstalledExceptionThrown $event
     */
    protected function onProductIsNotInstalledExceptionThrown(
        Account_Domain_Account_AggregateRoot_Account_Event_ProductIsNotInstalledExceptionThrown $event
    )
    {   
    }
    
    /**
     * @param Account_Domain_Account_ValueObject_DeviceInformation $deviceInforamtion
     */
    public function updateDetails(
        Account_Domain_Account_ValueObject_DeviceInformation $deviceInforamtion
    )
    {
        if($this->_isInitialized()){
            $this->_handleEvent(
                new Account_Domain_Account_AggregateRoot_Account_Event_DeviceDetailsUpdated(
                    array(
                        'accountGuid' => (string)$this->_account->getGuid(),
                        'deviceGuid' => (string)$this->_guid,
                        'newDetails' => $deviceInforamtion->toArray(),
                        'date' => date('Y-m-d H:i:s'),
                    )
                )
            );
        }        
    }
    
    /**
     * @param Account_Domain_Account_AggregateRoot_Account_Event_DeviceDetailsUpdated $event
     */
    protected function onDeviceDetailsUpdated(
        Account_Domain_Account_AggregateRoot_Account_Event_DeviceDetailsUpdated $event
    )
    {   
    }
    	                    
	/**
     * @see Oxy_EventStore_Storage_Memento_Originator_OriginatorInterface::createMemento()
     */
    public function createMemento()
    {
        $memento = array(
            'state' => (string)$this->_state,
            'deviceName' => (string)$this->_deviceName,
            'installations' => (array)$this->_installations->toArray(),
            'eventName' => 'MementoCreated',
        );
          
        return new Account_Domain_Account_AggregateRoot_Account_Memento_Device(
            $memento
        );         
    }

	/**
     * @see Oxy_EventStore_Storage_Memento_Originator_OriginatorInterface::setMemento()
     */
    public function setMemento(Oxy_EventStore_Storage_Memento_MementoInterface $memento)
    {
        $this->_state = new Account_Domain_Account_ValueObject_State(
            $memento->getState()
        );
        
        $this->_deviceName = new Account_Domain_Account_ValueObject_name(
            $memento->getDeviceName()
        );
        $this->_installations->createFromArray($memento->getInstallations());
    }
}