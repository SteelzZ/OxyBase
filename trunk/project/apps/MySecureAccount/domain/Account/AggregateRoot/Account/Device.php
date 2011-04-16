<?php
class MySecureAccount_Domain_Account_AggregateRoot_Account_Device
    extends Oxy_Domain_AggregateRoot_EventSourcedChildEntityAbstract
{     
    /**
     * @var MySecureAccount_Domain_Account_ValueObject_State
     */
    protected $_state;
    
    /**
     * @var MySecureAccount_Domain_Account_ValueObject_Name
     */
    protected $_deviceName;

    /**
     * @var MySecureAccount_Domain_Account_ValueObject_InstallationsCollection
     */
    protected $_installations;

    /**
     * @var MySecureAccount_Domain_Account_AggregateRoot_Account
     */
    protected $_account;   
        
    /**
     * @return MySecureAccount_Domain_Account_ValueObject_State
     */
    public function getState()
    {
        return $this->_state;
    }

	/**
     * @return MySecureAccount_Domain_Account_ValueObject_InstallationsCollection
     */
    public function getInstallations()
    {
        return $this->_installations;
    }

	/**
     * @return MySecureAccount_Domain_Account_AggregateRoot_Account
     */
    public function getAccount()
    {
        return $this->_account;
    }

	/**
     * @return MySecureAccount_Domain_Account_ValueObject_Name
     */
    public function getDeviceName()
    {
        return $this->_deviceName;
    }

	/**
     * @param Oxy_Guid $guid
     * @param MySecureAccount_Domain_Account_AggregateRoot_Account $aggregateRoot
     */
    public function __construct(
        Oxy_Guid $guid,
        MySecureAccount_Domain_Account_AggregateRoot_Account $aggregateRoot = null,
        MySecureAccount_Domain_Account_ValueObject_Name $deviceName
    ) 
    {
        parent::__construct($guid, (string)$guid, $aggregateRoot);
        $this->_state = new MySecureAccount_Domain_Account_ValueObject_State(
            MySecureAccount_Domain_Account_ValueObject_State::DEVICE_INITIALIZED
        );
        $this->_installations = new MySecureAccount_Domain_Account_ValueObject_InstallationsCollection();
        $this->_deviceName = $deviceName; 
        $this->_account = $aggregateRoot; 
    } 
    
    /**
     * @return boolean
     */
    protected function _isInitialized()
    {
        if((string)$this->_state === MySecureAccount_Domain_Account_ValueObject_State::DEVICE_INITIALIZED){
            return true;
        } else {
            return false;
        }
    }
    
    /**
     * @param MySecureAccount_Domain_Account_AggregateRoot_Account_Product $product
     * @param MySecureAccount_Domain_Account_ValueObject_Properties $settings
     */
    public function installProduct(
        MySecureAccount_Domain_Account_AggregateRoot_Account_Product $product,
        MySecureAccount_Domain_Account_ValueObject_Properties $settings
    )
    {
        if($this->_isInitialized()){
            if(!$this->_installations->exists((string)$product->getGuid())){
                $this->_handleEvent(
                    new MySecureAccount_Domain_Account_AggregateRoot_Account_Event_ProductInstalled(
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
                    new MySecureAccount_Domain_Account_AggregateRoot_Account_Event_ProductAlreadyInstalledExceptionThrown(
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
     * @param MySecureAccount_Domain_Account_AggregateRoot_Account_Event_ProductInstalled $event
     */
    protected function onProductInstalled(
        MySecureAccount_Domain_Account_AggregateRoot_Account_Event_ProductInstalled $event
    )
    {
        $this->_installations->set(
            $event->getProductName(), 
            new MySecureAccount_Domain_Account_ValueObject_Installation(
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
     * @param MySecureAccount_Domain_Account_AggregateRoot_Account_Event_ProductAlreadyInstalledExceptionThrown $event
     */
    protected function onProductAlreadyInstalledExceptionThrown(
        MySecureAccount_Domain_Account_AggregateRoot_Account_Event_ProductAlreadyInstalledExceptionThrown $event
    )
    {   
    }
    
    /**
     * @param MySecureAccount_Domain_Account_ValueObject_Name $productName
     */
    public function uninstallProduct(MySecureAccount_Domain_Account_ValueObject_Name $productName)
    { 
        if($this->_isInitialized()){
            if($this->_installations->exists((string)$productName)){
                $this->_handleEvent(
                    new MySecureAccount_Domain_Account_AggregateRoot_Account_Event_ProductUninstalled(
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
                    new MySecureAccount_Domain_Account_AggregateRoot_Account_Event_ProductIsNotInstalledExceptionThrown(
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
     * @param MySecureAccount_Domain_Account_AggregateRoot_Account_Event_ProductUninstalled $event
     */
    protected function onProductUninstalled(
        MySecureAccount_Domain_Account_AggregateRoot_Account_Event_ProductUninstalled $event
    )
    {
        $this->_installations->remove($event->getProductName()); 
    }
    
    /**
     * @param MySecureAccount_Domain_Account_AggregateRoot_Account_Event_ProductIsNotInstalledExceptionThrown $event
     */
    protected function onProductIsNotInstalledExceptionThrown(
        MySecureAccount_Domain_Account_AggregateRoot_Account_Event_ProductIsNotInstalledExceptionThrown $event
    )
    {   
    }
    
    /**
     * @param MySecureAccount_Domain_Account_ValueObject_DeviceInformation $deviceInforamtion
     */
    public function updateDetails(
        MySecureAccount_Domain_Account_ValueObject_DeviceInformation $deviceInforamtion
    )
    {
        if($this->_isInitialized()){
            $this->_handleEvent(
                new MySecureAccount_Domain_Account_AggregateRoot_Account_Event_DeviceDetailsUpdated(
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
     * @param MySecureAccount_Domain_Account_AggregateRoot_Account_Event_DeviceDetailsUpdated $event
     */
    protected function onDeviceDetailsUpdated(
        MySecureAccount_Domain_Account_AggregateRoot_Account_Event_DeviceDetailsUpdated $event
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
          
        return new MySecureAccount_Domain_Account_AggregateRoot_Account_Memento_Device(
            $memento
        );         
    }

	/**
     * @see Oxy_EventStore_Storage_Memento_Originator_OriginatorInterface::setMemento()
     */
    public function setMemento(Oxy_EventStore_Storage_Memento_MementoInterface $memento)
    {
        $this->_state = new MySecureAccount_Domain_Account_ValueObject_State(
            $memento->getState()
        );
        
        $this->_deviceName = new MySecureAccount_Domain_Account_ValueObject_name(
            $memento->getDeviceName()
        );
        $this->_installations->createFromArray($memento->getInstallations());
    }
}